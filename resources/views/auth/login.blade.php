<x-guest-layout>
    <!-- Иконка -->
    <div class="flex justify-center mb-6">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
    </div>

    <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-2">Добро пожаловать</h2>
    <p class="text-center text-gray-500 dark:text-gray-400 mb-8">Войдите, чтобы создавать и проходить опросы</p>

    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-xl text-sm text-green-700 dark:text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-5">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
            @error('email') <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Пароль</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
            @error('password') <p class="mt-1 text-sm text-red-500 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center mb-6">
            <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-white/10 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-offset-[#1a1a20]" name="remember">
            <label for="remember_me" class="ml-2 text-sm text-gray-600 dark:text-gray-400">Запомнить меня</label>
        </div>

        <!-- Button -->
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white py-3 rounded-xl font-medium transition active:scale-[0.98] shadow-lg shadow-blue-500/20">
            Войти в систему
        </button>
    </form>

    <!-- Register Link -->
    <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
        Нет аккаунта? 
        <a href="{{ route('register') }}" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors">Зарегистрироваться</a>
    </div>
</x-guest-layout>