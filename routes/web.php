<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PollController;
use App\Models\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Главная → вход
Route::get('/', function () {
    return redirect()->route('login');
});

// Дашборд → список опросов
Route::get('/dashboard', function () {
    return redirect()->route('polls.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Профиль
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Опросы (полный CRUD + конструктор)
Route::middleware(['auth'])->group(function () {
    Route::resource('polls', PollController::class);
    
    // 🔥 Страница конструктора опроса
    Route::get('/polls/{poll}/build', [PollController::class, 'build'])->name('polls.build');
    
    // 🔥 API для сохранения вопросов (внутри web.php для простоты)
    Route::post('/polls/{poll}/questions', function (Request $request, Poll $poll) {
        // Проверка прав
        if ($poll->user_id !== Auth::id()) {
            abort(403);
        }

        // Валидация
        $request->validate([
            'questions' => 'required|array',
            'questions.*.type' => 'required|in:text,textarea,radio,checkbox,dropdown,rating,scale',
            'questions.*.text' => 'required|string|max:500',
            'questions.*.options' => 'nullable|array',
            'questions.*.is_required' => 'boolean',
        ]);

        // Удаляем старые вопросы (для простоты — полная замена)
        $poll->questions()->delete();

        // Сохраняем новые
        foreach ($request->questions as $index => $q) {
            $poll->questions()->create([
                'text' => $q['text'],
                'type' => $q['type'],
                'options' => $q['options'] ?? null,
                'is_required' => $q['is_required'] ?? false,
                'sort_order' => $index,
            ]);
        }

        return response()->json(['success' => true]);
    })->name('polls.questions.store');
});

// Аутентификация Breeze
require __DIR__.'/auth.php';