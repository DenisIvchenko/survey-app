<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\ReviewVoteCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewVote extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const VOTE_USEFUL = 'useful';

    public const VOTE_USELESS = 'useless';

    protected $fillable = [
        'user_id',
        'review_id',
        'vote_type',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'review_id' => 'integer',
    ];

    protected static function booted(): void
    {
        static::created(static function (ReviewVote $vote): void {
            ReviewVoteCast::dispatchSync($vote);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }
}
