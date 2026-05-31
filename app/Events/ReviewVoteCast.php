<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\ReviewVote;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReviewVoteCast
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly ReviewVote $vote,
    ) {
    }
}
