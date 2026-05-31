<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\GuestLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <style>
        body { align-items: initial !important; justify-content: initial !important; overflow-y: auto !important; padding: 0 !important; }
        body > div[class*="max-w-"] { max-width: none !important; width: 100% !important; padding: 0 !important; border: none !important; box-shadow: none !important; background: transparent !important; margin: 0 !important; }
        .absolute[class*="blur-"] { display: none !important; }
    </style>
    
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-[#0b0b0f] dark:to-[#121218] flex items-center justify-center px-4">
        <div class="w-full max-w-2xl bg-white/80 dark:bg-[#1a1a20]/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-200/50 dark:border-white/10 p-8 sm:p-12 text-center">
            <div class="w-20 h-20 bg-green-100 dark:bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-3">
                Спасибо за участие!
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">
                Ваши ответы успешно сохранены.
            </p>
            <a href="<?php echo e(route('dashboard')); ?>" 
               class="inline-block px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-semibold transition-all shadow-lg shadow-blue-500/25">
                Вернуться на главную
            </a>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?><?php /**PATH E:\OSPanel\survey-app\resources\views/polls/thanks.blade.php ENDPATH**/ ?>