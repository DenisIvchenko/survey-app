<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\ReviewSubmitted;
use App\Events\ReviewVoteCast;
use App\Http\Requests\StoreReviewRequest;
use App\Listeners\UpdateSurveyRatingCache;
use App\Models\Review;
use App\Models\ReviewVote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $this->authorize('store', [Review::class, (int) $request->validated('survey_id')]);

        $review = Review::create([
            'user_id' => $request->user()->id,
            'survey_id' => $request->validated('survey_id'),
            'rating' => $request->validated('rating'),
            'comment' => $request->validated('comment'),
        ]);

        event(new ReviewSubmitted($review));

        return back()->with('success', 'Your review has been submitted and is awaiting moderation.');
    }

    public function vote(Review $review, Request $request): JsonResponse
    {
        $this->authorize('vote', $review);

        $validated = $request->validate([
            'vote_type' => ['required', 'string', 'in:useful,useless'],
        ]);

        if ($request->user()->id === $review->user_id) {
            return response()->json([
                'message' => 'You cannot vote on your own review.',
            ], 403);
        }

        $vote = ReviewVote::firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'review_id' => $review->id,
            ],
            [
                'vote_type' => $validated['vote_type'],
            ]
        );

        if (! $vote->wasRecentlyCreated) {
            return response()->json([
                'message' => 'You have already voted on this review.',
                'vote_type' => $vote->vote_type,
            ], 409);
        }

        event(new ReviewVoteCast($review, $request->user(), $vote->vote_type));

        return response()->json([
            'message' => 'Vote recorded successfully.',
            'vote_type' => $vote->vote_type,
        ]);
    }

    public function moderate(Review $review, Request $request): JsonResponse
    {
        $this->authorize('moderate', $review);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,approved,rejected'],
            'auto_moderation_reason' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($review, $validated): void {
            $review->update([
                'status' => $validated['status'],
                'auto_moderation_reason' => $validated['auto_moderation_reason'] ?? $review->auto_moderation_reason,
            ]);
        });

        $review->refresh();
        UpdateSurveyRatingCache::recalculate($review->survey_id);

        return response()->json([
            'message' => 'Review moderation updated successfully.',
            'review' => [
                'id' => $review->id,
                'status' => $review->status,
                'auto_moderation_reason' => $review->auto_moderation_reason,
            ],
        ]);
    }
}
