<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Poll extends Model
{
    use HasFactory;

    // 🔹 Поля, которые можно массово присваивать
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'is_active',
        'is_completed',  // ← ДОБАВИТЬ ЭТО
    ];

    // 🔹 Приведение типов
    protected $casts = [
        'is_active' => 'boolean',
        'is_completed' => 'boolean',  // ← ДОБАВИТЬ ЭТО
    ];

    // 🔹 Связь с пользователем
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 🔹 Связь с вопросами
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('sort_order');
    }

    public function surveyRating(): HasOne
    {
        return $this->hasOne(SurveyRating::class, 'survey_id', 'id');
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'survey_id', 'id')
            ->approved()
            ->with('user:id,name,reputation');
    }

    // 🔹 Вспомогательные методы для статусов (опционально, но удобно)
    
    /**
     * Получает текстовую метку статуса
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->is_completed) return 'Завершён';
        return $this->is_active ? 'Активный' : 'Черновик';
    }

    /**
     * Проверяет, принимает ли опрос ответы
     */
    public function acceptsAnswers(): bool
    {
        return $this->is_active && !$this->is_completed;
    }
}