<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ReviewSubmitted;
use App\Services\AutoModerationService;
use Illuminate\Support\Facades\DB;

class ApplyAutoModeration
{
    public function __construct(
        private readonly AutoModerationService $moderationService,
    ) {
    }

    public function handle(ReviewSubmitted $event): void
    {
        $result = $this->moderationService->check($event->review->comment ?? '');

        DB::transaction(function () use ($event, $result): void {
            $event->review->update([
                'status' => $result['status'],
                'moderation_score' => $result['score'],
                'auto_moderation_reason' => $result['reason'],
            ]);
        });

        $event->review->refresh();
    }
}
