<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Review;
use Illuminate\Support\Str;

class AutoModerationService
{
    private const MIN_LENGTH = 15;

    private const MAX_LENGTH = 1500;

    private const SIMILARITY_THRESHOLD = 85.0;

    private const RECENT_REVIEWS_LIMIT = 50;

    /**
     * @return array{status: 'approved'|'pending'|'rejected', reason: ?string, score: float}
     */
    public function check(string $comment): array
    {
        $normalized = trim($comment);
        $length = mb_strlen($normalized);

        if ($length < self::MIN_LENGTH) {
            return [
                'status' => 'rejected',
                'reason' => 'Comment is too short (minimum '.self::MIN_LENGTH.' characters).',
                'score' => 0.0,
            ];
        }

        if ($length > self::MAX_LENGTH) {
            return [
                'status' => 'pending',
                'reason' => 'Comment exceeds maximum length ('.self::MAX_LENGTH.' characters).',
                'score' => 0.5,
            ];
        }

        $stopWord = $this->findStopWord($normalized);

        if ($stopWord !== null) {
            return [
                'status' => 'rejected',
                'reason' => 'Comment contains prohibited word: '.$stopWord,
                'score' => 0.0,
            ];
        }

        if ($this->isTooSimilarToRecent($normalized)) {
            return [
                'status' => 'rejected',
                'reason' => 'Comment is too similar to an existing review.',
                'score' => 0.2,
            ];
        }

        return [
            'status' => 'approved',
            'reason' => null,
            'score' => 1.0,
        ];
    }

    private function findStopWord(string $comment): ?string
    {
        $lowerComment = mb_strtolower($comment);

        foreach (config('moderation.stop_words', []) as $word) {
            if (! is_string($word) || $word === '') {
                continue;
            }

            if (Str::contains($lowerComment, mb_strtolower($word))) {
                return $word;
            }
        }

        return null;
    }

    private function isTooSimilarToRecent(string $comment): bool
    {
        $recentComments = Review::query()
            ->whereIn('status', [Review::STATUS_APPROVED, Review::STATUS_PENDING])
            ->whereNotNull('comment')
            ->latest('id')
            ->limit(self::RECENT_REVIEWS_LIMIT)
            ->pluck('comment');

        foreach ($recentComments as $existingComment) {
            if (! is_string($existingComment) || $existingComment === '') {
                continue;
            }

            if ($this->similarityPercent($comment, $existingComment) >= self::SIMILARITY_THRESHOLD) {
                return true;
            }
        }

        return false;
    }

    private function similarityPercent(string $first, string $second): float
    {
        $percent = 0.0;
        similar_text(mb_strtolower($first), mb_strtolower($second), $percent);

        if ($percent >= self::SIMILARITY_THRESHOLD) {
            return $percent;
        }

        $maxLength = max(mb_strlen($first), mb_strlen($second));

        if ($maxLength === 0) {
            return 100.0;
        }

        if (strlen($first) > 255 || strlen($second) > 255) {
            return $percent;
        }

        $distance = levenshtein($first, $second);

        return (1 - ($distance / $maxLength)) * 100;
    }
}
