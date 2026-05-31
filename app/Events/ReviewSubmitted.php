<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Review;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReviewSubmitted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Review $review,
    ) {
    }
}
