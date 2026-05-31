<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Review;
use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        return ! Review::query()
            ->where('user_id', auth()->id())
            ->where('survey_id', $this->input('survey_id'))
            ->exists();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'min:15', 'max:1500'],
            'survey_id' => ['required', 'integer', 'exists:polls,id'],
        ];
    }
}
