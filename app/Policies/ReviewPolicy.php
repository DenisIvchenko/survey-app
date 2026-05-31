<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Review;
use App\Models\ReviewVote;
use App\Models\User;

class ReviewPolicy
{
    public function store(User $user, int $surveyId): bool
    {
        return ! Review::query()
            ->where('user_id', $user->id)
            ->where('survey_id', $surveyId)
            ->exists();
    }

    public function vote(User $user, Review $review): bool
    {
        if ($user->id === $review->user_id) {
            return false;
        }

        return ! ReviewVote::query()
            ->where('user_id', $user->id)
            ->where('review_id', $review->id)
            ->exists();
    }

    public function moderate(User $user, Review $review): bool
    {
        return $user->isModerator();
    }

    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->user_id || $user->isModerator();
    }
}
