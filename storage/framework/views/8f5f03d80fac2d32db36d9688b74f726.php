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
    <?php
        // Градиент для аватара по умолчанию
        $colors = [
            'from-purple-500 to-pink-500',
            'from-blue-500 to-cyan-500', 
            'from-green-500 to-emerald-500',
            'from-orange-500 to-red-500',
            'from-indigo-500 to-purple-500',
            'from-pink-500 to-rose-500',
        ];
        $gradient = $colors[abs(crc32($user->name)) % count($colors)];
    ?>
    
    <!-- Единый контейнер -->
    <div class="max-w-4xl mx-auto bg-white dark:bg-[#1a1a20] rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm transition-colors duration-300 overflow-hidden">
        
        <!-- 1. Информация профиля + Фото -->
        <div class="p-8 border-b border-gray-200 dark:border-white/5">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Информация профиля</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Обновите имя, email и фото вашего аккаунта.</p>
            
            <form method="POST" action="<?php echo e(route('profile.update')); ?>" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-8 items-start">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                
                <!-- Левая колонка: Фото -->
                <div class="flex-shrink-0 w-full md:w-auto flex flex-col items-center md:items-start">
                    <label class="relative group cursor-pointer mb-3">
                        <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-gray-100 dark:border-white/10 shadow-md transition-all group-hover:opacity-90 bg-gray-200 dark:bg-gray-700">
                            
                            <?php if($user->profile_photo_path): ?>
                                <!-- Фото из БД -->
                                <img src="<?php echo e(asset('storage/' . $user->profile_photo_path)); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <!-- Базовый аватар с градиентом -->
                                <div class="w-full h-full flex items-center justify-center text-white text-xl font-bold bg-gradient-to-br <?php echo e($gradient); ?>">
                                    <?php echo e(strtoupper(substr($user->name, 0, 2))); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Иконка камеры при наведении -->
                        <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        
                        <input type="file" name="profile_photo" accept="image/*" class="hidden">
                    </label>
                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center md:text-left mt-2">JPG, PNG, GIF до 2 МБ</p>
                </div>

                <!-- Правая колонка: Поля -->
                <div class="flex-1 w-full max-w-lg">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Имя</label>
                        <input id="name" name="name" type="text" value="<?php echo e(old('name', $user->name)); ?>" required 
                               class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-500 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                        <input id="email" name="email" type="email" value="<?php echo e(old('email', $user->email)); ?>" required 
                               class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-500 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2.5 rounded-xl font-medium transition active:scale-[0.98] shadow-lg shadow-blue-500/20">
                            Сохранить изменения
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 2. Обновление пароля -->
        <div class="p-8 border-b border-gray-200 dark:border-white/5">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Обновление пароля</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Используйте длинный случайный пароль для безопасности аккаунта.</p>
            
            <form method="POST" action="<?php echo e(route('password.update')); ?>" class="max-w-lg">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Текущий пароль</label>
                    <input id="current_password" name="current_password" type="password" required 
                           class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-500 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Новый пароль</label>
                    <input id="password" name="password" type="password" required 
                           class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-500 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Подтвердите пароль</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2.5 rounded-xl font-medium transition active:scale-[0.98] shadow-lg shadow-blue-500/20">
                        Обновить пароль
                    </button>
                </div>
            </form>
        </div>

        <!-- 3. Удаление аккаунта -->
        <div class="p-8" x-data="{ showDelete: false }">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Удаление аккаунта</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">После удаления все данные будут безвозвратно утеряны.</p>
            
            <button @click="showDelete = true" class="bg-red-600 hover:bg-red-500 text-white px-6 py-2.5 rounded-xl font-medium transition active:scale-[0.98] shadow-lg shadow-red-500/20">
                Удалить аккаунт
            </button>

            <form x-show="showDelete" x-transition method="POST" action="<?php echo e(route('profile.destroy')); ?>" class="mt-6 max-w-lg p-5 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl space-y-4">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <p class="text-sm text-red-600 dark:text-red-300 font-medium">Введите пароль для подтверждения:</p>
                <input type="password" name="password" required placeholder="Ваш текущий пароль" 
                       class="w-full !bg-white dark:!bg-[#121216] border border-red-200 dark:border-red-500/30 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500 transition-all">
                <?php $__errorArgs = ['password', 'userDeletion'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-sm text-red-500 dark:text-red-400"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="bg-red-600 hover:bg-red-500 text-white px-5 py-2 rounded-lg text-sm font-medium transition">Подтвердить удаление</button>
                    <button type="button" @click="showDelete = false" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white px-5 py-2 rounded-lg text-sm transition">Отмена</button>
                </div>
            </form>
        </div>

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
<?php endif; ?><?php /**PATH C:\OSPanel\survey-app\resources\views/profile/edit.blade.php ENDPATH**/ ?>