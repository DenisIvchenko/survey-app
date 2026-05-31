<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\Question;
use App\Models\Review;
use App\Models\Vote;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PollController extends Controller
{
    //  Список опросов пользователя
    public function index(): View
    {
        $polls = Auth::user()
            ->polls()
            ->with('surveyRating')
            ->withCount('questions')
            ->latest()
            ->paginate(10);

        return view('polls.index', compact('polls'));
    }

    //  Форма создания
    public function create()
    {
        return view('polls.create');
    }

    //  Сохранение нового опроса
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
            'is_test' => 'nullable|boolean',  // ← Добавьте это
        ]);

        $validated['is_active'] = $validated['is_active'] ?? false;
        $validated['is_test'] = $validated['is_test'] ?? false;  // ← Добавьте это
        
        $poll = Auth::user()->polls()->create($validated);

        return redirect()->route('polls.build', $poll)
            ->with('success', 'Опрос создан! Теперь добавьте вопросы.');
    }

    //  Конструктор опроса
    public function build(Poll $poll)
    {
        if ($poll->user_id !== Auth::id()) {
            abort(403);
        }
        
        $poll->load('questions');
        
        return view('polls.build', compact('poll'));
    }

    //  Просмотр опроса (админка)
    public function show(Poll $poll)
    {
        if ($poll->user_id !== Auth::id()) {
            abort(403);
        }
        return view('polls.show', compact('poll'));
    }

    //  Форма редактирования метаданных
    public function edit(Poll $poll)
    {
        if ($poll->user_id !== Auth::id()) {
            abort(403);
        }
        return view('polls.edit', compact('poll'));
    }

    //  Обновление метаданных опроса
    public function update(Request $request, Poll $poll)
    {
        if ($poll->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $validated['is_active'] ?? false;
        $poll->update($validated);

        return redirect()->route('polls.index')
            ->with('success', 'Опрос обновлён!');
    }

    //  Удаление опроса
    public function destroy(Poll $poll)
    {
        if ($poll->user_id !== Auth::id()) {
            abort(403);
        }

        $poll->delete();
        return redirect()->route('polls.index')
            ->with('success', 'Опрос удалён!');
    }

    //  Страница прохождения опроса
    public function take(Poll $poll)
    {
    // Если НЕ активен ИЛИ Завершён — блокируем
        if (!$poll->is_active || $poll->is_completed) {
            abort(404, 'Опрос не активен или не найден');
        }
        
        //  Проверка: не проходил ли пользователь этот опрос ранее
        if (Auth::check()) {
            $hasVoted = Vote::where('user_id', Auth::id())
                ->whereHas('question', function($q) use ($poll) {
                    $q->where('poll_id', $poll->id);
                })->exists();
            
            if ($hasVoted) {
                return redirect()->route('polls.thanks', $poll)
                    ->with('info', 'Вы уже прошли этот опрос.');
            }
        }
        
        $poll->load('questions');
        
        return view('polls.take', compact('poll'));
    }

    //  Обработка отправки ответов на опрос
    public function submit(Request $request, Poll $poll)
    {
        // 1. Проверка активности опроса
        // 🔹 Блокируем, если опрос не активен ИЛИ завершён
        if (!$poll->is_active || $poll->is_completed) {
            abort(404, 'Опрос не принимает ответы');
        }

        // 2. Проверка авторизации (критично!)
        if (!Auth::check()) {
            \Log::error('Poll submit: user not authenticated', ['poll_id' => $poll->id]);
            return redirect()->route('login')->with('error', 'Необходимо авторизоваться');
        }

        // 3. Проверка: не проходил ли пользователь этот опрос ранее
        $hasVoted = Vote::where('user_id', Auth::id())
            ->whereHas('question', function($q) use ($poll) {
                $q->where('poll_id', $poll->id);
            })->exists();
        
        if ($hasVoted) {
            \Log::info('Poll submit: user already voted', ['user_id' => Auth::id(), 'poll_id' => $poll->id]);
            return redirect()->route('polls.thanks', $poll)
                ->with('info', 'Вы уже прошли этот опрос.');
        }

        // 4. Валидация
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable',
        ], [
            'answers.required' => 'Необходимо ответить на вопросы',
        ]);

        try {
            \Log::info('Poll submit started', [
                'user_id' => Auth::id(),
                'poll_id' => $poll->id,
                'answers_count' => count($request->answers ?? [])
            ]);

            DB::transaction(function () use ($request, $poll) {
                foreach ($request->answers as $questionId => $answer) {
                    // Проверка: вопрос принадлежит этому опросу
                    $question = $poll->questions()->findOrFail($questionId);
                    
                    \Log::debug('Processing vote', [
                        'question_id' => $questionId,
                        'type' => $question->type,
                        'answer' => $answer
                    ]);

                    //  Текстовые типы
                    if ($this->isTextQuestion($question->type)) {
                        Vote::create([
                            'question_id' => $questionId,
                            'text_value' => $answer,
                            'user_id' => Auth::id(),
                            'voter_ip' => $request->ip(),
                        ]);
                    }
                    
                    //  Загрузка файлов
                    elseif ($question->type === 'file_upload' && $request->hasFile("answers.{$questionId}")) {
                        foreach ($request->file("answers.{$questionId}") as $file) {
                            if ($file->isValid()) {
                                $path = $file->store("polls/{$poll->id}/answers", 'public');
                                Vote::create([
                                    'question_id' => $questionId,
                                    'text_value' => $path,
                                    'user_id' => Auth::id(),
                                    'voter_ip' => $request->ip(),
                                ]);
                            }
                        }
                    }
                    
                    //  Загрузка изображений
                    elseif ($question->type === 'image_upload' && $request->hasFile("answers.{$questionId}")) {
                        foreach ($request->file("answers.{$questionId}") as $file) {
                            if ($file->isValid() && $file->getImageSize()) {
                                $path = $file->store("polls/{$poll->id}/answers", 'public');
                                Vote::create([
                                    'question_id' => $questionId,
                                    'text_value' => $path,
                                    'user_id' => Auth::id(),
                                    'voter_ip' => $request->ip(),
                                ]);
                            }
                        }
                    }
                    
                    //  Медиа (выбор из вариантов)
                    elseif ($question->type === 'media') {
                        foreach ((array)$answer as $answerIndex) {
                            if ($answerIndex !== null && $answerIndex !== '' && is_numeric($answerIndex)) {
                                Vote::create([
                                    'question_id' => $questionId,
                                    'answer_id' => (int) $answerIndex,
                                    'user_id' => Auth::id(),
                                    'voter_ip' => $request->ip(),
                                ]);
                            }
                        }
                    }
                    
                    //  Множественный выбор
                    elseif ($question->type === 'checkbox' && is_array($answer)) {
                        foreach ($answer as $answerIndex) {
                            if (is_numeric($answerIndex)) {
                                Vote::create([
                                    'question_id' => $questionId,
                                    'answer_id' => (int) $answerIndex,
                                    'user_id' => Auth::id(),
                                    'voter_ip' => $request->ip(),
                                ]);
                            }
                        }
                    }
                    
                    //  Одиночный выбор (числовой)
                    elseif (in_array($question->type, ['radio', 'rating', 'slider', 'scale', 'distribution_scale', 'semantic_differential', 'yesno', 'dropdown', 'smiley_rating'])) {
                        if ($answer !== null && $answer !== '' && is_numeric($answer)) {
                            Vote::create([
                                'question_id' => $questionId,
                                'answer_id' => (int) $answer,
                                'user_id' => Auth::id(),
                                'voter_ip' => $request->ip(),
                            ]);
                        }
                    }
                    
                    //  Упрощённые типы
                    elseif (in_array($question->type, ['ranking', 'matrix', 'welcome', 'message', 'end', 'group'])) {
                        if (!empty($answer) && is_string($answer)) {
                            Vote::create([
                                'question_id' => $questionId,
                                'text_value' => $answer,
                                'user_id' => Auth::id(),
                                'voter_ip' => $request->ip(),
                            ]);
                        }
                    }
                    
                    //  Fallback
                    else {
                        if ($answer !== null && $answer !== '') {
                            Vote::create([
                                'question_id' => $questionId,
                                is_numeric($answer) ? 'answer_id' : 'text_value' => $answer,
                                'user_id' => Auth::id(),
                                'voter_ip' => $request->ip(),
                            ]);
                        }
                    }
                }
            });

            \Log::info('Poll submit success', ['user_id' => Auth::id(), 'poll_id' => $poll->id]);

            return redirect()->route('polls.thanks', $poll)
                ->with('success', 'Спасибо за участие в опросе!');
                
        } catch (\Exception $e) {
            \Log::error('Poll submit failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'poll_id' => $poll->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['submit' => 'Ошибка: ' . $e->getMessage()]);
        }
    }

        //  Отчет: анонимная статистика ответов (без персональных данных)
    public function report(Poll $poll)
    {
        // 1. Проверка прав
        if ($poll->user_id !== Auth::id()) {
            abort(403, 'Доступ запрещён');
        }

        // 2. Загружаем вопросы
        $poll->load(['questions' => fn($q) => $q->orderBy('sort_order')]);

        // 3. Ручной подсчет статистики для каждого вопроса
        foreach ($poll->questions as $question) {
            $votes = Vote::where('question_id', $question->id)->get();
            
            $stats = [];
            foreach ($votes as $vote) {
                // Определяем текст ответа
                $answerText = $vote->text_value;

                // Если ответ через answer_id (индекс в options)
                if (empty($answerText) && $vote->answer_id !== null) {
                    $options = is_array($question->options) ? array_values($question->options) : [];
                    $answerText = $options[$vote->answer_id] ?? 'Вариант #' . ($vote->answer_id + 1);
                }

                // Фолбэк, если всё равно пусто
                $answerText = $answerText ?: 'Без ответа';

                // Считаем вхождения
                $stats[$answerText] = ($stats[$answerText] ?? 0) + 1;
            }

            // Присваиваем динамические свойства вопросу
            $question->total_votes = array_sum($stats);
            $question->stats = collect($stats)
                ->map(fn($count, $text) => ['text' => $text, 'count' => $count])
                ->sortByDesc('count')
                ->values();
        }

        return view('polls.report', compact('poll'));
    }

        // Страница "Все опросы" (свои + чужие)
    public function public()
    {
        $userId = Auth::id();
        
        // Загружаем ВСЕ активные опросы с вопросами и данными автора
        $polls = Poll::where('is_active', true)
            ->where('is_completed', false)  // 🔹 Исключаем завершённые опросы
            ->with(['user:id,name,profile_photo_path'])
            ->withCount('questions')
            ->latest()
            ->paginate(12);
        
        // Добавляем флаг is_owner
        $polls->getCollection()->transform(function ($poll) use ($userId) {
            $poll->is_owner = ($poll->user_id == $userId);
            return $poll;
        });

        return view('polls.public', compact('polls'));
    }

        // 🔹 Изменение статуса опроса (AJAX)
        public function updateStatus(Request $request, Poll $poll)
    {
        if ($poll->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:active,draft,completed', // Принимаем строку
        ]);

        $status = $validated['status'];

        // Логика переключения
        if ($status === 'active') {
            $poll->is_active = true;
            $poll->is_completed = false;
            $label = 'Активный';
            $badgeClass = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400';
        } elseif ($status === 'completed') {
            $poll->is_active = true; // Остается активным для истории
            $poll->is_completed = true; // Но помечается как завершенный
            $label = 'Завершён';
            $badgeClass = 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400';
        } else { // draft
            $poll->is_active = false;
            $poll->is_completed = false;
            $label = 'Черновик';
            $badgeClass = 'bg-gray-100 text-gray-700 dark:bg-gray-500/20 dark:text-gray-400';
        }

        $poll->save();

        return response()->json([
            'success' => true,
            'status' => $label,
            'badge_class' => $badgeClass,
            'is_completed' => $poll->is_completed,
        ]);
    }

    //  Страница благодарности
    public function thanks(Poll $poll): View
    {
        $poll->load('surveyRating');

        $canReview = ! Review::query()
            ->where('user_id', Auth::id())
            ->where('survey_id', $poll->id)
            ->exists();

        $averageRating = $poll->surveyRating?->avg_rating ?? 0;

        return view('polls.thanks', compact('poll', 'canReview', 'averageRating'));
    }

    public function showReviews(Poll $poll): View
    {
        $poll->load('surveyRating');

        $averageRating = $poll->surveyRating?->avg_rating ?? 0;
        $reviews = $poll->approvedReviews()->latest()->paginate(15);

        return view('polls.reviews', compact('poll', 'averageRating', 'reviews'));
    }

    public function reviewsFragment(Poll $poll): View
    {
        $reviews = $poll->approvedReviews()->latest()->paginate(15);

        return view('polls.partials.reviews_list', compact('poll', 'reviews'));
    }

    /**
     *   Определяет, является ли тип вопроса текстовым
     */
    private function isTextQuestion(string $type): bool
    {
        return in_array($type, ['text', 'number', 'contact_email', 'contact_phone', 'contact_name', 'contact_date']);
    }

    /**
     *  Сохранение структуры вопросов из конструктора
     *  АВТО-ПУБЛИКАЦИЯ: если опрос в черновике — публикуем его после сохранения вопросов
     */
    public function updateQuestions(Request $request, Poll $poll)
    {
        if ($poll->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'questions' => 'required|array',
            'questions.*.type' => 'required|string',
            'questions.*.text' => 'nullable|string',
            'questions.*.is_required' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($request, $poll) {
            // Полная замена вопросов (каскадное удаление старых)
            $poll->questions()->delete();

            foreach ($request->questions as $index => $q) {
                $rawOptions = $q['options'] ?? [];
                $cleanOptions = [];

                foreach ($rawOptions as $opt) {
                    // Обработка base64-изображений из конструктора
                    if (is_array($opt) && !empty($opt['preview']) && str_starts_with($opt['preview'], 'data:image/')) {
                        $path = $this->saveImageToDisk($opt['preview'], $poll->id, $opt['name'] ?? 'image.jpg');
                        $cleanOptions[] = [
                            'name' => $opt['name'] ?? basename($path),
                            'path' => $path,
                            'size' => $opt['size'] ?? null,
                        ];
                    } elseif (is_array($opt) && !empty($opt['path'])) {
                        // Уже сохранённые изображения
                        $cleanOptions[] = $opt;
                    } else {
                        // Обычные текстовые варианты
                        $cleanOptions[] = $opt;
                    }
                }

                //  Сбрасываем ключи на 0,1,2... чтобы JSON стал [...], а не {"0":...}
                $cleanOptions = array_values($cleanOptions);
                
                // Извлекаем метаданные вопроса
                $meta = collect($q)->only(['description', 'placeholder', 'min', 'max', 'left', 'right', 'left_label', 'right_label'])->filter()->toArray();

                $poll->questions()->create([
                    'text'        => $q['text'] ?? '',
                    'type'        => $q['type'],
                    'options'     => array_merge($cleanOptions, $meta),
                    'is_required' => (bool)($q['is_required'] ?? false),
                    'sort_order'  => $index,
                ]);
            }
        });

        //  АВТО-ПУБЛИКАЦИЯ: если опрос ещё в черновике — публикуем его
        if (!$poll->is_active) {
            $poll->update(['is_active' => true]);
        }

        return response()->json(['success' => true]);
    }

    /**
     *   Декодирует base64 и сохраняет изображение на диск
     */
    private function saveImageToDisk(string $base64, int $pollId, string $filename): string
    {
        // Удаляем префикс data:image/...;base64,
        $data = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
        $data = base64_decode($data);
        
        if (!$data) {
            throw new \InvalidArgumentException('Invalid base64 image data');
        }

        // Определяем расширение и безопасное имя файла
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)) ?: 'jpg';
        $safeName = pathinfo($filename, PATHINFO_FILENAME);
        $safeName = preg_replace('/[^a-zA-Z0-9_-]/u', '_', $safeName);
        
        // Генерируем уникальный путь: polls/{id}/questions/{timestamp}_{hash}.{ext}
        $path = "polls/{$pollId}/questions/" . now()->format('Ymd_His') . '_' . 
                substr(md5($safeName . microtime(true)), 0, 8) . '.' . $ext;

        Storage::disk('public')->put($path, $data);
        return $path;
    }
}