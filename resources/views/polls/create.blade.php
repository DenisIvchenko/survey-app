<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-[#1a1a20] rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm p-8 transition-colors duration-200">
            
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Создать новый опрос</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Заполните основную информацию. Вопросы можно будет добавить на следующем шаге.</p>

            <form method="POST" action="{{ route('polls.store') }}">
                @csrf

                <!-- Название -->
                <div class="mb-5">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Название опроса <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all">
                    @error('title') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Описание -->
                <div class="mb-5">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Описание</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full !bg-gray-50 dark:!bg-[#121216] border border-gray-300 dark:border-white/10 rounded-xl px-4 py-3 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all resize-none">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Статус -->
                <div class="mb-6 flex items-center gap-3">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-gray-300 dark:border-white/10 text-blue-600 focus:ring-blue-500 dark:focus:ring-offset-[#1a1a20]">
                    <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Сразу опубликовать опрос</label>
                </div>

                <!-- Кнопки -->
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('polls.index') }}" class="px-6 py-2.5 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors font-medium">
                        Отмена
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2.5 rounded-xl font-medium transition active:scale-[0.98] shadow-lg shadow-blue-500/20">
                        Создать опрос
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>