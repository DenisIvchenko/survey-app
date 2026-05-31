<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Система опросов')); ?></title>
    
    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gray-50 dark:bg-[#0b0b0f] text-gray-900 dark:text-gray-100 font-sans antialiased">

    <div x-data="themeSwitcher" x-init="init()" x-cloak class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-72 bg-white dark:bg-[#121216] border-r border-gray-200 dark:border-white/5 flex flex-col hidden md:flex" x-data="{ profileMenuOpen: false }">
            <div class="h-20 flex items-center px-8 border-b border-gray-200 dark:border-white/5">
                <div class="flex items-center gap-3 text-blue-600 dark:text-blue-500 font-bold text-2xl">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <span>Опросник</span>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                
                <a href="<?php echo e(route('polls.index')); ?>" class="group flex items-center px-4 py-3.5 text-base font-medium rounded-xl <?php echo e(request()->routeIs('polls.index') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                    <svg class="w-6 h-6 mr-4 <?php echo e(request()->routeIs('polls.index') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Мои опросы
                </a>

                
                <a href="<?php echo e(route('polls.public')); ?>" class="group flex items-center px-4 py-3.5 text-base font-medium rounded-xl <?php echo e(request()->routeIs('polls.public') ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white'); ?>">
                    <svg class="w-6 h-6 mr-4 <?php echo e(request()->routeIs('polls.public') ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Все опросы
                </a>

                <a href="#" class="group flex items-center px-4 py-3.5 text-base font-medium rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-all">
                    <svg class="w-6 h-6 mr-4 text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    Архив
                </a>
            </nav>

            <div class="p-5 border-t border-gray-200 dark:border-white/5 relative">
                <button @click="profileMenuOpen = !profileMenuOpen" class="flex items-center gap-4 px-2 w-full hover:bg-gray-100 dark:hover:bg-white/5 rounded-xl p-2 -m-2 transition-colors text-left">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center text-sm font-bold shadow-lg flex-shrink-0 overflow-hidden border-2 border-gray-200 dark:border-white/10">
                        <?php if(Auth::user()->profile_photo_path): ?>
                            <img src="<?php echo e(asset('storage/' . Auth::user()->profile_photo_path)); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <?php
                                $colors = ['from-purple-500 to-pink-500', 'from-blue-500 to-cyan-500', 'from-green-500 to-emerald-500', 'from-orange-500 to-red-500', 'from-indigo-500 to-purple-500'];
                                $gradient = $colors[abs(crc32(Auth::user()->name)) % count($colors)];
                            ?>
                            <span class="text-white bg-gradient-to-br <?php echo e($gradient); ?> w-full h-full flex items-center justify-center"><?php echo e(strtoupper(substr(Auth::user()->name, 0, 2))); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-base font-semibold text-gray-900 dark:text-white truncate"><?php echo e(Auth::user()->name); ?></p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate"><?php echo e(Auth::user()->email); ?></p>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0" :class="{ 'rotate-180': profileMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                </button>

                <div x-show="profileMenuOpen" x-cloak @click.away="profileMenuOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute bottom-full left-5 right-5 mb-3 bg-white dark:bg-[#1a1a20] border border-gray-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden z-50">
                    <a href="<?php echo e(route('profile.edit')); ?>" class="flex items-center gap-3 px-5 py-3.5 text-base text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 hover:text-gray-900 dark:hover:text-white transition-colors border-b border-gray-200 dark:border-white/10">
                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Настройки профиля
                    </a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="flex items-center gap-3 px-5 py-3.5 w-full text-left text-red-500 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-700 dark:hover:text-red-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Выйти из аккаунта
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden relative">
            <div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none">
                <div class="absolute -top-32 -right-32 w-[500px] h-[500px] bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 -left-32 w-[400px] h-[400px] bg-purple-500/10 dark:bg-purple-500/5 rounded-full blur-3xl"></div>
            </div>

            <header class="h-20 bg-white/70 dark:bg-[#0b0b0f]/70 backdrop-blur-xl border-b border-gray-200/50 dark:border-white/5 flex items-center justify-end px-8 sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <button @click="toggleTheme()" class="p-3 rounded-xl hover:bg-gray-100 dark:hover:bg-white/10 transition-colors text-gray-500 dark:text-gray-400">
                        <svg x-show="isDark" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <svg x-show="!isDark" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-10">
                <?php echo e($slot); ?>

            </main>
        </div>
    </div>
</body>
</html><?php /**PATH E:\OSPanel\survey-app\resources\views/layouts/app.blade.php ENDPATH**/ ?>