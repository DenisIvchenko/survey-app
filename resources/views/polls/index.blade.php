<x-app-layout>
<div x-data="{
showCreateModal: false,
showDeleteModal: false,
deletePollId: null,
deletePollTitle: '',
lockScroll(isLocked) {
if (isLocked) {
document.body.style.overflow = 'hidden';
document.body.style.paddingRight = '15px';
} else {
document.body.style.overflow = '';
document.body.style.paddingRight = '';
}
}
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Заголовок + КНОПКА -->
        <div class="flex justify-end items-center mb-8">
            <button @click="showCreateModal = true; lockScroll(true)" class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-3.5 rounded-xl text-lg font-semibold transition active:scale-[0.98] shadow-xl shadow-blue-500/30 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Новый опрос
            </button>
        </div>

        <!-- Уведомление -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-xl text-sm text-green-700 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <!-- Заголовок -->
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Мои опросы</h2>

        <!-- СЕТКА ОПРОСОВ -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($polls as $poll)
                <!-- 🔵 КАРТОЧКА -->
                <!-- УБРАН overflow-hidden, чтобы тултипы не обрезались -->
                <div class="group relative bg-white dark:bg-[#1a1a20] rounded-2xl border border-gray-200 dark:border-white/10 hover:shadow-2xl transition-all duration-300"
                     x-data="{ panelOpen: false, hoverTip: null }">
                    
                    <!-- Цветная шапка -->
                    <!-- ДОБАВЛЕН overflow-hidden для скругления углов внутри -->
                    <div class="h-32 bg-gradient-to-br from-emerald-400 via-green-500 to-emerald-600 rounded-t-2xl relative z-10 overflow-hidden">
                        
                        <!-- Статус (слева) -->
                        <div x-show="!panelOpen"
                             class="absolute top-3 left-3 transition-all duration-200"
                             :class="panelOpen ? 'opacity-0 -translate-x-4' : 'opacity-100 translate-x-0'">
                            <span class="px-3 py-1 bg-white/95 dark:bg-black/60 backdrop-blur-sm rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300 shadow-sm">
                                {{ $poll->is_active ? 'Активный' : 'Черновик' }}
                            </span>
                        </div>
                        
                        <!-- 🔴 ТРОЕТОЧИЕ (СПРАВА) -->
                        <div x-show="!panelOpen"
                             class="absolute top-3 right-3 transition-all duration-200"
                             :class="panelOpen ? 'opacity-0 translate-x-4' : 'opacity-100 translate-x-0'"
                             style="right: 0.75rem;">
                            <button @click="panelOpen = true; hoverTip = null" 
                                    class="w-9 h-9 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg flex items-center justify-center text-white transition-all hover:scale-110">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="5" cy="12" r="2"></circle>
                                    <circle cx="12" cy="12" r="2"></circle>
                                    <circle cx="19" cy="12" r="2"></circle>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- 🟥 ПАНЕЛЬ ДЕЙСТВИЙ -->
                        <div x-show="panelOpen"
                             x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-y-90 origin-top"
                             x-transition:enter-end="opacity-100 scale-y-100 origin-top"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-y-100 origin-top"
                             x-transition:leave-end="opacity-0 scale-y-90 origin-top"
                             class="absolute z-30 flex items-center justify-between bg-white/95 dark:bg-[#1a1a20]/95 backdrop-blur-md rounded-t-xl h-12 px-6 shadow-lg border-b border-gray-200 dark:border-white/10"
                             style="top: 0; left: 0; right: 0;"
                             @click.away="panelOpen = false; hoverTip = null">
                            
                            <!-- 1. Редактировать -->
                            <div class="relative flex flex-col items-center">
                                <a href="{{ route('polls.build', $poll) }}" @mouseenter="hoverTip = 'edit'" @mouseleave="hoverTip = null" class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-lg transition-colors cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                            </div>

                            <!-- 2. Сбор ответов -->
                            <div class="relative flex flex-col items-center">
                                <button @mouseenter="hoverTip = 'collect'" @mouseleave="hoverTip = null" class="p-2 text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-500/10 rounded-lg transition-colors cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                </button>
                            </div>

                            <!-- 3. Отчет -->
                            <div class="relative flex flex-col items-center">
                                <button @mouseenter="hoverTip = 'report'" @mouseleave="hoverTip = null" class="p-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-500/10 rounded-lg transition-colors cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                                </button>
                            </div>

                            <!-- 4. Дублировать -->
                            <div class="relative flex flex-col items-center">
                                <button @mouseenter="hoverTip = 'duplicate'" @mouseleave="hoverTip = null" class="p-2 text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 rounded-lg transition-colors cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </button>
                            </div>

                            <!-- 5. Переименовать -->
                            <div class="relative flex flex-col items-center">
                                <button @mouseenter="hoverTip = 'rename'" @mouseleave="hoverTip = null" class="p-2 text-gray-600 dark:text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-500/10 rounded-lg transition-colors cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                </button>
                            </div>

                            <!-- 6. В архив -->
                            <div class="relative flex flex-col items-center">
                                <button @mouseenter="hoverTip = 'archive'" @mouseleave="hoverTip = null" class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/10 rounded-lg transition-colors cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                </button>
                            </div>

                            <!-- 7. Удалить -->
                            <div class="relative flex flex-col items-center">
                                <button @mouseenter="hoverTip = 'delete'" @mouseleave="hoverTip = null" @click="panelOpen = false; showDeleteModal = true; deletePollId = {{ $poll->id }}; deletePollTitle = '{{ addslashes($poll->title) }}'; lockScroll(true)" 
                                        class="p-2 text-red-500 hover:text-red-700 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- ТУЛТИПЫ (ВЫНЕСЕНЫ ЗА ШАПКУ, ВЫРАВНИВАЮТСЯ ПО ШИРИНЕ КАРТОЧКИ) -->
                    <div class="absolute top-0 left-0 right-0 z-50 flex justify-between px-6 pointer-events-none h-12">
                        <!-- 1. Редактировать -->
                        <div class="flex-1 flex justify-center">
                            <span :class="hoverTip === 'edit' ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-2 pointer-events-none'" 
                                  style="white-space:nowrap !important; width:max-content;"  
                                  class="absolute bottom-full mb-2 px-2.5 py-1 text-[10px] font-medium bg-black/70 text-white rounded-md backdrop-blur-sm whitespace-nowrap w-max min-w-max transition-all duration-200">
                                Редактировать
                            </span>
                        </div>
                        <!-- 2. Сбор ответов -->
                        <div class="flex-1 flex justify-center">
                            <span :class="hoverTip === 'collect' ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-2 pointer-events-none'" 
                                  style="white-space:nowrap !important; width:max-content;"
                                  class="absolute bottom-full mb-2 px-2.5 py-1 text-[10px] font-medium bg-black/70 text-white rounded-md backdrop-blur-sm whitespace-nowrap w-max min-w-max transition-all duration-200">
                                Сбор ответов
                            </span>
                        </div>
                        <!-- 3. Отчет -->
                        <div class="flex-1 flex justify-center">
                            <span :class="hoverTip === 'report' ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-2 pointer-events-none'" 
                                  style="white-space:nowrap !important; width:max-content;"  
                                  class="absolute bottom-full mb-2 px-2.5 py-1 text-[10px] font-medium bg-black/70 text-white rounded-md backdrop-blur-sm whitespace-nowrap w-max min-w-max transition-all duration-200">
                                Отчет
                            </span>
                        </div>
                        <!-- 4. Дублировать -->
                        <div class="flex-1 flex justify-center">
                            <span :class="hoverTip === 'duplicate' ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-2 pointer-events-none'" 
                                  style="white-space:nowrap !important; width:max-content;"  
                                  class="absolute bottom-full mb-2 px-2.5 py-1 text-[10px] font-medium bg-black/70 text-white rounded-md backdrop-blur-sm whitespace-nowrap w-max min-w-max transition-all duration-200">
                                Дублировать
                            </span>
                        </div>
                        <!-- 5. Переименовать -->
                        <div class="flex-1 flex justify-center">
                            <span :class="hoverTip === 'rename' ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-2 pointer-events-none'" 
                                  style="white-space:nowrap !important; width:max-content;"  
                                  class="absolute bottom-full mb-2 px-2.5 py-1 text-[10px] font-medium bg-black/70 text-white rounded-md backdrop-blur-sm whitespace-nowrap w-max min-w-max transition-all duration-200">
                                Переименовать
                            </span>
                        </div>
                        <!-- 6. В архив -->
                        <div class="flex-1 flex justify-center">
                            <span :class="hoverTip === 'archive' ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-2 pointer-events-none'" 
                                  style="white-space:nowrap !important; width:max-content;"  
                                  class="absolute bottom-full mb-2 px-2.5 py-1 text-[10px] font-medium bg-black/70 text-white rounded-md backdrop-blur-sm whitespace-nowrap w-max min-w-max transition-all duration-200">
                                В архив
                            </span>
                        </div>
                        <!-- 7. Удалить -->
                        <div class="flex-1 flex justify-center">
                            <span :class="hoverTip === 'delete' ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-2 pointer-events-none'" 
                                  style="white-space:nowrap !important; width:max-content;"  
                                  class="absolute bottom-full mb-2 px-2.5 py-1 text-[10px] font-medium bg-red-600/80 text-white rounded-md backdrop-blur-sm whitespace-nowrap w-max min-w-max transition-all duration-200">
                                Удалить
                            </span>
                        </div>
                    </div>
                    
                    <!-- Нижняя часть карточки -->
                    <!-- ДОБАВЛЕН overflow-hidden для скругления нижних углов -->
                    <div class="p-5 rounded-b-2xl bg-white dark:bg-[#1a1a20] relative z-10 overflow-hidden">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 truncate">{{ $poll->title }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                            {{ $poll->questions_count ?? 0 }} вопросов • {{ $poll->created_at->format('d.m.Y') }}
                        </p>
                        <div class="flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 pt-3 border-t border-gray-100 dark:border-white/5">
                            <span>Без ответов</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-[#1a1a20] border border-gray-200 dark:border-white/10 rounded-2xl p-12 text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-4">У вас пока нет созданных опросов.</p>
                    <button @click="showCreateModal = true; lockScroll(true)" class="text-blue-600 dark:text-blue-400 hover:underline text-lg font-medium">
                        Создать первый опрос →
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Пагинация -->
        <div class="mt-8">
            {{ $polls->links() }}
        </div>

    </div>

    {{-- ================= МОДАЛЬНОЕ ОКНО: СОЗДАНИЕ ================= --}}
    <div x-show="showCreateModal" 
         x-cloak 
         class="fixed inset-0 z-[99998] overflow-y-auto" 
         @click="showCreateModal = false; lockScroll(false)"
         style="position: fixed !important; z-index: 99998 !important;">
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" style="position: fixed !important; z-index: 99998 !important;"></div>
        
        <div class="flex items-center justify-center min-h-screen px-4 relative z-[99999]" style="position: relative !important; z-index: 99999 !important;">
            <div class="relative bg-white dark:bg-[#1a1a20] rounded-2xl shadow-2xl max-w-md w-full p-8" @click.stop>
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Создать опрос</h3>
                    <p class="text-gray-500 dark:text-gray-400">Введите название, чтобы начать работу</p>
                </div>
                
                <form action="{{ route('polls.store') }}" method="POST" @click.stop>
                    @csrf
                    <div class="mb-6">
                        <input type="text" 
                               name="title" 
                               required
                               autofocus
                               placeholder="Например: Опрос удовлетворённости клиентов"
                               class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-5 py-4 text-lg text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500">
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showCreateModal = false; lockScroll(false)" class="px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-colors font-medium">
                            Отмена
                        </button>
                        <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-semibold transition-colors shadow-lg shadow-blue-500/30">
                            Создать и продолжить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ================= МОДАЛЬНОЕ ОКНО: УДАЛЕНИЕ ================= --}}
    <div x-show="showDeleteModal" 
         x-cloak 
         class="fixed inset-0 z-[99998] flex items-center justify-center"
         @click="showDeleteModal = false; lockScroll(false)"
         style="display: none; position: fixed !important; z-index: 99998 !important;">
        
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm" style="position: fixed !important; z-index: 99998 !important;"></div>
        
        <div class="relative bg-white dark:bg-[#1a1a20] rounded-2xl shadow-2xl p-8 z-[99999]"
             style="width: 420px; max-width: 90vw; position: relative !important; z-index: 99999 !important;"
             @click.stop>
            
            <div class="flex items-start gap-4 mb-6">
                <div class="w-14 h-14 rounded-full bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-2 text-lg">Удалить опрос?</h4>
                    <p class="text-gray-600 dark:text-gray-400 mb-3 break-all">
                        «<span x-text="deletePollTitle" class="font-medium text-gray-900 dark:text-white"></span>»
                    </p>
                    <p class="text-sm text-red-600 dark:text-red-400 font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Это действие нельзя отменить
                    </p>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                <button @click="showDeleteModal = false; lockScroll(false)" 
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-colors">
                    Отмена
                </button>
                <form :action="`/polls/${deletePollId}`" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-5 py-2.5 text-sm font-semibold bg-red-600 hover:bg-red-500 text-white rounded-xl transition-colors shadow-lg shadow-red-500/30">
                        Удалить опрос
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
</x-app-layout>