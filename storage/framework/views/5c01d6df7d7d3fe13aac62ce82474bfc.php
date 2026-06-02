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

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-[#0b0b0f] dark:to-[#121218] flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-2xl bg-white/80 dark:bg-[#1a1a20]/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-200/50 dark:border-white/10 p-8 sm:p-12">
            
            <div class="text-center mb-10">
                <div class="w-20 h-20 bg-green-100 dark:bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-3">
                    Спасибо за участие!
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mb-2">
                    Ваши ответы успешно сохранены.
                </p>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    <?php echo e($poll->title); ?>

                </p>
            </div>

            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-xl text-sm text-green-700 dark:text-green-400 text-center">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('info')): ?>
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-xl text-sm text-blue-700 dark:text-blue-400 text-center">
                    <?php echo e(session('info')); ?>

                </div>
            <?php endif; ?>

            
            <?php if($averageRating > 0): ?>
                <div class="mb-8 flex flex-col sm:flex-row items-center justify-center gap-3 p-4 bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/20 rounded-2xl">
                    <?php if (isset($component)) { $__componentOriginalb61a0e1628d5c877594425ae307bbe79 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb61a0e1628d5c877594425ae307bbe79 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rating-display','data' => ['rating' => $averageRating,'size' => 'lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('rating-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['rating' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($averageRating),'size' => 'lg']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb61a0e1628d5c877594425ae307bbe79)): ?>
<?php $attributes = $__attributesOriginalb61a0e1628d5c877594425ae307bbe79; ?>
<?php unset($__attributesOriginalb61a0e1628d5c877594425ae307bbe79); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb61a0e1628d5c877594425ae307bbe79)): ?>
<?php $component = $__componentOriginalb61a0e1628d5c877594425ae307bbe79; ?>
<?php unset($__componentOriginalb61a0e1628d5c877594425ae307bbe79); ?>
<?php endif; ?>
                    <div class="text-center sm:text-left">
                        <p class="text-sm text-yellow-800/80 dark:text-yellow-300/80">Средняя оценка опроса</p>
                        <p class="text-2xl font-bold text-yellow-800 dark:text-yellow-300">
                            <?php echo e(number_format((float) $averageRating, 1)); ?> <span class="text-base font-normal text-yellow-700/70 dark:text-yellow-400/70">/ 5</span>
                        </p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(auth()->guard()->check()): ?>
                <?php if($canReview): ?>
                    <div class="border-t border-gray-200 dark:border-white/10 pt-8 mb-8">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1 text-center sm:text-left">
                            Оставить отзыв
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 text-center sm:text-left">
                            Поделитесь впечатлением об опросе — это поможет другим участникам.
                        </p>

                        <form method="POST"
                              action="<?php echo e(route('reviews.store')); ?>"
                              class="space-y-6"
                              x-data="{
                                  rating: <?php echo e((int) old('rating', 0)); ?>,
                                  hover: 0,
                                  submitting: false,
                                  comment: <?php echo \Illuminate\Support\Js::from(old('comment', ''))->toHtml() ?>,
                                  maxLength: 1500,
                              }"
                              @submit.prevent="submitting = true; $el.submit()">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="survey_id" value="<?php echo e($poll->id); ?>">
                            <input type="hidden" name="rating" x-model="rating">

                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['value' => 'Ваша оценка','class' => 'dark:text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => 'Ваша оценка','class' => 'dark:text-gray-300']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                <div class="mt-2 flex items-center gap-1"
                                     role="group"
                                     aria-label="Выберите оценку от 1 до 5">
                                    <?php for($star = 1; $star <= 5; $star++): ?>
                                        <button type="button"
                                                @click="rating = <?php echo e($star); ?>"
                                                @mouseover="hover = <?php echo e($star); ?>"
                                                @mouseleave="hover = 0"
                                                class="p-1 rounded-lg transition-transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-yellow-400/50"
                                                aria-label="<?php echo e($star); ?> из 5">
                                            <svg class="w-8 h-8 transition-colors"
                                                 :class="<?php echo e($star); ?> <= (hover || rating) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'"
                                                 viewBox="0 0 20 20"
                                                 fill="currentColor"
                                                 aria-hidden="true">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </button>
                                    <?php endfor; ?>
                                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400" x-show="rating > 0" x-text="rating + ' / 5'"></span>
                                </div>
                                <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('rating'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('rating')),'class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                            </div>

                            
                            <div>
                                <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'comment','value' => 'Комментарий','class' => 'dark:text-gray-300']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'comment','value' => 'Комментарий','class' => 'dark:text-gray-300']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                <textarea id="comment"
                                          name="comment"
                                          rows="4"
                                          maxlength="1500"
                                          x-model="comment"
                                          required
                                          placeholder="Опишите ваш опыт (минимум 15 символов)..."
                                          class="mt-2 block w-full border-gray-300 dark:border-white/10 dark:bg-[#121216] dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"><?php echo e(old('comment')); ?></textarea>
                                <div class="mt-1 flex justify-between items-center">
                                    <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('comment')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('comment'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
                                    <span class="text-xs text-gray-400 dark:text-gray-500 ml-auto"
                                          :class="comment.length > maxLength ? 'text-red-500' : ''"
                                          x-text="comment.length + ' / ' + maxLength"></span>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['type' => 'submit','xBind:disabled' => 'submitting','class' => '!bg-blue-600 hover:!bg-blue-500 disabled:!opacity-60 disabled:!cursor-not-allowed !normal-case !tracking-normal !text-sm !px-6 !py-3 !rounded-xl shadow-lg shadow-blue-500/30 inline-flex items-center gap-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','x-bind:disabled' => 'submitting','class' => '!bg-blue-600 hover:!bg-blue-500 disabled:!opacity-60 disabled:!cursor-not-allowed !normal-case !tracking-normal !text-sm !px-6 !py-3 !rounded-xl shadow-lg shadow-blue-500/30 inline-flex items-center gap-2']); ?>
                                    <svg x-show="submitting"
                                         x-cloak
                                         class="animate-spin w-4 h-4"
                                         xmlns="http://www.w3.org/2000/svg"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         aria-hidden="true">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="submitting ? 'Отправка…' : 'Отправить отзыв'"></span>
                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2 border-t border-gray-200 dark:border-white/10">
                <a href="<?php echo e(route('dashboard')); ?>"
                   class="inline-block px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-semibold transition-all shadow-lg shadow-blue-500/25 text-center">
                    Вернуться на главную
                </a>
            </div>
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
<?php endif; ?>
<?php /**PATH C:\OSPanel\survey-app\resources\views/polls/thanks.blade.php ENDPATH**/ ?>