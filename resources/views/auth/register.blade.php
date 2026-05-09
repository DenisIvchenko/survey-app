<x-guest-layout>
    <!-- Иконка -->
    <div class="flex justify-center mb-6">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
        </div>
    </div>

    <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-2">Создать аккаунт</h2>
    <p class="text-center text-gray-500 dark:text-gray-400 mb-8">Заполните форму для регистрации в системе опросов</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-5">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Имя</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
            @error('name') <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
            @error('email') <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div class="mb-5">
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Пароль</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
            @error('password') <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Подтвердите пароль</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
            @error('password_confirmation') <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <!-- Button -->
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white py-3 rounded-xl font-medium transition active:scale-[0.98] shadow-lg shadow-blue-500/20">
            Зарегистрироваться
        </button>
    </form>

    <!-- Login Link -->
    <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
        Уже есть аккаунт? 
        <a href="{{ route('login') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors">Войти в систему</a>
    </div>
</x-guest-layout>