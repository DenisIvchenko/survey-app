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
        [x-cloak] { display: none !important; }
        .step-transition { transition: opacity 0.3s ease, transform 0.3s ease; }
        .step-enter { opacity: 0; transform: translateX(20px); }
        .step-enter-active { opacity: 1; transform: translateX(0); }
        .media-card { transition: all 0.2s ease; }
        .media-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .media-card.selected { border-color: #3b82f6 !important; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3); }
        input[type="file"]::-webkit-file-upload-button { @apply bg-blue-600 text-white px-4 py-2 rounded-lg border-0 font-medium cursor-pointer hover:bg-blue-500 transition; }
    </style>
    
    
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-[#0b0b0f] dark:to-[#121218] py-6 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-7xl mx-auto">
            
            
            <form action="<?php echo e(route('polls.submit', $poll)); ?>" method="POST" 
                  x-data="{ 
                      currentStep: 0, 
                      answers: {},
                      nextStep() { this.currentStep++ },
                      prevStep() { this.currentStep-- }
                  }"
                  class="space-y-6"
                  enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                
                <div class="bg-white/80 dark:bg-[#1a1a20]/80 backdrop-blur-xl rounded-3xl shadow-xl border border-gray-200/50 dark:border-white/10 p-6 sm:p-8 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white"><?php echo e($poll->title); ?></h1>
                            <?php if($poll->description): ?><p class="text-gray-600 dark:text-gray-400 text-sm mt-1"><?php echo e($poll->description); ?></p><?php endif; ?>
                        </div>
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-white/5 px-3 py-1.5 rounded-full">
                            Вопрос <span x-text="currentStep + 1"></span> из <?php echo e($poll->questions->count()); ?>

                        </span>
                    </div>
                    
                    <div class="w-full bg-gray-200 dark:bg-white/10 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-500 ease-out"
                             x-bind:style="`width: ${((currentStep + 1) / <?php echo e($poll->questions->count()); ?>) * 100}%`"></div>
                    </div>
                </div>

                
                <?php if($errors->any()): ?>
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-4 mb-6">
                        <h3 class="text-sm font-semibold text-red-800 dark:text-red-200 mb-2">Исправьте ошибки:</h3>
                        <ul class="text-sm text-red-700 dark:text-red-300 list-disc list-inside space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php $__currentLoopData = $poll->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div x-show="currentStep === <?php echo e($index); ?>" 
                         x-cloak
                         x-transition:enter="step-transition step-enter"
                         x-transition:enter-start="step-enter"
                         x-transition:enter-end="step-enter-active"
                         class="bg-white dark:bg-[#1a1a20] rounded-3xl shadow-xl border border-gray-200/50 dark:border-white/10 p-6 sm:p-8">
                        
                        
                        <div class="mb-6">
                            <div class="flex items-start gap-4">
                                <span class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-lg"><?php echo e($index + 1); ?></span>
                                <div class="flex-1 min-w-0">
                                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-1">
                                        <?php echo e($question->text); ?><?php if($question->is_required): ?><span class="text-red-500 ml-1">*</span><?php endif; ?>
                                    </h2>
                                    <?php if($question->description): ?><p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($question->description); ?></p><?php endif; ?>
                                </div>
                            </div>
                        </div>

                        
                        <?php if(in_array($question->type, ['text', 'number', 'contact_email', 'contact_phone'])): ?>
                            <div class="max-w-2xl">
                                <input type="<?php echo e($question->type === 'contact_email' ? 'email' : ($question->type === 'number' ? 'number' : 'text')); ?>"
                                       name="answers[<?php echo e($question->id); ?>]"
                                       <?php if($question->is_required): echo 'required'; endif; ?>
                                       placeholder="<?php echo e(is_array($question->options) ? ($question->options['placeholder'] ?? 'Ваш ответ') : 'Ваш ответ'); ?>"
                                       class="w-full px-5 py-4 rounded-2xl border border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-[#121216] text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>

                        
                        <?php elseif($question->type === 'radio'): ?>
                            <div class="max-w-2xl space-y-3">
                                <?php $__currentLoopData = (is_array($question->options) ? $question->options : []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center gap-4 p-4 rounded-2xl border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 hover:border-blue-300 dark:hover:border-blue-500/50 cursor-pointer transition-all group">
                                        <input type="radio" name="answers[<?php echo e($question->id); ?>]" value="<?php echo e($loop->index); ?>" <?php if($question->is_required): echo 'required'; endif; ?> class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?php echo e($option); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                        
                        <?php elseif($question->type === 'checkbox'): ?>
                            <div class="max-w-2xl space-y-3">
                                <?php $__currentLoopData = (is_array($question->options) ? $question->options : []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex items-center gap-4 p-4 rounded-2xl border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 hover:border-blue-300 dark:hover:border-blue-500/50 cursor-pointer transition-all group">
                                        <input type="checkbox" name="answers[<?php echo e($question->id); ?>][]" value="<?php echo e($loop->index); ?>" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors"><?php echo e($option); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                        
                        <?php elseif($question->type === 'media'): ?>
                            <?php
                                $options = is_array($question->options) ? $question->options : [];
                                $multiple = $options['multiple'] ?? false;
                                $images = array_values(array_filter($options, fn($opt) => is_array($opt) && isset($opt['path']) && is_string($opt['path'])));
                            ?>
                            <?php if(count($images) > 0): ?>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4">
                                    <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="media-card relative cursor-pointer group block">
                                            <input type="<?php echo e($multiple ? 'checkbox' : 'radio'); ?>" name="answers[<?php echo e($question->id); ?>]<?php echo e($multiple ? '[]' : ''); ?>" value="<?php echo e($idx); ?>" <?php if($question->is_required): echo 'required'; endif; ?> class="absolute inset-0 opacity-0 cursor-pointer z-20 peer" onchange="this.closest('label').classList.toggle('selected', this.checked)">
                                            <div class="aspect-square rounded-2xl overflow-hidden border-2 peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-500/30 border-gray-200 dark:border-white/10 transition-all duration-200 group-hover:border-blue-400 bg-gray-100 dark:bg-white/5">
                                                <img src="<?php echo e(asset('storage/' . ($option['path'] ?? ''))); ?>" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/200x200/9ca3af/ffffff?text=No+Image'">
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transform scale-75 peer-checked:scale-100 transition-all shadow-lg">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent text-white text-[10px] sm:text-xs py-2 px-3 truncate"><?php echo e($option['name'] ?? 'Изображение #' . ($idx + 1)); ?></span>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-8 bg-gray-50 dark:bg-white/5 rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/10">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Нет изображений для выбора</p>
                                </div>
                            <?php endif; ?>

                        
                        <?php elseif($question->type === 'file_upload'): ?>
                            <div class="max-w-2xl">
                                <label class="block border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-6 text-center hover:border-blue-400 dark:hover:border-blue-500 transition-colors cursor-pointer bg-gray-50/50 dark:bg-white/5">
                                    <div class="flex flex-col items-center">
                                        <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center mb-4">
                                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Перетащите файлы сюда</p>
                                        <input type="file" name="answers[<?php echo e($question->id); ?>][]" multiple <?php if($question->is_required): echo 'required'; endif; ?> accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar" class="hidden">
                                        <span class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-xl transition-colors">Выбрать файлы</span>
                                    </div>
                                </label>
                            </div>

                        
                        <?php elseif($question->type === 'image_upload'): ?>
                            <div class="max-w-2xl">
                                <label class="block border-2 border-dashed border-blue-300 dark:border-blue-500/40 bg-blue-50/50 dark:bg-blue-500/10 rounded-2xl p-6 text-center hover:border-blue-400 transition-colors cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center mb-4">
                                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Загрузить изображения</p>
                                        <input type="file" name="answers[<?php echo e($question->id); ?>][]" multiple <?php if($question->is_required): echo 'required'; endif; ?> accept="image/*" class="hidden">
                                        <span class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-medium rounded-xl transition-colors">Выбрать изображения</span>
                                    </div>
                                </label>
                                <div x-data="{ previews: [] }" @change="previews = Array.from($event.target.files).map(file => ({ name: file.name, url: URL.createObjectURL(file) }))" class="mt-5 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3" x-show="previews.length > 0">
                                    <template x-for="(img, i) in previews" :key="i">
                                        <div class="relative aspect-square rounded-xl overflow-hidden border-2 border-gray-200 dark:border-white/10 shadow-sm">
                                            <img :src="img.url" class="w-full h-full object-cover">
                                            <span class="absolute bottom-0 left-0 right-0 bg-black/60 text-white text-[10px] py-1 px-2 truncate" x-text="img.name"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                        
                        <?php elseif($question->type === 'rating'): ?>
                            <div class="flex gap-2 justify-center">
                                <?php for($i = 1; $i <= ($question->max ?? 5); $i++): ?>
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="answers[<?php echo e($question->id); ?>]" value="<?php echo e($i); ?>" <?php if($question->is_required): echo 'required'; endif; ?> class="peer sr-only">
                                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl peer-checked:bg-gradient-to-br peer-checked:from-yellow-400 peer-checked:to-orange-400 peer-checked:text-white bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 transition-all duration-200 shadow-sm hover:shadow-md peer-checked:shadow-lg peer-checked:scale-110">★</div>
                                    </label>
                                <?php endfor; ?>
                            </div>

                        
                        <?php elseif($question->type === 'smiley_rating'): ?>
                            <div class="flex gap-3 sm:gap-4 justify-center">
                                <?php $__currentLoopData = ['😞','😟','😐','🙂','😊']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $emoji): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="answers[<?php echo e($question->id); ?>]" value="<?php echo e($idx + 1); ?>" <?php if($question->is_required): echo 'required'; endif; ?> class="peer sr-only">
                                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl peer-checked:bg-blue-100 dark:peer-checked:bg-blue-500/20 peer-checked:scale-110 bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 transition-all duration-200" x-text="'<?php echo e($emoji); ?>'"></div>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                        
                        <?php elseif($question->type === 'yesno'): ?>
                            <div class="flex gap-4 justify-center max-w-xs mx-auto">
                                <label class="flex-1">
                                    <input type="radio" name="answers[<?php echo e($question->id); ?>]" value="1" <?php if($question->is_required): echo 'required'; endif; ?> class="peer sr-only">
                                    <div class="py-4 rounded-2xl border-2 border-green-300 dark:border-green-500/30 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 font-semibold text-center peer-checked:bg-green-200 dark:peer-checked:bg-green-500/20 peer-checked:border-green-500 transition-all duration-200 hover:bg-green-100 dark:hover:bg-green-500/15">Да ✓</div>
                                </label>
                                <label class="flex-1">
                                    <input type="radio" name="answers[<?php echo e($question->id); ?>]" value="0" <?php if($question->is_required): echo 'required'; endif; ?> class="peer sr-only">
                                    <div class="py-4 rounded-2xl border-2 border-red-300 dark:border-red-500/30 bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 font-semibold text-center peer-checked:bg-red-200 dark:peer-checked:bg-red-500/20 peer-checked:border-red-500 transition-all duration-200 hover:bg-red-100 dark:hover:bg-red-500/15">Нет ✕</div>
                                </label>
                            </div>

                        
                        <?php elseif($question->type === 'dropdown'): ?>
                            <div class="max-w-2xl">
                                <select name="answers[<?php echo e($question->id); ?>]" <?php if($question->is_required): echo 'required'; endif; ?> class="w-full px-5 py-4 rounded-2xl border border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-[#121216] text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="" disabled selected>Выберите вариант...</option>
                                    <?php $__currentLoopData = (is_array($question->options) ? $question->options : []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($loop->index); ?>"><?php echo e($option); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                        
                        <?php elseif($question->type === 'slider'): ?>
                            <div class="max-w-2xl space-y-4">
                                <div class="flex items-center gap-4">
                                    <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($question->min ?? 0); ?></span>
                                    <input type="range" name="answers[<?php echo e($question->id); ?>]" min="<?php echo e($question->min ?? 0); ?>" max="<?php echo e($question->max ?? 100); ?>" <?php if($question->is_required): echo 'required'; endif; ?> class="flex-1 h-2 bg-gray-200 dark:bg-white/10 rounded-lg appearance-none cursor-pointer accent-blue-600" oninput="this.nextElementSibling.value = this.value">
                                    <span class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($question->max ?? 100); ?></span>
                                </div>
                                <output class="block text-center text-lg font-semibold text-blue-600 dark:text-blue-400"><?php echo e($question->min ?? 0); ?></output>
                            </div>

                        
                        <?php elseif($question->type === 'scale'): ?>
                            <div class="max-w-2xl space-y-4">
                                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span><?php echo e($question->left_label ?? 'Не согласен'); ?></span>
                                    <span><?php echo e($question->right_label ?? 'Согласен'); ?></span>
                                </div>
                                <div class="flex justify-center gap-2">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="answers[<?php echo e($question->id); ?>]" value="<?php echo e($i); ?>" <?php if($question->is_required): echo 'required'; endif; ?> class="peer sr-only">
                                            <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-white/10 peer-checked:bg-blue-600 transition-all"></div>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                            </div>

                        
                        <?php elseif($question->type === 'distribution_scale'): ?>
                            <div class="max-w-2xl space-y-4">
                                <div class="flex items-center gap-4">
                                    <span class="text-xs text-gray-400"><?php echo e($question->min ?? 0); ?>%</span>
                                    <input type="range" name="answers[<?php echo e($question->id); ?>]" min="<?php echo e($question->min ?? 0); ?>" max="<?php echo e($question->max ?? 100); ?>" <?php if($question->is_required): echo 'required'; endif; ?> class="flex-1 h-2 bg-gray-200 dark:bg-white/10 rounded-lg appearance-none cursor-pointer accent-blue-600">
                                    <span class="text-xs text-gray-400"><?php echo e($question->max ?? 100); ?>%</span>
                                </div>
                            </div>

                        
                        <?php elseif($question->type === 'semantic_differential'): ?>
                            <div class="max-w-2xl space-y-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-300"><?php echo e($question->left ?? 'Негативно'); ?></span>
                                    <span class="text-gray-600 dark:text-gray-300"><?php echo e($question->right ?? 'Позитивно'); ?></span>
                                </div>
                                <div class="flex justify-center gap-1">
                                    <?php for($i = 1; $i <= 7; $i++): ?>
                                        <label class="cursor-pointer">
                                            <input type="radio" name="answers[<?php echo e($question->id); ?>]" value="<?php echo e($i); ?>" <?php if($question->is_required): echo 'required'; endif; ?> class="peer sr-only">
                                            <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-white/10 peer-checked:bg-blue-600 transition-all"></div>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                            </div>

                        
                        <?php elseif(in_array($question->type, ['ranking', 'matrix'])): ?>
                            <div class="max-w-2xl text-center py-8 bg-gray-50 dark:bg-white/5 rounded-2xl border-2 border-dashed border-gray-200 dark:border-white/10">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Вопрос типа «<?php echo e($question->type); ?>» доступен для прохождения в упрощённом режиме</p>
                                <input type="text" name="answers[<?php echo e($question->id); ?>]" placeholder="Ваш комментарий (опционально)" class="mt-4 w-full max-w-md mx-auto px-4 py-3 rounded-xl border border-gray-300 dark:border-white/10 bg-gray-50 dark:bg-[#121216] text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        <?php endif; ?>

                        
                        <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200 dark:border-white/10">
                            <?php if($index > 0): ?>
                                <button type="button" @click="prevStep()" class="inline-flex items-center gap-2 px-5 py-3 border border-gray-300 dark:border-white/10 rounded-2xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-all text-sm font-medium">← Назад</button>
                            <?php else: ?>
                                <div></div>
                            <?php endif; ?>
                            <?php if($index < $poll->questions->count() - 1): ?>
                                <button type="button" @click="nextStep()" class="ml-auto px-7 py-3 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 text-white rounded-2xl font-semibold text-sm shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 transition-all">Далее →</button>
                            <?php else: ?>
                                <button type="submit" class="ml-auto px-7 py-3 bg-gradient-to-r from-green-600 to-emerald-500 hover:from-green-500 hover:to-emerald-400 text-white rounded-2xl font-semibold text-sm shadow-lg shadow-green-500/25 hover:shadow-green-500/40 transition-all">Отправить ответы ✓</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </form>

            
            <div class="mt-6 flex justify-center pb-8">
                <div class="flex items-center gap-2">
                    <?php for($i = 0; $i < $poll->questions->count(); $i++): ?>
                        <button type="button" @click="currentStep = <?php echo e($i); ?>" class="w-2.5 h-2.5 rounded-full transition-all" :class="currentStep === <?php echo e($i); ?> ? 'bg-blue-600 w-6' : 'bg-gray-300 dark:bg-white/20 hover:bg-gray-400'"></button>
                    <?php endfor; ?>
                </div>
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
<?php endif; ?><?php /**PATH C:\OSPanel\survey-app\resources\views/polls/take.blade.php ENDPATH**/ ?>