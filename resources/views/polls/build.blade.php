<!DOCTYPE html>
<html lang="ru" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Конструктор опроса — {{ $poll->title }}</title>
    
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
        .builder-bg { background-color: #f8f9fc; }
        .dark .builder-bg { background-color: #0f1115; }
        .question-card { background-color: #ffffff; border: 1px solid #e5e7eb; }
        .dark .question-card { background-color: #1a1a20; border-color: rgba(255,255,255,0.1); }
        .builder-input { background-color: #f9fafb; border-color: #e5e7eb; }
        .dark .builder-input { background-color: #121216; border-color: rgba(255,255,255,0.1); }
    </style>
</head>
<body class="builder-bg text-gray-900 dark:text-gray-100 font-sans antialiased">

    <div x-data="pollBuilder({{ $poll->id }}, @json($poll->questions))" class="min-h-screen flex flex-col">
        
        {{-- ================= ВЕРХНЯЯ ПАНЕЛЬ (webask.io style) ================= --}}
        <header class="bg-white dark:bg-[#121216] border-b border-gray-200 dark:border-white/5 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    {{-- Левая часть --}}
                    <div class="flex items-center gap-4">
                        <a href="{{ route('polls.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $poll->title }}</h1>
                            <span class="text-xs text-gray-400 bg-gray-100 dark:bg-white/5 px-2 py-0.5 rounded-full">Черновик</span>
                        </div>
                    </div>
                    
                    {{-- Центральная часть: Вкладки --}}
                    <nav class="hidden md:flex items-center gap-1 bg-gray-100 dark:bg-white/5 rounded-lg p-1">
                        <button class="px-4 py-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 bg-white dark:bg-white/10 rounded-md shadow-sm">
                            Конструктор
                        </button>
                        <button class="px-4 py-1.5 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 rounded-md transition-colors">
                            Дизайн
                        </button>
                        <button class="px-4 py-1.5 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 rounded-md transition-colors">
                            Настройки
                        </button>
                    </nav>
                    
                    {{-- Правая часть --}}
                    <div class="flex items-center gap-3">
                        <button @click="savePoll()" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-medium transition-colors shadow-lg shadow-blue-500/20">
                            <svg x-show="saving" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="saving ? 'Сохранение...' : 'Опубликовать'"></span>
                        </button>
                        
                        {{-- Переключатель темы --}}
                        <button @click="toggleTheme()" class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">
                            <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                            <svg x-show="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </button>
                        
                        {{-- Профиль --}}
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- ================= ОСНОВНАЯ ОБЛАСТЬ ================= --}}
        <main class="flex-1 max-w-3xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            {{-- Список вопросов --}}
            <div class="space-y-4">
                <template x-for="(question, index) in questions" :key="question.id || index">
                    <div class="question-card rounded-xl p-6 relative group hover:shadow-md transition-shadow">
                        
                        <div class="flex items-start gap-4">
                            {{-- Номер --}}
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 flex items-center justify-center font-medium text-sm">
                                <span x-text="index + 1"></span>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                {{-- Тип --}}
                                <div class="text-xs text-gray-400 dark:text-gray-500 mb-2 uppercase tracking-wider" x-text="getQuestionTypeLabel(question.type)"></div>
                                
                                {{-- Текст вопроса --}}
                                <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1" 
                                    x-text="question.text || 'Введите заголовок...'"></h3>
                                
                                {{-- Превью вариантов --}}
                                <template x-if="['radio', 'checkbox', 'dropdown'].includes(question.type) && question.options && question.options.length > 0">
                                    <div class="space-y-1.5 mt-4 pl-1">
                                        <template x-for="(option, optIndex) in question.options.filter(o => o.trim()).slice(0, 3)" :key="optIndex">
                                            <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                                <template x-if="question.type === 'radio'">
                                                    <div class="w-4 h-4 rounded-full border-2 border-gray-300 dark:border-gray-600"></div>
                                                </template>
                                                <template x-if="question.type === 'checkbox'">
                                                    <div class="w-4 h-4 rounded border-2 border-gray-300 dark:border-gray-600"></div>
                                                </template>
                                                <template x-if="question.type === 'dropdown'">
                                                    <span class="text-gray-400 text-xs" x-text="optIndex + 1 + '.'"></span>
                                                </template>
                                                <span x-text="option"></span>
                                            </div>
                                        </template>
                                        <div x-show="question.options.filter(o => o.trim()).length > 3" class="text-xs text-gray-400 pl-7">
                                            и ещё <span x-text="question.options.filter(o => o.trim()).length - 3"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            
                            {{-- Кнопки управления (появляются при наведении) --}}
                            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity absolute top-4 right-4">
                                <button @click="editingQuestion = index; showQuestionModal = true" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="moveQuestion(index, -1)" x-show="index > 0" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                </button>
                                <button @click="moveQuestion(index, 1)" x-show="index < questions.length - 1" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <button @click="removeQuestion(index)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- Пустое состояние --}}
                <div x-show="questions.length === 0" class="text-center py-16">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/5 text-gray-400 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Начните добавлять вопросы</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 text-sm">Нажмите на плюс ниже или выберите тип вопроса</p>
                </div>
            </div>

            {{-- Кнопка добавления вопроса --}}
            <div class="mt-8 flex justify-center">
                <button @click="showTypeSelector = true" 
                        class="w-12 h-12 rounded-full bg-white dark:bg-[#1a1a20] border-2 border-gray-200 dark:border-white/10 text-gray-400 hover:text-blue-600 hover:border-blue-500 dark:hover:text-blue-400 dark:hover:border-blue-500 shadow-sm hover:shadow-md transition-all flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </button>
            </div>
        </main>

        {{-- ================= МОДАЛЬНОЕ ОКНО: ВЫБОР ТИПА ================= --}}
        <div x-show="showTypeSelector" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @click.self="showTypeSelector = false">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
                
                <div class="relative bg-white dark:bg-[#1a1a20] rounded-2xl shadow-2xl max-w-lg w-full p-6 z-10">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Добавить вопрос</h3>
                        <button @click="showTypeSelector = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="addQuestion('text'); showTypeSelector = false" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 dark:border-white/10 hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-all text-left group">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white text-sm">Текст</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Короткий ответ</div>
                            </div>
                        </button>
                        
                        <button @click="addQuestion('textarea'); showTypeSelector = false" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 dark:border-white/10 hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-all text-left group">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white text-sm">Абзац</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Длинный текст</div>
                            </div>
                        </button>
                        
                        <button @click="addQuestion('radio'); showTypeSelector = false" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 dark:border-white/10 hover:border-green-500 hover:bg-green-50 dark:hover:bg-green-500/10 transition-all text-left group">
                            <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white text-sm">Один вариант</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Выбор одного</div>
                            </div>
                        </button>
                        
                        <button @click="addQuestion('checkbox'); showTypeSelector = false" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 dark:border-white/10 hover:border-green-500 hover:bg-green-50 dark:hover:bg-green-500/10 transition-all text-left group">
                            <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white text-sm">Несколько</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Множественный</div>
                            </div>
                        </button>
                        
                        <button @click="addQuestion('rating'); showTypeSelector = false" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 dark:border-white/10 hover:border-purple-500 hover:bg-purple-50 dark:hover:bg-purple-500/10 transition-all text-left group">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white text-sm">Рейтинг</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Звёзды 1-5</div>
                            </div>
                        </button>
                        
                        <button @click="addQuestion('scale'); showTypeSelector = false" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 dark:border-white/10 hover:border-purple-500 hover:bg-purple-50 dark:hover:bg-purple-500/10 transition-all text-left group">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-500/20 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white text-sm">Шкала</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">1 до 10</div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= МОДАЛЬНОЕ ОКНО: РЕДАКТИРОВАНИЕ ================= --}}
        <div x-show="showQuestionModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @click.self="showQuestionModal = false">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
                
                <div class="relative bg-white dark:bg-[#1a1a20] rounded-2xl shadow-2xl max-w-lg w-full p-6 z-10">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Редактирование вопроса</h3>
                        <button @click="showQuestionModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Текст вопроса</label>
                            <input type="text" 
                                   x-model="questions[editingQuestion].text"
                                   class="w-full builder-input border rounded-xl px-4 py-3 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500/50">
                        </div>
                        
                        {{-- Варианты ответов --}}
                        <div x-show="['radio', 'checkbox', 'dropdown'].includes(questions[editingQuestion].type)">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Варианты ответов</label>
                                <button @click="addOption(questions[editingQuestion])" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">+ Добавить</button>
                            </div>
                            <div class="space-y-2">
                                <template x-for="(option, optIndex) in questions[editingQuestion].options" :key="optIndex">
                                    <div class="flex gap-2">
                                        <input type="text" 
                                               x-model="questions[editingQuestion].options[optIndex]"
                                               class="flex-1 builder-input border rounded-xl px-4 py-2 text-sm">
                                        <button @click="questions[editingQuestion].options.splice(optIndex, 1)" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <button @click="showQuestionModal = false" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg">Отмена</button>
                        <button @click="saveQuestion(); showQuestionModal = false" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function pollBuilder(pollId, initialQuestions = []) {
            return {
                pollId: pollId,
                questions: initialQuestions.map(q => ({
                    ...q,
                    options: q.options ? (typeof q.options === 'string' ? JSON.parse(q.options) : q.options) : []
                })),
                showTypeSelector: false,
                showQuestionModal: false,
                editingQuestion: null,
                saving: false,
                isDark: localStorage.getItem('theme') === 'dark',
                
                toggleTheme() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                    if (this.isDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                },
                
                addQuestion(type) {
                    const question = {
                        type: type,
                        text: '',
                        options: ['radio', 'checkbox', 'dropdown'].includes(type) ? ['', ''] : [],
                        is_required: false
                    };
                    this.questions.push(question);
                    this.editingQuestion = this.questions.length - 1;
                    this.showQuestionModal = true;
                },
                
                addOption(question) {
                    if (!question.options) question.options = [];
                    question.options.push('');
                },
                
                moveQuestion(index, direction) {
                    const newIndex = index + direction;
                    if (newIndex < 0 || newIndex >= this.questions.length) return;
                    [this.questions[index], this.questions[newIndex]] = 
                    [this.questions[newIndex], this.questions[index]];
                },
                
                removeQuestion(index) {
                    if (confirm('Удалить этот вопрос?')) {
                        this.questions.splice(index, 1);
                    }
                },
                
                saveQuestion() {
                    if (!this.questions[this.editingQuestion].text.trim()) {
                        alert('Введите текст вопроса');
                        return;
                    }
                },
                
                getQuestionTypeLabel(type) {
                    const labels = {
                        'text': 'Текст',
                        'textarea': 'Абзац',
                        'radio': 'Один вариант',
                        'checkbox': 'Несколько вариантов',
                        'dropdown': 'Выпадающий список',
                        'rating': 'Звёздный рейтинг',
                        'scale': 'Шкала'
                    };
                    return labels[type] || type;
                },
                
                async savePoll() {
                    if (this.questions.length === 0) {
                        alert('Добавьте хотя бы один вопрос');
                        return;
                    }
                    
                    this.saving = true;
                    try {
                        const response = await fetch(`/polls/${this.pollId}/questions`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ questions: this.questions })
                        });
                        
                        if (response.ok) {
                            window.location.href = '{{ route("polls.index") }}';
                        } else {
                            alert('Ошибка при сохранении');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Ошибка соединения');
                    } finally {
                        this.saving = false;
                    }
                }
            }
        }
    </script>
</body>
</html>