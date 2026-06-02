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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Все опросы</h2>
            <a href="<?php echo e(route('polls.index')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">
                ← Мои опросы
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $polls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $poll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                
                <a href="<?php echo e(route('polls.reviews', $poll)); ?>" 
                   class="block relative bg-white dark:bg-[#1a1a20] rounded-2xl border border-gray-200 dark:border-white/10 hover:shadow-2xl transition-all duration-300 overflow-hidden group">
                    
                    
                    <div class="h-32 rounded-t-2xl relative z-10"
                         style="background: linear-gradient(135deg, #34d399 0%, #22c55e 50%, #10b981 100%);">
                        
                        
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 bg-white/95 dark:bg-[#1a1a20]/95 backdrop-blur-sm rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300 shadow-sm">
                                Активный
                            </span>
                        </div>
                        
                        
                        <div class="absolute top-3 right-3 flex items-center gap-2 bg-white/95 dark:bg-[#1a1a20]/95 backdrop-blur-sm rounded-lg px-3 py-1.5 shadow-sm">
                            
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold shadow-lg flex-shrink-0 overflow-hidden border-2 border-white dark:border-[#1a1a20]">
                                <?php if($poll->user->profile_photo_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $poll->user->profile_photo_path)); ?>" 
                                         class="w-full h-full object-cover"
                                         alt="<?php echo e($poll->user->name); ?>">
                                <?php else: ?>
                                    <?php
                                        $colors = ['from-purple-500 to-pink-500', 'from-blue-500 to-cyan-500', 'from-green-500 to-emerald-500', 'from-orange-500 to-red-500', 'from-indigo-500 to-purple-500'];
                                        $gradient = $colors[abs(crc32($poll->user->name)) % count($colors)];
                                    ?>
                                    <span class="text-white bg-gradient-to-br <?php echo e($gradient); ?> w-full h-full flex items-center justify-center">
                                        <?php echo e(strtoupper(substr($poll->user->name, 0, 1))); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300 truncate max-w-[100px]">
                                <?php echo e($poll->user->name); ?>

                            </span>
                        </div>
                    </div>
                    
                    
                    <div class="p-5 bg-white dark:bg-[#1a1a20] relative z-10 rounded-b-2xl">

                        <div class="flex items-start justify-between gap-2 mb-2">
                            <?php if($poll->surveyRating && (float) $poll->surveyRating->avg_rating > 0): ?>
                                <div class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 hover:bg-yellow-200 dark:hover:bg-yellow-800 transition-colors shrink-0"
                                    title="Смотреть отзывы">
                                    ★ <?php echo e(number_format((float) $poll->surveyRating->avg_rating, 1)); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            <?php echo e($poll->title); ?>

                        </h3>
                        
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                            <?php echo e($poll->questions_count ?? 0); ?> вопросов • <?php echo e($poll->created_at->format('d.m.Y')); ?>

                        </p>
                        
                        <div class="pt-3 border-t border-gray-100 dark:border-white/5">
                            <span class="text-xs text-gray-400 dark:text-gray-500">Публичный опрос</span>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full bg-white dark:bg-[#1a1a20] border border-gray-200 dark:border-white/10 rounded-2xl p-12 text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-2">Пока нет активных опросов</p>
                    <a href="<?php echo e(route('polls.create')); ?>" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Создать первый опрос →</a>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="mt-8"><?php echo e($polls->links()); ?></div>
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
<?php endif; ?><?php /**PATH C:\OSPanel\survey-app\resources\views/polls/public.blade.php ENDPATH**/ ?>