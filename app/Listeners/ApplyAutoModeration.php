<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ReviewSubmitted;
use App\Models\Review;
use App\Services\AutoModerationService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ApplyAutoModeration
{
    public function __construct(
        private readonly AutoModerationService $moderationService,
    ) {}

    public function handle(ReviewSubmitted $event): void
    {
        // Передаём модель Review, а не строку
        $result = $this->moderationService->check($event->review);

        DB::transaction(function () use ($event, $result): void {
            $event->review->update([
                'status'                 => $result['status'],
                'moderation_score'       => $result['score'],
                'auto_moderation_reason' => $result['reason'],
            ]);
        });

        // 🔥 Инкремент счётчика ИП только после успешного сохранения!
        $this->incrementIpCounter($event->review);

        $event->review->refresh();
    }

    /**
     * Инкремент счётчика отправок с одного IP для rate limiting.
     */
    private function incrementIpCounter(Review $review): void
    {
        $clientIp = $review->client_ip ?? request()?->ip();
        if (!$clientIp) {
            return;
        }

        $ipKey = "moderation:ip:{$clientIp}";
        $ttlHours = config('moderation.rate_limit_window_hours', 1);
        
        Cache::remember($ipKey, $ttlHours * 3600, fn () => 0);
        Cache::increment($ipKey);
    }
}