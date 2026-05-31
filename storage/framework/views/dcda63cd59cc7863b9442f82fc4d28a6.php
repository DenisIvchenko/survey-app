<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Конструктор — <?php echo e($poll->title ?? 'Опрос'); ?></title>
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-[#0b0b0f] text-gray-900 dark:text-gray-100 font-sans antialiased transition-colors duration-200">

    <?php
        $defaultTestSettings = [
            'points_per_question' => 10,
            'time_limit' => null,
            'time_per_question' => null,
            'show_timer' => true,
            'grading_scale' => 'five_point',
            'passing_score' => 60,
            'grade_2_max' => 59,
            'grade_3_min' => 60, 'grade_3_max' => 74,
            'grade_4_min' => 75, 'grade_4_max' => 89,
            'grade_5_min' => 90,
            'shuffle_questions' => false,
            'show_correct_answers' => false,
            'one_attempt' => false
        ];
    ?>

    <script>
        window.__pollInit__ = {
            id: <?php echo e($poll->id ?? 'null'); ?>,
            questions: <?php echo json_encode($poll->questions ? $poll->questions->toArray() : [], 15, 512) ?>,
            redirectUrl: '<?php echo e(route('polls.index')); ?>',
            isTest: <?php echo json_encode($poll->is_test ?? false, 15, 512) ?>,
            testSettings: <?php echo json_encode($poll->test_settings ?? $defaultTestSettings, 15, 512) ?>
        };
    </script>

    <div x-data="pollBuilder(window.__pollInit__.id, window.__pollInit__.questions, window.__pollInit__.redirectUrl)"
         x-init="init()"
         x-cloak
         class="min-h-screen flex flex-col">

        <!-- 🔝 HEADER -->
        <header class="sticky top-0 z-50 bg-white/90 dark:bg-[#121216]/90 backdrop-blur-xl border-b border-gray-200 dark:border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-3">
                        <a href="<?php echo e(route('polls.index')); ?>" class="p-2 text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <h1 class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo e($poll->title ?? 'Опрос'); ?></h1>
                            <span x-show="isTest" class="text-[10px] uppercase tracking-wide font-bold text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-500/20 px-2.5 py-0.5 rounded-full">Тест</span>
                            <span x-show="!isTest" class="text-[10px] uppercase tracking-wide font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-white/5 px-2.5 py-0.5 rounded-full">Опрос</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <nav class="hidden md:flex items-center gap-1 p-1 bg-gray-100 dark:bg-white/5 rounded-xl">
                            <button @click="currentView = 'constructor'" :class="currentView === 'constructor' ? 'bg-white dark:bg-[#1a1a20] text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'" class="px-4 py-1.5 text-sm font-medium rounded-lg transition-all">Опрос</button>
                            <button @click="currentView = 'logicMap'" :class="currentView === 'logicMap' ? 'bg-white dark:bg-[#1a1a20] text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'" class="px-4 py-1.5 text-sm font-medium rounded-lg transition-all">Карта логики</button>
                            <button @click="currentView = 'settings'" :class="currentView === 'settings' ? 'bg-white dark:bg-[#1a1a20] text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'" class="px-4 py-1.5 text-sm font-medium rounded-lg transition-all">Настройки</button>
                            <button @click="currentView = 'preview'" :class="currentView === 'preview' ? 'bg-white dark:bg-[#1a1a20] text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'" class="px-4 py-1.5 text-sm font-medium rounded-lg transition-all">Просмотр</button>
                        </nav>
                        <div class="flex items-center gap-3">
                            <button class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-xl transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                                Поделиться
                            </button>
                            
                            <button @click="savePoll()" :disabled="saving || questions.length === 0" class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold bg-blue-600 hover:bg-blue-500 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-xl transition-colors shadow-lg shadow-blue-500/20">
                                <svg x-show="saving" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span x-text="saving ? 'Сохранение...' : 'Опубликовать'"></span>
                            </button>
                            
                            <button @click="toggleTheme()" class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-colors">
                                <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                                <svg x-show="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </button>
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold cursor-pointer"><?php echo e(substr(Auth::user()->name ?? 'U', 0, 1)); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- ⚙️ НАСТРОЙКИ ТЕСТА -->
        <div x-show="currentView === 'settings'" x-cloak class="flex-1 bg-gray-50 dark:bg-[#0b0b0f] overflow-y-auto">
            <div class="max-w-4xl mx-auto px-4 py-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Настройки теста</h2>
                
                <div class="space-y-6">
                    <!-- Переключатель режима теста -->
                    <div class="bg-white dark:bg-[#1a1a20] rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Режим тестирования</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Включите для активации системы баллов и оценивания</p>
                            </div>
                            <button @click="isTest = !isTest" 
                                    :class="isTest ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <span :class="isTest ? 'translate-x-6' : 'translate-x-1'" 
                                      class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                            </button>
                        </div>
                    </div>

                    <div x-show="isTest" x-transition class="space-y-6">
                        <!-- Баллы за вопрос -->
                        <div class="bg-white dark:bg-[#1a1a20] rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Система баллов</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Баллов за правильный ответ</label>
                                    <input type="number" x-model.number="testSettings.points_per_question" min="1" max="100" 
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 focus:outline-none focus:border-blue-400">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Баллы по умолчанию за каждый вопрос</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Проходной балл (%)</label>
                                    <input type="number" x-model.number="testSettings.passing_score" min="0" max="100"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 focus:outline-none focus:border-blue-400">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Минимальный процент для сдачи</p>
                                </div>
                            </div>
                        </div>

                        <!-- Ограничение по времени -->
                        <div class="bg-white dark:bg-[#1a1a20] rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ограничение по времени</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Минут на весь тест</label>
                                    <input type="number" x-model.number="testSettings.time_limit" min="1" max="180" 
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 focus:outline-none focus:border-blue-400">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Оставьте пустым для безлимита</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">На вопрос (сек)</label>
                                    <input type="number" x-model.number="testSettings.time_per_question" min="10" max="600" 
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 focus:outline-none focus:border-blue-400">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Время на один вопрос</p>
                                </div>
                                <div class="flex items-end">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" x-model="testSettings.show_timer" 
                                               class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Показывать таймер</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Система оценивания -->
                        <div class="bg-white dark:bg-[#1a1a20] rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Система оценивания</h3>
                            <div class="space-y-4">
                                <div class="flex gap-4">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" x-model="testSettings.grading_scale" value="five_point" 
                                               class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-gray-200 dark:border-white/10 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-500/10 transition-all">
                                            <div class="font-medium text-gray-900 dark:text-white mb-1">5-балльная</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">2 (неуд) - 5 (отл)</div>
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" x-model="testSettings.grading_scale" value="verbal" 
                                               class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-gray-200 dark:border-white/10 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-500/10 transition-all">
                                            <div class="font-medium text-gray-900 dark:text-white mb-1">Словесная</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Не зачёт / Зачёт</div>
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" x-model="testSettings.grading_scale" value="percent" 
                                               class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-gray-200 dark:border-white/10 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-500/10 transition-all">
                                            <div class="font-medium text-gray-900 dark:text-white mb-1">Процентная</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">0-100%</div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Настройка шкалы 2-5 -->
                                <div x-show="testSettings.grading_scale === 'five_point'" class="mt-4 p-4 bg-gray-50 dark:bg-white/5 rounded-xl space-y-3">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <div>
                                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">2 (неуд)</label>
                                            <input type="number" x-model.number="testSettings.grade_2_max" value="59" min="0" max="100"
                                                   class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-center text-sm">
                                            <p class="text-[10px] text-gray-400 mt-1">до %</p>
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">3 (удовл)</label>
                                            <input type="number" x-model.number="testSettings.grade_3_min" value="60" min="0" max="100"
                                                   class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-center text-sm">
                                            <input type="number" x-model.number="testSettings.grade_3_max" value="74" min="0" max="100"
                                                   class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-center text-sm mt-1">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">4 (хор)</label>
                                            <input type="number" x-model.number="testSettings.grade_4_min" value="75" min="0" max="100"
                                                   class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-center text-sm">
                                            <input type="number" x-model.number="testSettings.grade_4_max" value="89" min="0" max="100"
                                                   class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-center text-sm mt-1">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">5 (отл)</label>
                                            <input type="number" x-model.number="testSettings.grade_5_min" value="90" min="0" max="100"
                                                   class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-center text-sm">
                                            <p class="text-[10px] text-gray-400 mt-1">от %</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Дополнительные настройки -->
                        <div class="bg-white dark:bg-[#1a1a20] rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Дополнительно</h3>
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" x-model="testSettings.shuffle_questions" 
                                           class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Перемешивать вопросы</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" x-model="testSettings.show_correct_answers" 
                                           class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Показывать правильные ответы после теста</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" x-model="testSettings.one_attempt" 
                                           class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Только одна попытка</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-4">
                    <button @click="currentView = 'constructor'" class="px-6 py-3 border border-gray-200 dark:border-white/10 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        Отмена
                    </button>
                    <button @click="saveTestSettings()" :disabled="saving" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-xl font-medium transition-colors shadow-lg shadow-blue-500/20 flex items-center gap-2">
                        <svg x-show="saving" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="saving ? 'Сохранение...' : 'Сохранить настройки'"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- 🔑 КАРТА ЛОГИКИ -->
        <div x-show="currentView === 'logicMap'" x-cloak class="flex-1 bg-gray-50 dark:bg-[#0b0b0f] overflow-hidden">
            <div class="flex flex-col h-full">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-white/10 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Структура опроса</h2>
                </div>
                <div class="flex-1 overflow-x-auto overflow-y-hidden p-8">
                    <div class="flex items-center gap-5 min-w-max pb-4">
                        <div class="flex items-center gap-3 px-5 py-3 bg-white dark:bg-[#1a1a20] rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm">
                            <div class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
                            <span class="text-sm font-medium text-gray-800 dark:text-gray-200">Начало</span>
                        </div>
                        <svg class="w-10 h-10 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        
                        <template x-for="(q, i) in questions" :key="q.id">
                            <div class="flex items-center gap-5">
                                <div class="relative group" draggable="true" @dragstart="dragStart($event, i)" @dragend="dragEnd($event)" @dragover.prevent @dragenter.prevent @drop="dragDrop($event, i)" class="w-60 p-1 rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/10 hover:border-blue-400 dark:hover:border-blue-500/50 transition-colors cursor-grab active:cursor-grabbing">
                                    <div class="bg-white dark:bg-[#1a1a20] rounded-xl p-3 border border-gray-200 dark:border-white/10 shadow-sm">
                                        <div class="flex items-center gap-2 mb-1">
                                            <div class="w-5 h-5 rounded bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-[10px] font-bold" x-text="i + 1"></div>
                                            <span class="text-[10px] font-medium text-gray-500 dark:text-gray-400" x-text="getQuestionTypeLabel(q.type)"></span>
                                            <span x-show="isTest && q.points" class="text-[10px] font-bold text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-500/20 px-1.5 py-0.5 rounded" x-text="q.points + ' б.'"></span>
                                        </div>
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate" x-text="q.text || 'Вопрос ' + (i + 1)"></h3>
                                    </div>
                                    <div class="absolute -top-2.5 right-1.5 bg-white dark:bg-[#1a1a20] px-2 py-0.5 rounded-full text-[10px] text-gray-400 border border-gray-200 dark:border-white/10 opacity-0 group-hover:opacity-100 transition-opacity shadow-sm">Перетащите</div>
                                </div>
                                <svg x-show="i < questions.length - 1" class="w-9 h-9 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </div>
                        </template>

                        <svg x-show="questions.length > 0" class="w-9 h-9 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        <div x-show="questions.length > 0" class="flex items-center gap-3 px-5 py-3 bg-white dark:bg-[#1a1a20] rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm">
                            <div class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg></div>
                            <div><span class="block text-sm font-medium text-gray-800 dark:text-gray-200">Отправка</span><span class="text-[10px] text-gray-500 dark:text-gray-400">Конец опроса</span></div>
                        </div>
                        <div x-show="questions.length === 0" class="flex flex-col items-center justify-center w-60 p-5 rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/10 text-gray-400 dark:text-gray-500">
                            <svg class="w-7 h-7 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-xs">Добавьте вопросы</span>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-2.5 bg-gray-100 dark:bg-[#15151a] text-[10px] text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-white/10">💡 Перетаскивайте карточки вопросов ЛКМ, чтобы изменить порядок. Изменения сохранятся при публикации.</div>
            </div>
        </div>

        <!-- 👁️ ПРОСМОТР (ПРЕВЬЮ ОПРОСА) -->
        <div x-show="currentView === 'preview'" x-cloak class="flex-1 bg-gray-50 dark:bg-[#0b0b0f] overflow-y-auto">
            <div x-show="questions.length === 0" class="flex flex-col items-center justify-center min-h-[60vh] px-4">
                <div class="w-20 h-20 bg-gray-100 dark:bg-white/5 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Добавьте хотя бы один вопрос</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Чтобы запустить предпросмотр, добавьте вопросы в опрос</p>
            </div>
            <div x-show="questions.length > 0" class="max-w-3xl mx-auto px-4 py-12">
                <!-- Стартовый экран -->
                <div x-show="!previewStarted && !previewFinished" class="text-center py-16">
                    <div class="w-24 h-24 bg-blue-100 dark:bg-blue-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Тестовое прохождение</h2>
                    <div x-show="isTest" class="mb-6 p-4 bg-purple-50 dark:bg-purple-500/10 rounded-xl inline-block">
                        <p class="text-sm text-purple-700 dark:text-purple-300">
                            <span x-text="questions.length * (testSettings.points_per_question || 10)"></span> максимальных баллов • 
                            <span x-text="testSettings.time_limit ? testSettings.time_limit + ' мин' : 'Без ограничения'"></span>
                        </p>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">Проверьте, как ваш опрос будет выглядеть для респондента. Вы сможете пройти все шаги и увидеть финальную сводку.</p>
                    <button @click="startPreview()" class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl transition shadow-lg shadow-blue-500/20">Начать тест</button>
                </div>
                <!-- Экран прохождения -->
                <div x-show="previewStarted && !previewFinished" class="space-y-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Вопрос <span x-text="previewStep + 1"></span> из <span x-text="questions.length"></span></span>
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400" x-text="Math.round(((previewStep + 1) / questions.length) * 100) + '%'"></span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-white/10 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="'width: ' + ((previewStep + 1) / questions.length * 100) + '%'"></div>
                    </div>
                    <div class="bg-white dark:bg-[#1a1a20] rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm p-8">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-xs uppercase tracking-wide font-bold text-gray-400 dark:text-gray-500">Вопрос №<span x-text="previewStep + 1"></span></div>
                            <div x-show="isTest && questions[previewStep].points" class="text-xs font-bold text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-500/20 px-2 py-1 rounded">
                                <span x-text="questions[previewStep].points"></span> баллов
                            </div>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2" x-text="questions[previewStep].text || 'Без названия'"></h2>
                        <p x-show="questions[previewStep].description" class="text-sm text-gray-500 dark:text-gray-400 mb-6" x-text="questions[previewStep].description"></p>
                        <div class="space-y-3 mt-6">
                            <!-- Radio/Checkbox/Dropdown -->
                            <template x-if="['radio', 'checkbox', 'dropdown'].includes(questions[previewStep].type)">
                                <template x-for="(opt, i) in questions[previewStep].options" :key="i">
                                    <label class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer transition">
                                        <input type="radio" :name="'preview_' + previewStep" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500" @change="setPreviewAnswer(opt)">
                                        <span class="text-gray-700 dark:text-gray-200" x-text="opt"></span>
                                    </label>
                                </template>
                            </template>
                            <!-- Text/Number/Contact -->
                            <template x-if="['text', 'number', 'contact_email', 'contact_phone'].includes(questions[previewStep].type)">
                                <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 focus:outline-none focus:border-blue-400" @input="setPreviewAnswer($event.target.value)">
                            </template>
                            <!-- Yes/No -->
                            <template x-if="questions[previewStep].type === 'yesno'">
                                <div class="flex gap-4">
                                    <button @click="setPreviewAnswer('Да')" class="flex-1 py-3 rounded-xl border border-green-300 dark:border-green-500/30 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 font-medium hover:bg-green-100 transition">Да</button>
                                    <button @click="setPreviewAnswer('Нет')" class="flex-1 py-3 rounded-xl border border-red-300 dark:border-red-500/30 bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 font-medium hover:bg-red-100 transition">Нет</button>
                                </div>
                            </template>
                            <!-- Rating/Smiley -->
                            <template x-if="['rating', 'smiley_rating'].includes(questions[previewStep].type)">
                                <div class="flex gap-2 justify-center mt-4">
                                    <template x-for="n in 5" :key="n">
                                        <button @click="setPreviewAnswer(n)" class="w-10 h-10 rounded-full flex items-center justify-center text-lg hover:scale-110 transition" :class="previewAnswers[previewStep] === n ? 'bg-yellow-400 text-white' : 'bg-gray-100 dark:bg-white/10'">
                                            <span x-show="questions[previewStep].type === 'rating'">★</span>
                                            <span x-show="questions[previewStep].type === 'smiley_rating'" x-text="['😞','😟','😐','🙂','😊'][n-1]"></span>
                                        </button>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="flex justify-between pt-4">
                        <button @click="prevPreviewStep()" :disabled="previewStep === 0" class="px-6 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 disabled:opacity-30 disabled:cursor-not-allowed transition">Назад</button>
                        <button @click="nextPreviewStep()" class="px-8 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-xl transition shadow-lg shadow-blue-500/20">
                            <span x-text="previewStep < questions.length - 1 ? 'Далее' : 'Завершить'"></span>
                        </button>
                    </div>
                </div>
                <!-- Финальный экран -->
                <div x-show="previewFinished" class="text-center py-12">
                    <div class="w-20 h-20 bg-green-100 dark:bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Тестовое прохождение завершено!</h2>
                    <div x-show="isTest" class="mb-6 p-4 bg-purple-50 dark:bg-purple-500/10 rounded-xl inline-block">
                        <p class="text-lg font-bold text-purple-700 dark:text-purple-300">
                            Результат: <span x-text="calculatePreviewScore()"></span> из <span x-text="questions.length * (testSettings.points_per_question || 10)"></span> баллов
                        </p>
                        <p class="text-sm text-purple-600 dark:text-purple-400 mt-1" x-text="getPreviewGrade()"></p>
                    </div>
                    <div class="flex gap-4 justify-center mt-8">
                        <button @click="startPreview()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-medium rounded-xl transition">Пройти снова</button>
                        <button @click="previewStarted = false; previewFinished = false;" class="px-6 py-2.5 border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 rounded-xl transition">Вернуться</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔑 КОНСТРУКТОР -->
        <main x-show="currentView === 'constructor'" class="flex-1 relative bg-gray-50 dark:bg-[#0b0b0f] overflow-y-auto">
            <div class="flex flex-col">
                <template x-for="(question, index) in questions" :key="question.id">
                    <div class="relative bg-white dark:bg-[#1a1a20] border-b border-gray-200 dark:border-white/5 py-12 px-6 min-h-[320px] flex flex-col items-center justify-center hover:bg-gray-50 dark:hover:bg-[#1e1e24] transition-colors">
                        
                        <!-- Кнопки управления -->
                        <button @click="showTypeSelector = true; insertIndex = index" class="absolute top-5 left-5 p-2.5 rounded-xl bg-white dark:bg-[#2a2a30] shadow-md hover:shadow-lg border border-gray-200 dark:border-white/10 transition-all z-10"><svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></button>
                        <div class="absolute top-5 right-5 flex items-center gap-2 z-10">
                            <button @click="openQuestionSettings(index)" class="p-2.5 rounded-xl bg-white dark:bg-[#2a2a30] shadow-md hover:shadow-lg border border-gray-200 dark:border-white/10 transition-all" title="Настройки вопроса">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </button>
                            <div class="flex items-center bg-white dark:bg-[#2a2a30] rounded-xl px-2 py-1.5 shadow-md border border-gray-200 dark:border-white/10 gap-1">
                                <button @click="moveQuestion(index, 'up')" :disabled="index === 0" class="p-1.5 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg disabled:opacity-30 disabled:cursor-not-allowed transition-colors"><svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg></button>
                                <button @click="moveQuestion(index, 'down')" :disabled="index === questions.length - 1" class="p-1.5 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg disabled:opacity-30 disabled:cursor-not-allowed transition-colors"><svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                                <button @click="duplicateQuestion(index)" class="p-1.5 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors"><svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg></button>
                                <div class="w-px h-4 bg-gray-200 dark:bg-white/10 mx-0.5"></div>
                                <button @click="removeQuestion(index, question.text)" class="p-1.5 hover:bg-red-50 dark:hover:bg-red-500/10 text-gray-500 hover:text-red-500 rounded-lg transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </div>
                        </div>

                        <!-- Основной контент вопроса -->
                        <div class="w-full max-w-4xl mx-auto text-center space-y-3">
                            <div class="flex items-center justify-center gap-2 mb-2">
                                <div class="text-[10px] uppercase tracking-wide font-bold text-gray-400 dark:text-gray-500">Вопрос №<span x-text="index + 1"></span></div>
                                <span x-show="isTest && question.points" class="text-[10px] font-bold text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-500/20 px-2 py-0.5 rounded" x-text="question.points + ' баллов'"></span>
                            </div>
                            <input x-model="question.text" placeholder="Введите заголовок вопроса..." class="w-full text-center text-2xl font-semibold bg-transparent border-none focus:ring-0 text-gray-900 dark:text-gray-50 placeholder-gray-400 dark:placeholder-gray-500 p-0">
                            <textarea x-model="question.description" placeholder="Описание или подсказка..." rows="2" class="w-full text-center text-sm bg-transparent border-none focus:ring-0 text-gray-600 dark:text-gray-300 placeholder-gray-400 dark:placeholder-gray-500 p-0 resize-none"></textarea>
                            
                            <!-- Radio/Checkbox -->
                            <div x-show="['radio', 'checkbox'].includes(question.type)" class="mt-6 max-w-md mx-auto space-y-3">
                                <template x-for="(opt, oIndex) in question.options" :key="oIndex">
                                    <div class="relative">
                                        <input x-model="question.options[oIndex]" placeholder="Введите текст варианта..." class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 focus:outline-none focus:border-blue-400 dark:focus:border-blue-500 text-blue-600 dark:text-blue-400 placeholder-gray-400 dark:placeholder-gray-500 text-center shadow-sm transition-colors">
                                        <button @click="removeOption(index, oIndex)" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                    </div>
                                </template>
                                <div class="flex gap-3 justify-center mt-4">
                                    <button @click="addOption(index)" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors shadow-sm flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>Добавить</button>
                                    <button @click="addOptionsList(index)" class="bg-blue-50 hover:bg-blue-100 dark:bg-blue-500/10 dark:hover:bg-blue-500/20 text-blue-600 dark:text-blue-400 px-4 py-2 rounded-xl text-sm font-medium transition-colors flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>Списком</button>
                                </div>
                            </div>

                            <!-- Text/Number -->
                            <div x-show="['text', 'number'].includes(question.type)" class="mt-6 max-w-2xl mx-auto">
                                <input :placeholder="question.placeholder || 'Напишите ответ...'" 
                                    x-model="question.placeholder"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-700 dark:text-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 text-xs">
                            </div>
                        </div>

                        <button @click="showTypeSelector = true; insertIndex = index + 1" class="absolute bottom-5 left-5 p-2.5 rounded-xl bg-white dark:bg-[#2a2a30] shadow-md hover:shadow-lg border border-gray-200 dark:border-white/10 transition-all z-10"><svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></button>
                    </div>
                </template>
            </div>
            
            <!-- Пустое состояние -->
            <div x-show="questions.length === 0" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-50 dark:bg-[#0b0b0f] z-10">
                <button @click="showTypeSelector = true; insertIndex = null" class="group w-28 h-28 rounded-full border-2 border-dashed border-blue-300/80 dark:border-blue-500/40 flex items-center justify-center hover:border-blue-400 dark:hover:border-blue-400 transition-colors cursor-pointer focus:outline-none">
                    <div class="w-20 h-20 rounded-full bg-white dark:bg-[#2a2a30] shadow-sm border border-blue-100 dark:border-white/10 flex items-center justify-center group-hover:shadow-md transition-shadow"><svg class="w-8 h-8 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path></svg></div>
                </button>
                <h3 class="mt-6 text-xl font-semibold text-gray-800 dark:text-white">Пока вопросов нет</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 max-w-xs text-center">Нажмите на плюс, чтобы создать первый вопрос</p>
            </div>
        </main>

        <!-- МОДАЛКА НАСТРОЕК ВОПРОСА (баллы, время) -->
        <div x-show="questionSettingsOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @click.self="questionSettingsOpen = false">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
                <div class="relative bg-white dark:bg-[#1a1a20] rounded-2xl shadow-2xl max-w-md w-full z-10 p-6" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Настройки вопроса</h3>
                        <button @click="questionSettingsOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                    
                    <div x-show="isTest" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Баллов за вопрос</label>
                            <input type="number" x-model.number="questions[currentQuestionIndex].points" min="1" max="100" 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 focus:outline-none focus:border-blue-400">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Максимум баллов за правильный ответ</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Время на вопрос (сек)</label>
                            <input type="number" x-model.number="questions[currentQuestionIndex].time_limit" min="10" max="600" step="10"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 focus:outline-none focus:border-blue-400">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Оставьте пустым для использования общего времени</p>
                        </div>

                        <div x-show="['radio', 'checkbox'].includes(questions[currentQuestionIndex].type)">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Правильные ответы</label>
                            <div class="space-y-2">
                                <template x-for="(opt, idx) in questions[currentQuestionIndex].options" :key="idx">
                                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 cursor-pointer">
                                        <input type="checkbox" x-model="questions[currentQuestionIndex].correct_answers" :value="idx"
                                               class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700 dark:text-gray-300" x-text="opt"></span>
                                    </label>
                                </template>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Отметьте правильные варианты ответа</p>
                        </div>
                    </div>

                    <div x-show="!isTest" class="text-center py-4 text-gray-500 dark:text-gray-400">
                        <p class="text-sm">Включите режим теста в настройках, чтобы настроить баллы</p>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button @click="questionSettingsOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">Закрыть</button>
                        <button @click="saveQuestionSettings()" class="px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition-colors">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- МОДАЛКА ВЫБОРА ТИПА (полная версия со всеми типами вопросов) -->
        <div x-show="showTypeSelector" x-cloak class="fixed inset-0 z-50 overflow-y-auto" @click.self="showTypeSelector = false">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>
                <div class="relative bg-white dark:bg-[#1a1a20] rounded-2xl shadow-2xl max-w-4xl w-full z-10 transform transition-all flex flex-col max-h-[85vh]" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                    <div class="p-5 border-b border-gray-200 dark:border-white/10 flex justify-between items-center flex-shrink-0">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Добавить элемент</h3>
                        <button @click="showTypeSelector = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-2 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                    <div class="p-5 overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                            
                            <!-- Базовые -->
                            <div class="space-y-4">
                                <h4 class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Базовые</h4>
                                <div class="space-y-1.5">
                                    <button @click="addQuestion('welcome', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Приветствие</span>
                                    </button>
                                    <button @click="addQuestion('message', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Сообщение</span>
                                    </button>
                                    <button @click="addQuestion('text', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Текст</span>
                                    </button>
                                    <button @click="addQuestion('number', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Число</span>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Выбор -->
                            <div class="space-y-4">
                                <h4 class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Выбор</h4>
                                <div class="space-y-1.5">
                                    <button @click="addQuestion('radio', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Один вариант</span>
                                    </button>
                                    <button @click="addQuestion('checkbox', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Несколько вариантов</span>
                                    </button>
                                    <button @click="addQuestion('dropdown', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Выпадающий список</span>
                                    </button>
                                    <button @click="addQuestion('yesno', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Да/Нет</span>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Оценка и рейтинг -->
                            <div class="space-y-4">
                                <h4 class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Оценка</h4>
                                <div class="space-y-1.5">
                                    <button @click="addQuestion('rating', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Звёзды</span>
                                    </button>
                                    <button @click="addQuestion('smiley_rating', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Смайлы</span>
                                    </button>
                                    <button @click="addQuestion('scale', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Шкала</span>
                                    </button>
                                    <button @click="addQuestion('slider', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Ползунок</span>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Медиа и файлы -->
                            <div class="space-y-4">
                                <h4 class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Медиа</h4>
                                <div class="space-y-1.5">
                                    <button @click="addQuestion('file_upload', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Файл</span>
                                    </button>
                                    <button @click="addQuestion('image_upload', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Изображение</span>
                                    </button>
                                    <button @click="addQuestion('media', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Выбор медиа</span>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Контакты -->
                            <div class="space-y-4">
                                <h4 class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Контакты</h4>
                                <div class="space-y-1.5">
                                    <button @click="addQuestion('contact_name', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">ФИО</span>
                                    </button>
                                    <button @click="addQuestion('contact_email', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Email</span>
                                    </button>
                                    <button @click="addQuestion('contact_phone', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Телефон</span>
                                    </button>
                                    <button @click="addQuestion('contact_date', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Дата</span>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Сложные типы -->
                            <div class="space-y-4">
                                <h4 class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest">Сложные</h4>
                                <div class="space-y-1.5">
                                    <button @click="addQuestion('ranking', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Ранжирование</span>
                                    </button>
                                    <button @click="addQuestion('matrix', insertIndex); showTypeSelector = false" class="flex items-center gap-2.5 p-2 rounded-xl border border-gray-200 dark:border-white/10 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors text-left group w-full">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-200">Матрица</span>
                                    </button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- МОДАЛКА УДАЛЕНИЯ -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[99998] flex items-center justify-center" @click="showDeleteModal = false; lockScroll(false)" style="position: fixed !important;">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="relative bg-white dark:bg-[#1a1a20] rounded-2xl shadow-2xl p-6 z-[99999]" style="width: 400px; max-width: 90vw;" @click.stop>
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 flex items-center justify-center flex-shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-gray-900 dark:text-white mb-1 text-base">Удалить элемент?</h4>
                        <p class="text-gray-600 dark:text-gray-400 mb-2 break-all text-sm">«<span x-text="questionToDeleteText" class="font-medium text-gray-900 dark:text-white"></span>»</p>
                        <p class="text-xs text-red-600 dark:text-red-400 font-semibold flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>Это действие нельзя отменить</p>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3 border-t border-gray-200 dark:border-white/10">
                    <button @click="showDeleteModal = false; lockScroll(false)" class="px-4 py-2 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">Отмена</button>
                    <button @click="confirmRemoveQuestion()" class="px-4 py-2 text-xs font-semibold bg-red-600 hover:bg-red-500 text-white rounded-lg transition-colors shadow-lg shadow-red-500/30">Удалить</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function pollBuilder(pollId, initialQuestions, redirectUrl) {
        return {
            pollId,
            questions: Array.isArray(initialQuestions) ? initialQuestions : [],
            showTypeSelector: false,
            showDeleteModal: false,
            questionToDeleteIndex: null,
            questionToDeleteText: '',
            saving: false,
            isDark: localStorage.getItem('theme') !== 'light',
            redirectUrl,
            insertIndex: null,
            currentView: 'constructor',
            
            // Тестовые настройки
            isTest: window.__pollInit__.isTest || false,
            testSettings: window.__pollInit__.testSettings || {
                points_per_question: 10,
                time_limit: null,
                grading_scale: 'five_point',
                passing_score: 60
            },
            
            // Настройки вопроса
            questionSettingsOpen: false,
            currentQuestionIndex: null,
            
            // Превью
            previewStep: 0,
            previewAnswers: {},
            previewStarted: false,
            previewFinished: false,

            init() {
                this.updateTheme();
                
                // 🔹 Инициализируем вопросы с данными из БД
                this.questions = this.questions.map(q => {
                    // Парсим JSON-поля, если они пришли как строки
                    const options = typeof q.options === 'string' ? JSON.parse(q.options) : (q.options || []);
                    const correct_answers = typeof q.correct_answers === 'string' ? JSON.parse(q.correct_answers) : (q.correct_answers || []);
                    
                    return {
                        ...q,
                        options: options,
                        correct_answers: correct_answers,
                        // Если баллов нет, но режим теста включён — ставим дефолтное значение
                        points: q.points ?? (this.isTest ? (this.testSettings?.points_per_question || 10) : null)
                    };
                });
            },

            updateTheme() {
                if (this.isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            },

            toggleTheme() {
                this.isDark = !this.isDark;
                localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                this.updateTheme();
            },

            lockScroll(isLocked) {
                document.body.style.overflow = isLocked ? 'hidden' : '';
                document.body.style.paddingRight = isLocked ? '15px' : '';
            },

            addQuestion(type, index = null) {
                const base = { 
                    type, 
                    text: '', 
                    description: '', 
                    options: type === 'radio' || type === 'checkbox' ? ['', ''] : [],
                    is_required: false, 
                    id: Date.now() + '_' + Math.random().toString(36).substr(2, 5),
                    points: this.isTest ? this.testSettings.points_per_question : null,
                    correct_answers: []
                };
                
                if (index === null || index < 0) {
                    this.questions.push(base);
                } else {
                    this.questions.splice(index, 0, base);
                }
                this.insertIndex = null;
            },

            duplicateQuestion(index) {
                const copy = JSON.parse(JSON.stringify(this.questions[index]));
                copy.id = Date.now() + '_' + Math.random().toString(36).substr(2, 5);
                this.questions.splice(index + 1, 0, copy);
            },

            dragStart(event, index) {
                this.draggedIndex = index;
                event.dataTransfer.effectAllowed = 'move';
                setTimeout(() => event.target.classList.add('opacity-50', 'scale-95'), 0);
            },

            dragEnd(event) {
                this.draggedIndex = null;
                event.target.classList.remove('opacity-50', 'scale-95');
            },

            dragDrop(event, dropIndex) {
                if (this.draggedIndex === null || this.draggedIndex === dropIndex) return;
                const item = this.questions.splice(this.draggedIndex, 1)[0];
                this.questions.splice(dropIndex, 0, item);
                this.draggedIndex = null;
            },

            addOption(qIndex) { 
                this.questions[qIndex].options.push(''); 
            },

            addOptionsList(qIndex) {
                const list = prompt('Введите варианты, каждый с новой строки:');
                if (list) {
                    this.questions[qIndex].options = [
                        ...this.questions[qIndex].options, 
                        ...list.split('\n').filter(i => i.trim())
                    ];
                }
            },

            removeOption(qIndex, oIndex) { 
                this.questions[qIndex].options.splice(oIndex, 1); 
            },

            removeQuestion(index, text) {
                this.questionToDeleteIndex = index;
                this.questionToDeleteText = text || 'Вопрос без названия';
                this.showDeleteModal = true;
                this.lockScroll(true);
            },

            confirmRemoveQuestion() {
                if (this.questionToDeleteIndex !== null) {
                    this.questions.splice(this.questionToDeleteIndex, 1);
                }
                this.showDeleteModal = false;
                this.lockScroll(false);
            },

            moveQuestion(index, direction) {
                if (direction === 'up' && index > 0) {
                    [this.questions[index], this.questions[index-1]] = [this.questions[index-1], this.questions[index]];
                } else if (direction === 'down' && index < this.questions.length - 1) {
                    [this.questions[index], this.questions[index+1]] = [this.questions[index+1], this.questions[index]];
                }
            },

            openQuestionSettings(index) {
                this.currentQuestionIndex = index;
                this.questionSettingsOpen = true;
            },

            saveQuestionSettings() {
                const question = this.questions[this.currentQuestionIndex];
                
                // 🔹 Валидация для тестового режима
                if (this.isTest && ['radio', 'checkbox', 'dropdown'].includes(question.type)) {
                    // Проверяем, отмечены ли правильные ответы
                    if (!question.correct_answers || question.correct_answers.length === 0) {
                        // 🔴 Показываем предупреждение и НЕ закрываем модальное окно
                        showToast('⚠️ Отметьте хотя бы один правильный ответ!', 'error');
                        
                        // Подсвечиваем поле с правильными ответами (опционально)
                        const correctAnswersSection = document.querySelector('[x-data] .space-y-2');
                        if (correctAnswersSection) {
                            correctAnswersSection.classList.add('ring-2', 'ring-red-500', 'rounded-lg');
                            setTimeout(() => {
                                correctAnswersSection.classList.remove('ring-2', 'ring-red-500', 'rounded-lg');
                            }, 2000);
                        }
                        return; // Прерываем выполнение, не закрываем модалку
                    }
                    
                    // 🔹 Дополнительная проверка: для "Один вариант" только один правильный ответ
                    if (question.type === 'radio' && question.correct_answers.length > 1) {
                        showToast('⚠️ Для "Выбор одного" можно отметить только один правильный ответ', 'error');
                        return;
                    }
                }
                
                // 🔹 Проверка: баллы за вопрос (если тестовый режим)
                if (this.isTest && (!question.points || question.points < 1)) {
                    showToast('⚠️ Укажите количество баллов за вопрос (минимум 1)', 'error');
                    return;
                }
                
                // 🔹 Проверка: время на вопрос (если задано, должно быть в пределах)
                if (question.time_limit !== null && question.time_limit !== undefined) {
                    if (question.time_limit < 10 || question.time_limit > 600) {
                        showToast('⚠️ Время на вопрос должно быть от 10 до 600 секунд', 'error');
                        return;
                    }
                }
                
                // ✅ Все проверки пройдены — закрываем модальное окно
                this.questionSettingsOpen = false;
                showToast('✅ Настройки вопроса сохранены', 'success');
            },

            async saveTestSettings() {
                this.saving = true;
                try {
                    const payload = {
                        is_test: this.isTest,
                        test_settings: this.testSettings,
                        questions: this.questions.map(q => ({
                            id: q.id,
                            type: q.type,
                            text: q.text,
                            description: q.description,
                            options: q.options,
                            is_required: q.is_required,
                            points: q.points,
                            correct_answers: q.correct_answers,
                            time_limit: q.time_limit,
                            sort_order: this.questions.indexOf(q)
                        }))
                    };

                    const res = await fetch(`/polls/${this.pollId}/questions`, {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                        },
                        body: JSON.stringify(payload)
                    });

                    if (res.ok) {
                        showToast('Настройки теста сохранены!', 'success');
                        // Обновляем бейдж в шапке
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        const error = await res.json().catch(() => ({}));
                        throw new Error(error.message || 'Ошибка сохранения');
                    }
                } catch (e) { 
                    console.error('Save error:', e);
                    showToast('Не удалось сохранить: ' + e.message, 'error');
                } finally { 
                    this.saving = false; 
                }
            },

            getQuestionTypeLabel(type) {
                const map = {
                    welcome: 'Приветствие', 
                    text: 'Текст', 
                    radio: 'Выбор одного', 
                    checkbox: 'Несколько вариантов'
                };
                return map[type] || type;
            },

            async savePoll() {
                if (this.questions.length === 0) return;
                this.saving = true;
                try {
                    const payload = {
                        questions: this.questions.map(q => ({
                            id: q.id,
                            type: q.type,
                            text: q.text,
                            description: q.description,
                            options: q.options,
                            is_required: q.is_required,
                            points: q.points,
                            correct_answers: q.correct_answers,
                            sort_order: this.questions.indexOf(q)
                        })),
                        is_test: this.isTest,
                        test_settings: this.testSettings
                    };

                    const res = await fetch(`/polls/${this.pollId}/questions`, {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
                        },
                        body: JSON.stringify(payload)
                    });

                    if (res.ok) {
                        window.location.href = this.redirectUrl;
                    } else {
                        const error = await res.json().catch(() => ({}));
                        throw new Error(error.message || 'Ошибка сохранения');
                    }
                } catch (e) { 
                    console.error('Save error:', e);
                    alert('Не удалось сохранить изменения: ' + e.message); 
                } finally { 
                    this.saving = false; 
                }
            },

            // Превью функции
            startPreview() {
                this.previewStep = 0;
                this.previewAnswers = {};
                this.previewStarted = true;
                this.previewFinished = false;
            },
            
            nextPreviewStep() {
                if (this.previewStep < this.questions.length - 1) {
                    this.previewStep++;
                } else {
                    this.previewFinished = true;
                }
            },
            
            prevPreviewStep() {
                if (this.previewStep > 0) {
                    this.previewStep--;
                }
            },
            
            setPreviewAnswer(value) {
                this.previewAnswers[this.previewStep] = value;
            },

            calculatePreviewScore() {
                let score = 0;
                this.questions.forEach((q, idx) => {
                    if (q.correct_answers && q.correct_answers.includes(this.previewAnswers[idx])) {
                        score += q.points || this.testSettings.points_per_question;
                    }
                });
                return score;
            },

            getPreviewGrade() {
                const score = this.calculatePreviewScore();
                const maxScore = this.questions.length * (this.testSettings.points_per_question || 10);
                const percent = (score / maxScore) * 100;
                
                if (this.testSettings.grading_scale === 'five_point') {
                    if (percent >= (this.testSettings.grade_5_min || 90)) return '5 (Отлично)';
                    if (percent >= (this.testSettings.grade_4_min || 75)) return '4 (Хорошо)';
                    if (percent >= (this.testSettings.grade_3_min || 60)) return '3 (Удовлетворительно)';
                    return '2 (Неудовлетворительно)';
                } else if (this.testSettings.grading_scale === 'verbal') {
                    return percent >= (this.testSettings.passing_score || 60) ? 'Зачёт' : 'Не зачёт';
                }
                return Math.round(percent) + '%';
            }
        }
    }
    
    // 🔹 Вспомогательная функция для уведомлений
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 text-white transition-all duration-300 transform translate-y-20 opacity-0 ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.remove('translate-y-20', 'opacity-0');
        }, 10);
        
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }
    </script>
</body>
</html><?php /**PATH C:\OSPanel\survey-app\resources\views/polls/build.blade.php ENDPATH**/ ?>