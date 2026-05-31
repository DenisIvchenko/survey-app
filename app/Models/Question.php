<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'text',
        'type',
        'options',      // JSON с вариантами ответов
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    /**
     * Связь с опросом
     */
    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    /**
     *  Связь с ответами (голосами) — НОВАЯ МЕТОД
     * Один вопрос может иметь много ответов (votes)
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}