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
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        
        <div class="mb-8">
            <a href="<?php echo e(route('polls.index')); ?>" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:underline mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Назад к опросам
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><?php echo e($poll->title); ?></h1>
            <?php if($poll->description): ?><p class="text-gray-600 dark:text-gray-400 mt-2"><?php echo e($poll->description); ?></p><?php endif; ?>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white dark:bg-[#1a1a20] rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                    <?php echo e($poll->questions->sum('total_votes')); ?>

                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Всего ответов</div>
            </div>
            <div class="bg-white dark:bg-[#1a1a20] rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                <div class="text-3xl font-bold text-green-600 dark:text-green-400"><?php echo e($poll->questions->count()); ?></div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Вопросов</div>
            </div>
            <div class="bg-white dark:bg-[#1a1a20] rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400"><?php echo e($poll->is_active ? 'Активен' : 'Черновик'); ?></div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Статус</div>
            </div>
        </div>

        
        <?php $__empty_1 = true; $__currentLoopData = $poll->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white dark:bg-[#1a1a20] rounded-2xl p-6 mb-6 border border-gray-200 dark:border-white/10">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    <?php echo e($loop->iteration); ?>. <?php echo e($question->text); ?>

                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">
                        (<?php echo e($question->total_votes ?? 0); ?> ответов)
                    </span>
                </h3>
                
                <?php if(($question->stats ?? collect())->count() > 0): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $question->stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $percent = $question->total_votes > 0 
                                    ? round(($stat['count'] / $question->total_votes) * 100) 
                                    : 0;
                            ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-white/5 rounded-xl">
                                <span class="text-gray-700 dark:text-gray-300"><?php echo e($stat['text']); ?></span>
                                <div class="flex items-center gap-3">
                                    <div class="w-32 bg-gray-200 dark:bg-white/10 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" 
                                             style="width: <?php echo e($percent); ?>%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400 w-10 text-right">
                                        <?php echo e($stat['count']); ?>

                                    </span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">Нет ответов</p>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-12 bg-white dark:bg-[#1a1a20] rounded-2xl border border-gray-200 dark:border-white/10">
                <p class="text-gray-500 dark:text-gray-400">В опросе нет вопросов.</p>
            </div>
        <?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\OSPanel\survey-app\resources\views/polls/report.blade.php ENDPATH**/ ?>