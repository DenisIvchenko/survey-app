<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно массово присваивать
     * answer_id — это индекс в массиве options вопроса, НЕ внешняя ссылка!
     */
    protected $fillable = [
        'question_id',
        'answer_id',      // Индекс варианта в options (JSON), nullable
        'text_value',     // Для текстовых ответов и путей к файлам
        'user_id',        // Авторизованный пользователь
        'voter_ip',       // IP-адрес респондента
    ];

    /**
     * Атрибуты, которые нужно приводить к типам
     */
    protected $casts = [
        'question_id' => 'integer',
        'answer_id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * Связь с пользователем (авторизованный респондент)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь с вопросом
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * ВАЖНО: answer_id — это индекс в массиве options, а не FK к таблице answers
     * Поэтому связь с Answer::class НЕ нужна и может вызывать ошибки, если таблицы answers нет
     */

    /**
     * Определяет, является ли ответ текстовым
     */
    public function isTextAnswer(): bool
    {
        return !empty($this->text_value) && empty($this->answer_id);
    }

    /**
     * Получает значение ответа (универсальный геттер)
     *  Для медиа/радио/чекбоксов: возвращает текст варианта из options вопроса
     */
    public function getAnswerValueAttribute()
    {
        // Если есть текстовое значение — возвращаем его
        if (!empty($this->text_value)) {
            return $this->text_value;
        }
        
        // Если есть answer_id — пытаемся получить текст из options вопроса
        if ($this->answer_id !== null && $this->question) {
            $options = $this->question->options ?? [];
            if (is_array($options) && isset($options[$this->answer_id])) {
                $opt = $options[$this->answer_id];
                // Для медиа: возвращаем имя, для обычных: сам текст
                return is_array($opt) ? ($opt['name'] ?? $opt) : $opt;
            }
            return 'Вариант #' . ($this->answer_id + 1);
        }
        
        return '—';
    }
}