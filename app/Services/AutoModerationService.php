<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ModerationLog;
use App\Models\Review;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Throwable;

class AutoModerationService
{
    /**
     * Проверка отзыва системой автоматической модерации.
     *
     * @return array{status: 'approved'|'pending'|'rejected', reason: ?string, score: float}
     */
    public function check(Review $review): array
    {
        $score = 1.0;
        $reasons = [];
        $content = $this->getContent($review);

        // ─────────────────────────────────────────────────────────────
        // 1. Проверка длины текста
        // ─────────────────────────────────────────────────────────────
        $len = mb_strlen($content);
        $minLength = config('moderation.min_length', 15);
        $maxLength = config('moderation.max_length', 1500);

        if ($len < $minLength) {
            $score -= 0.4;
            $reasons[] = 'too_short';
        }
        if ($len > $maxLength) {
            $score -= 0.2;
            $reasons[] = 'too_long';
        }

        // ─────────────────────────────────────────────────────────────
        // 2. Проверка на стоп-слова
        // ─────────────────────────────────────────────────────────────
        $text = mb_strtolower($content);
        foreach (config('moderation.stop_words', []) as $word) {
            if (!is_string($word) || $word === '') {
                continue;
            }
            if (Str::contains($text, mb_strtolower($word))) {
                $score -= 0.6;
                $reasons[] = 'stop_word:' . $word;
                break;
            }
        }

        // ─────────────────────────────────────────────────────────────
        // 3. Проверка на наличие ссылок
        // ─────────────────────────────────────────────────────────────
        if (preg_match('/https?:\/\/|www\./i', $content)) {
            $score -= 0.3;
            $reasons[] = 'contains_link';
        }

        // ─────────────────────────────────────────────────────────────
        // 4. Rate limit по IP
        // ─────────────────────────────────────────────────────────────
        $clientIp = $review->client_ip ?? request()?->ip();
        if ($clientIp) {
            $ipKey = "moderation:ip:{$clientIp}";
            $maxPerHour = config('moderation.max_reviews_per_ip_per_hour', 10);
            
            // Важно: инкремент счётчика должен происходить после успешной отправки!
            // См. заметку ниже по интеграции.
            $currentCount = (int) Cache::get($ipKey, 0);
            if ($currentCount >= $maxPerHour) {
                $score -= 0.5;
                $reasons[] = 'ip_rate_exceeded';
            }
        }

        // ─────────────────────────────────────────────────────────────
        // 5. Проверка на дубликаты (в пределах временного окна)
        // ─────────────────────────────────────────────────────────────
        $windowHours = config('moderation.duplicate_window_hours', 24);
        $duplicateExists = Review::query()
            ->where(function ($q) use ($content) {
                $q->where('comment', $content);
            })
            ->where('id', '!=', $review->id)
            ->where('created_at', '>', now()->subHours($windowHours))
            ->exists();

        if ($duplicateExists) {
            $score -= 0.5;
            $reasons[] = 'duplicate';
        }

        // ─────────────────────────────────────────────────────────────
        // 6. Нормализация финального скоринга и определение статуса
        // ─────────────────────────────────────────────────────────────
        $finalScore = max(0.0, min(1.0, $score));
        $approveThreshold = config('moderation.auto_approve_threshold', 0.8);
        $rejectThreshold = config('moderation.auto_reject_threshold', 0.4);

        $status = match (true) {
            $finalScore >= $approveThreshold => 'approved',
            $finalScore <= $rejectThreshold  => 'rejected',
            default                           => 'pending',
        };

        // ─────────────────────────────────────────────────────────────
        // 7. Логирование в ModerationLog
        // ─────────────────────────────────────────────────────────────
        try {
            ModerationLog::create([
                'review_id'    => $review->id,
                'moderator_id' => null,
                'action'       => 'system_check',
                'details'      => [
                    'score'   => round($finalScore, 2),
                    'reasons' => $reasons,
                    'status'  => $status,
                    'meta'    => [
                        'length' => $len,
                        'ip'     => $clientIp,
                    ],
                ],
                'created_at' => now(),
            ]);
        } catch (Throwable $e) {
            // Логирование не должно ломать основной поток
            report($e);
        }

        // ─────────────────────────────────────────────────────────────
        // 8. Формирование ответа в нужном формате
        // ─────────────────────────────────────────────────────────────
        $reason = null;
        if ($status !== 'approved' || !empty($reasons)) {
            $reason = !empty($reasons) 
                ? implode(', ', $reasons) 
                : 'manual_review_required';
        }

        return [
            'status' => $status,
            'score'  => round($finalScore, 2),
            'reason' => $reason,
        ];
    }

    /**
     * Универсальное получение текста отзыва (поддержка content/comment).
     */
    private function getContent(Review $review): string
    {
        return trim($review->content ?? $review->comment ?? '');
    }
}