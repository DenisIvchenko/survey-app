<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ReviewVoteCast;
use App\Models\ReviewVote;
use Illuminate\Support\Facades\DB;

class UpdateUserReputation
{
    private const USEFUL_DELTA = 5;

    private const USELESS_DELTA = -3;

    public function handle(ReviewVoteCast $event): void
    {
        $reviewAuthorId = $event->review->user_id;

        if ($reviewAuthorId === null) {
            return;
        }

        $delta = $event->voteType === ReviewVote::VOTE_USEFUL
            ? self::USEFUL_DELTA
            : self::USELESS_DELTA;

        DB::transaction(function () use ($reviewAuthorId, $delta): void {
            DB::update(
                'UPDATE users SET reputation = GREATEST(0, reputation + ?) WHERE id = ?',
                [$delta, $reviewAuthorId]
            );
        });
    }
}
