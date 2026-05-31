<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'survey_id',
        'rating',
        'comment',
        'status',
        'moderation_score',
        'auto_moderation_reason',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'survey_id' => 'integer',
        'rating' => 'integer',
        'moderation_score' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        $syncSurveyRating = static function (Review $review): void {
            SurveyRating::syncFromReviewEvents($review->survey_id);
        };

        static::saved($syncSurveyRating);
        static::deleted($syncSurveyRating);
        static::restored($syncSurveyRating);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Poll::class, 'survey_id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ReviewVote::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}
