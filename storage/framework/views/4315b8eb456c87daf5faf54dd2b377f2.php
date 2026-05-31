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
<body class="bg-gray-50 dark:bg-[#0b0b0f] text-gray-900 dark:text-gray-100 font-sans antialiased min-h-screen flex flex-col items-center justify-center relative overflow-hidden transition-colors duration-200">
    
    <!-- Фоновые декорации -->
    <div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -left-24 w-72 h-72 bg-purple-500/10 dark:bg-purple-500/5 rounded-full blur-3xl"></div>
    </div>

    <!-- Кнопка смены темы -->
    <div class="absolute top-6 right-6 z-20" x-data="{ 
            isDark: localStorage.getItem('theme') !== 'light',
            toggleTheme() {
                this.isDark = !this.isDark;
                localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                document.documentElement.classList.toggle('dark', this.isDark);
            }
        }">
        <button @click="toggleTheme()" class="p-3 rounded-xl bg-white dark:bg-[#1a1a20] border border-gray-200 dark:border-white/10 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/5 transition-all shadow-lg">
            <svg x-show="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
        </button>
    </div>

    <!-- Основная карточка -->
    <div class="w-full max-w-md p-8 bg-white dark:bg-[#1a1a20] border border-gray-200 dark:border-white/10 rounded-2xl shadow-2xl transition-colors duration-200">
        <?php echo e($slot); ?>

    </div>
</body>
</html><?php /**PATH C:\OSPanel\survey-app\resources\views/layouts/guest.blade.php ENDPATH**/ ?>