<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div x-data="{
        showCreateModal: false,
        showDeleteModal: false,
        deletePollId: null,
        deletePollTitle: '',
        copiedPollId: null,
        lockScroll(isLocked) {
            if (isLocked) {
                document.body.style.overflow = 'hidden';
                document.body.style.paddingRight = '15px';
            } else {
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }
        },
        async copyPollLink(pollId, takeUrl) {
            try {
                await navigator.clipboard.writeText(takeUrl);
                this.copiedPollId = pollId;
                setTimeout(() => { this.copiedPollId = null; }, 2000);
            } catch (err) {
                const textarea = document.createElement('textarea');
                textarea.value = takeUrl;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                this.copiedPollId = pollId;
                setTimeout(() => { this.copiedPollId = null; }, 2000);
            }
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            
            
            <div x-show="copiedPollId" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="fixed top-4 right-4 z-[10000] bg-green-600 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-medium">Ссылка скопирована!</span>
            </div>
            
            <div class="flex justify-end items-center mb-5">
                <button @click="showCreateModal = true; lockScroll(true)" 
                        class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-3.5 rounded-xl text-lg font-semibold transition active:scale-[0.98] shadow-xl shadow-blue-500/30 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Новый опрос
                </button>
            </div>

            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-xl text-sm text-green-700 dark:text-green-400">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Мои опросы</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $polls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $poll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="relative bg-white dark:bg-[#1a1a20] rounded-2xl border border-gray-200 dark:border-white/10 hover:shadow-2xl transition-all duration-300 overflow-visible"
                         x-data="{ panelOpen: false }"
                         data-poll-id="<?php echo e($poll->id); ?>">
                        
                        <div class="h-32 bg-gradient-to-br from-emerald-400 via-green-500 to-emerald-600 rounded-t-2xl relative z-10 overflow-visible">
                            <div x-show="!panelOpen" class="absolute top-3 left-3 transition-all duration-200" :class="panelOpen ? 'opacity-0 -translate-x-4' : 'opacity-100 translate-x-0'">
                                <span class="px-3 py-1 bg-white/95 dark:bg-black/60 backdrop-blur-sm rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300 shadow-sm status-badge">
                                    <?php if($poll->is_completed): ?>
                                        Завершён
                                    <?php elseif($poll->is_active): ?>
                                        Активный
                                    <?php else: ?>
                                        Черновик
                                    <?php endif; ?>
                                </span>
                            </div>
                            
                            <div x-show="!panelOpen" class="absolute top-3 right-3 transition-all duration-200" :class="panelOpen ? 'opacity-0 translate-x-4' : 'opacity-100 translate-x-0'" style="right: 0.75rem;">
                                <button @click="panelOpen = true" class="w-9 h-9 bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg flex items-center justify-center text-white transition-all hover:scale-110">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="5" cy="12" r="2"></circle>
                                        <circle cx="12" cy="12" r="2"></circle>
                                        <circle cx="19" cy="12" r="2"></circle>
                                    </svg>
                                </button>
                            </div>
                            
                            <div x-show="panelOpen" x-cloak
                                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-y-90 origin-top" x-transition:enter-end="opacity-100 scale-y-100 origin-top"
                                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-y-100 origin-top" x-transition:leave-end="opacity-0 scale-y-90 origin-top"
                                 class="absolute z-50 flex items-center justify-between bg-white/95 dark:bg-[#1a1a20]/95 backdrop-blur-md rounded-t-[12px] h-12 px-6 shadow-lg border-b border-gray-200 dark:border-white/10 w-full"
                                 style="top: 0; left: 0; right: 0;" @click.away="panelOpen = false">
                                
                                
                                <div class="relative group flex flex-col items-center">
                                    <a href="<?php echo e(route('polls.report', $poll)); ?>" 
                                       class="p-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-500/10 rounded-lg transition-colors cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </a>
                                    <span class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 px-3 py-1.5 text-[17px] font-semibold bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-white rounded-md whitespace-nowrap pointer-events-none z-50 shadow-sm backdrop-blur-sm">Отчет</span>
                                </div>

                                
                                <div class="relative group flex flex-col items-center">
                                    <button @click="copyPollLink(<?php echo e($poll->id); ?>, '<?php echo e(route('polls.take', $poll)); ?>')"
                                            class="p-2 text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 hover:bg-green-50 dark:hover:bg-green-500/10 rounded-lg transition-colors cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                                        </svg>
                                    </button>
                                    <span class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 px-3 py-1.5 text-[17px] font-semibold bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-white rounded-md whitespace-nowrap pointer-events-none z-50 shadow-sm backdrop-blur-sm">
                                        <span x-text="copiedPollId === <?php echo e($poll->id); ?> ? 'Скопировано!' : 'Сбор ответов'"></span>
                                    </span>
                                </div>

                                
                                <div class="relative group flex flex-col items-center">
                                    <a href="<?php echo e(route('polls.build', $poll)); ?>" class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-lg transition-colors cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <span class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 px-3 py-1.5 text-[17px] font-semibold bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-white rounded-md whitespace-nowrap pointer-events-none z-50 shadow-sm backdrop-blur-sm">Редактировать</span>
                                </div>

                                                                           
                                <div class="relative group flex flex-col items-center overflow-visible" 
                                     x-data="{ statusMenuOpen: false }"
                                     style="z-index: 60;">
                                    <button @click="statusMenuOpen = !statusMenuOpen" 
                                            class="p-2 text-gray-600 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-500/10 rounded-lg transition-colors cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                    <span class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 px-3 py-1.5 text-[17px] font-semibold bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-white rounded-md whitespace-nowrap pointer-events-none z-50 shadow-sm backdrop-blur-sm">Статус</span>
                                    
                                    
                                    <div x-show="statusMenuOpen" 
                                         x-cloak 
                                         @click.away="statusMenuOpen = false"
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         class="absolute right-0 bottom-full mb-4 w-56 bg-white dark:bg-[#1a1a20] border border-gray-200 dark:border-white/10 rounded-xl shadow-2xl z-[9999]"
                                         style="right: -12px;">
                                        
                                        
                                        <button @click="updatePollStatus(<?php echo e($poll->id); ?>, 'active'); statusMenuOpen = false"
                                                :class="<?php echo e($poll->is_active && !$poll->is_completed ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5'); ?>"
                                                class="w-full px-4 py-3 text-left text-sm font-medium flex items-center justify-between transition-colors rounded-t-xl">
                                            <span>Активный</span>
                                            <?php if($poll->is_active && !$poll->is_completed): ?>
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            <?php endif; ?>
                                        </button>
                                        
                                        
                                        <button @click="updatePollStatus(<?php echo e($poll->id); ?>, 'completed'); statusMenuOpen = false"
                                                :class="<?php echo e($poll->is_completed ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5'); ?>"
                                                class="w-full px-4 py-3 text-left text-sm font-medium flex items-center justify-between transition-colors border-t border-gray-100 dark:border-white/5">
                                            <span>Завершён</span>
                                            <?php if($poll->is_completed): ?>
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            <?php endif; ?>
                                        </button>
                                        
                                        
                                        <button @click="updatePollStatus(<?php echo e($poll->id); ?>, 'draft'); statusMenuOpen = false"
                                                :class="<?php echo e(!$poll->is_active && !$poll->is_completed ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5'); ?>"
                                                class="w-full px-4 py-3 text-left text-sm font-medium flex items-center justify-between transition-colors border-t border-gray-100 dark:border-white/5 rounded-b-xl">
                                            <span>Черновик</span>
                                            <?php if(!$poll->is_active && !$poll->is_completed): ?>
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            <?php endif; ?>
                                        </button>
                                    </div>
                                </div>

                                
                                <div class="relative group flex flex-col items-center">
                                    <a href="<?php echo e(route('polls.take', $poll)); ?>" 
                                    class="p-2 text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 rounded-lg transition-colors cursor-pointer"
                                    title="Пройти опрос">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </a>
                                    <span class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 px-3 py-1.5 text-[17px] font-semibold bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-white rounded-md whitespace-nowrap pointer-events-none z-50 shadow-sm backdrop-blur-sm">
                                        Пройти опрос
                                    </span>
                                </div>

                                
                                <div class="relative group flex flex-col items-center">
                                    <button class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/10 rounded-lg transition-colors cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                    </button>
                                    <span class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 px-3 py-1.5 text-[17px] font-semibold bg-white/90 dark:bg-gray-800/90 text-gray-900 dark:text-white rounded-md whitespace-nowrap pointer-events-none z-50 shadow-sm backdrop-blur-sm">В архив</span>
                                </div>

                                
                                <div class="relative group flex flex-col items-center">
                                    <button @click="panelOpen = false; showDeleteModal = true; deletePollId = <?php echo e($poll->id); ?>; deletePollTitle = '<?php echo e(addslashes($poll->title)); ?>'; lockScroll(true)"
                                            class="p-2 text-red-500 hover:text-red-700 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors cursor-pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    <span class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 px-3 py-1.5 text-[17px] font-semibold bg-white/90 dark:bg-gray-800/90 text-red-600 dark:text-red-500 rounded-md whitespace-nowrap pointer-events-none z-50 shadow-sm backdrop-blur-sm">Удалить</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-5 bg-white dark:bg-[#1a1a20] relative z-10 rounded-b-2xl">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 truncate"><?php echo e($poll->title); ?></h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                <?php
                                    $qCount = $poll->questions_count ?? 0;
                                    $qLastTwo = $qCount % 100;
                                    $qWord = ($qLastTwo >= 5 && $qLastTwo <= 20) ? 'вопросов' : 
                                            match($qCount % 10) { 1 => 'вопрос', 2 => 'вопроса', 3 => 'вопроса', 4 => 'вопроса', default => 'вопросов' };
                                ?>
                                <?php echo e($qCount); ?> <?php echo e($qWord); ?> • <?php echo e($poll->created_at->format('d.m.Y')); ?>

                            </p>
                            <div class="flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 pt-3 border-t border-gray-100 dark:border-white/5">
                                <span>Без ответов</span>
                                <?php
                                    $votesCount = $poll->votes_count ?? 0;
                                ?>
                                <?php if($votesCount > 0): ?>
                                    <span class="text-green-600 dark:text-green-400 font-medium"><?php echo e($votesCount); ?> ответов</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full bg-white dark:bg-[#1a1a20] border border-gray-200 dark:border-white/10 rounded-2xl p-12 text-center">
                        <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 text-lg mb-4">У вас пока нет созданных опросов.</p>
                        <button @click="showCreateModal = true; lockScroll(true)" class="text-blue-600 dark:text-blue-400 hover:underline text-lg font-medium">Создать первый опрос →</button>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mt-8"><?php echo e($polls->links()); ?></div>
        </div>
        
                
        <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-[99998] overflow-y-auto" @click="showCreateModal = false; lockScroll(false)" style="position: fixed !important;">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="flex items-center justify-center min-h-screen px-4 relative z-[99999]">
                <div class="relative bg-white dark:bg-[#1a1a20] rounded-2xl shadow-2xl max-w-md w-full p-8" @click.stop>
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Создать опрос</h3>
                        <p class="text-gray-500 dark:text-gray-400">Введите название, чтобы начать работу</p>
                    </div>
                    <form action="<?php echo e(route('polls.store')); ?>" method="POST" @click.stop>
                        <?php echo csrf_field(); ?>
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Название опроса</label>
                            <input type="text" name="title" id="title" required autofocus placeholder="Например: Опрос удовлетворённости клиентов" class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-5 py-4 text-lg text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500">
                        </div>
                        
                        
                        <div class="mb-6 p-4 bg-purple-50 dark:bg-purple-500/10 rounded-xl border border-purple-200 dark:border-purple-500/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="block text-sm font-medium text-gray-900 dark:text-white">Режим теста</label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Включите для системы баллов и оценивания</p>
                                </div>
                                
                                
                                <div x-data="{ isTest: false }">
                                    <input type="hidden" name="is_test" :value="isTest ? '1' : '0'">
                                    
                                    <button 
                                        type="button"
                                        @click="isTest = !isTest"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                                        :class="isTest ? 'bg-purple-600' : 'bg-gray-200 dark:bg-gray-700'"
                                    >
                                        <span 
                                            class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"
                                            :class="isTest ? 'translate-x-6' : 'translate-x-1'"
                                        ></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end gap-3">
                            <button type="button" @click="showCreateModal = false; lockScroll(false)" class="px-6 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-colors font-medium">Отмена</button>
                            <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-semibold transition-colors shadow-lg shadow-blue-500/30">Создать и продолжить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[99998] flex items-center justify-center" @click="showDeleteModal = false; lockScroll(false)" style="position: fixed !important;">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm"></div>
            <div class="relative bg-white dark:bg-[#1a1a20] rounded-2xl shadow-2xl p-8 z-[99999]" style="width: 420px; max-width: 90vw;" @click.stop>
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-14 h-14 rounded-full bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2 text-lg">Удалить опрос?</h4>
                        <p class="text-gray-600 dark:text-gray-400 mb-3 break-all">«<span x-text="deletePollTitle" class="font-medium text-gray-900 dark:text-white"></span>»</p>
                        <p class="text-sm text-red-600 dark:text-red-400 font-semibold flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Это действие нельзя отменить
                        </p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-white/10">
                    <button @click="showDeleteModal = false; lockScroll(false)" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl transition-colors">Отмена</button>
                    <form :action="`/polls/${deletePollId}`" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="px-5 py-2.5 text-sm font-semibold bg-red-600 hover:bg-red-500 text-white rounded-xl transition-colors shadow-lg shadow-red-500/30">Удалить опрос</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <script>
    function updatePollStatus(pollId, status) {
        const card = document.querySelector(`[data-poll-id="${pollId}"]`);
        const badge = card?.querySelector('.status-badge');
        const originalText = badge?.textContent;
        
        if (badge) {
            badge.textContent = 'Сохранение...';
            badge.classList.add('opacity-75');
        }

        fetch(`/polls/${pollId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (badge) {
                    badge.textContent = data.status;
                    badge.className = `px-3 py-1 ${data.badge_class} backdrop-blur-sm rounded-lg text-xs font-semibold shadow-sm`;
                }
                
                // Перезагружаем страницу для корректного отображения
                setTimeout(() => {
                    window.location.reload();
                }, 800);
                
                showToast(`Статус изменён: ${data.status}`, 'success');
            }
        })
        .catch(error => {
            console.error('Error updating status:', error);
            if (badge && originalText) {
                badge.textContent = originalText;
                badge.classList.remove('opacity-75');
            }
            showToast('Не удалось изменить статус опроса', 'error');
        });
    }

    // Вспомогательная функция для уведомлений
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH E:\OSPanel\survey-app\resources\views/polls/index.blade.php ENDPATH**/ ?>