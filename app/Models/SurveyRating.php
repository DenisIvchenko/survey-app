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

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Poll::class, 'survey_id');
    }

    public function save(array $options = []): bool
    {
        throw new RuntimeException('SurveyRating can only be updated via ReviewSubmitted event listeners.');
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new RuntimeException('SurveyRating can only be updated via ReviewSubmitted event listeners.');
    }

    public function delete(): bool|null
    {
        throw new RuntimeException('SurveyRating records cannot be deleted directly.');
    }
}
