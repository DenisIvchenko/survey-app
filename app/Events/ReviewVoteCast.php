<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReviewVoteCast
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Review $review,
        public readonly User $user,
        public readonly string $voteType,
    ) {
    }
}
