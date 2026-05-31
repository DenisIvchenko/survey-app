<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;

// Главная → вход
Route::get('/', function () {
    return redirect()->route('login');
});

// Дашборд → список опросов
Route::get('/dashboard', function () {
    return redirect()->route('polls.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// 🔹 Все маршруты, требующие авторизации — ОДНА ГРУППА
Route::middleware(['auth'])->group(function () {
    
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/vote', [ReviewController::class, 'vote'])->name('reviews.vote');
    Route::patch('/reviews/{review}/moderate', [ReviewController::class, 'moderate'])->name('reviews.moderate');
    
    // Профиль пользователя
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // 🔹 1. СПЕЦИФИЧНЫЕ МАРШРУТЫ (должны быть ПЕРВЫМИ!)
    // Публичные опросы — ДО resource, чтобы не перехватывался как {poll}
    Route::get('/polls/public', [PollController::class, 'public'])->name('polls.public');

    // 🔹 Изменение статуса опроса (AJAX)
    Route::patch('/polls/{poll}/status', [PollController::class, 'updateStatus'])->name('polls.status');
    
    // Конструктор опроса
    Route::get('/polls/{poll}/build', [PollController::class, 'build'])->name('polls.build');
    Route::post('/polls/{poll}/questions', [PollController::class, 'updateQuestions'])->name('polls.questions.store');
    
    // Прохождение опроса
    Route::get('/polls/{poll}/take', [PollController::class, 'take'])->name('polls.take');
    Route::post('/polls/{poll}/submit', [PollController::class, 'submit'])->name('polls.submit');
    Route::get('/polls/{poll}/thanks', [PollController::class, 'thanks'])->name('polls.thanks');
    
    // Отчет
    Route::get('/polls/{poll}/report', [PollController::class, 'report'])->name('polls.report');
    
    // 🔹 2. РЕСУРСНЫЕ МАРШРУТЫ (идут ПОСЛЕ специфичных)
    Route::resource('polls', PollController::class);
});

// Аутентификация Breeze
require __DIR__.'/auth.php';