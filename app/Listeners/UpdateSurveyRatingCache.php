<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ReviewSubmitted;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class UpdateSurveyRatingCache
{
    public function handle(ReviewSubmitted $event): void
    {
        $review = $event->review->refresh();

        if ($review->status !== Review::STATUS_APPROVED) {
            return;
        }

        self::recalculate($review->survey_id);
    }

    public static function recalculate(int $surveyId): void
    {
        DB::transaction(function () use ($surveyId): void {
            $baseQuery = static fn () => DB::table('reviews')
                ->where('survey_id', $surveyId)
                ->where('status', Review::STATUS_APPROVED)
                ->whereNull('deleted_at');

            $avgRating = (float) ($baseQuery()->avg('rating') ?? 0);
            $totalReviews = (int) $baseQuery()->count();

            DB::table('survey_ratings')->updateOrInsert(
                ['survey_id' => $surveyId],
                [
                    'avg_rating' => round($avgRating, 2),
                    'total_reviews' => $totalReviews,
                    'updated_at' => now(),
                ]
            );
        });
    }
}
