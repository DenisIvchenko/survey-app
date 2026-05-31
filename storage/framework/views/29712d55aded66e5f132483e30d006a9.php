<?php
    /** @var \App\Models\Poll $poll */
    /** @var \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection $reviews */
?>

<div class="space-y-4">
    <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <article class="bg-white dark:bg-[#1a1a20] rounded-2xl border border-gray-200 dark:border-white/10 p-5 sm:p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                <div class="flex flex-wrap items-center gap-2 min-w-0">
                    <span class="font-semibold text-gray-900 dark:text-white truncate">
                        <?php echo e($review->user?->name ?? 'Аноним'); ?>

                    </span>
                    <?php if($review->user): ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-500/20 dark:text-indigo-300"
                              title="Репутация">
                            ★ <?php echo e(number_format((int) $review->user->reputation)); ?>

                        </span>
                    <?php endif; ?>
                </div>
                <time class="text-xs text-gray-500 dark:text-gray-400 shrink-0"
                      datetime="<?php echo e($review->created_at->toIso8601String()); ?>">
                    <?php echo e($review->created_at->diffForHumans()); ?>

                </time>
            </div>

            <div class="mb-3">
                <?php if (isset($component)) { $__componentOriginalb61a0e1628d5c877594425ae307bbe79 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb61a0e1628d5c877594425ae307bbe79 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.rating-display','data' => ['rating' => $review->rating,'size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('rating-display'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['rating' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($review->rating),'size' => 'sm']); ?>
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
            </div>

            <p class="text-gray-700 dark:text-gray-300 text-sm sm:text-base leading-relaxed whitespace-pre-wrap break-words">
                <?php echo e($review->comment); ?>

            </p>

            <?php if(auth()->guard()->check()): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vote', $review)): ?>
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-white/5 flex flex-wrap items-center gap-2"
                         x-data="{ voting: false, voted: false }">
                        <button type="button"
                                @click="castReviewVote($data, '<?php echo e(route('reviews.vote', $review)); ?>', 'useful')"
                                :disabled="voting || voted"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 border border-emerald-200 dark:border-emerald-500/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg x-show="voting"
                                 x-cloak
                                 class="animate-spin w-4 h-4"
                                 xmlns="http://www.w3.org/2000/svg"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg x-show="!voting && !voted"
                                 class="w-4 h-4"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                            </svg>
                            <svg x-show="voted"
                                 x-cloak
                                 class="w-4 h-4"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span x-text="voted ? 'Голос учтён' : 'Полезно'"></span>
                        </button>

                        <button type="button"
                                @click="castReviewVote($data, '<?php echo e(route('reviews.vote', $review)); ?>', 'useless')"
                                :disabled="voting || voted"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 border border-gray-200 dark:border-white/10 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg x-show="voting"
                                 x-cloak
                                 class="animate-spin w-4 h-4"
                                 xmlns="http://www.w3.org/2000/svg"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg x-show="!voting && !voted"
                                 class="w-4 h-4"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.737 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"></path>
                            </svg>
                            <svg x-show="voted"
                                 x-cloak
                                 class="w-4 h-4"
                                 fill="none"
                                 stroke="currentColor"
                                 viewBox="0 0 24 24"
                                 aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span x-text="voted ? 'Голос учтён' : 'Бесполезно'"></span>
                        </button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </article>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="bg-white dark:bg-[#1a1a20] border border-gray-200 dark:border-white/10 rounded-2xl p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-lg font-medium">Отзывов пока нет</p>
            <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Станьте первым, кто оставит отзыв об этом опросе.</p>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\OSPanel\survey-app\resources\views/polls/partials/reviews_list.blade.php ENDPATH**/ ?>