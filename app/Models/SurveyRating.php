<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RuntimeException;

class SurveyRating extends Model
{
    public const CREATED_AT = null;

    public const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'survey_id';

    public $incrementing = false;

    protected $guarded = ['*'];

    protected $casts = [
        'survey_id' => 'integer',
        'avg_rating' => 'decimal:2',
        'total_reviews' => 'integer',
        'updated_at' => 'datetime',
    ];

    private static bool $allowEventWrites = false;

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Poll::class, 'survey_id');
    }

    /**
     * Recalculate aggregate rating for a survey. Called exclusively from Review model events.
     */
    public static function syncFromReviewEvents(int $surveyId): void
    {
        $stats = Review::query()
            ->approved()
            ->where('survey_id', $surveyId)
            ->selectRaw('COALESCE(AVG(rating), 0) as avg_rating, COUNT(*) as total_reviews')
            ->first();

        self::$allowEventWrites = true;

        try {
            self::query()->updateOrCreate(
                ['survey_id' => $surveyId],
                [
                    'avg_rating' => round((float) $stats->avg_rating, 2),
                    'total_reviews' => (int) $stats->total_reviews,
                    'updated_at' => now(),
                ]
            );
        } finally {
            self::$allowEventWrites = false;
        }
    }

    public function save(array $options = []): bool
    {
        if (! self::$allowEventWrites) {
            throw new RuntimeException('SurveyRating can only be updated via Review model events.');
        }

        return parent::save($options);
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        if (! self::$allowEventWrites) {
            throw new RuntimeException('SurveyRating can only be updated via Review model events.');
        }

        return parent::update($attributes, $options);
    }

    public function delete(): bool|null
    {
        throw new RuntimeException('SurveyRating records cannot be deleted directly.');
    }
}
