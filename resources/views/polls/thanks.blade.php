<x-guest-layout>
    {{-- Выход из ограничений guest-layout для широкой карточки --}}
    <style>
        body { align-items: initial !important; justify-content: initial !important; overflow-y: auto !important; padding: 0 !important; }
        body > div[class*="max-w-"] { max-width: none !important; width: 100% !important; padding: 0 !important; border: none !important; box-shadow: none !important; background: transparent !important; margin: 0 !important; }
        .absolute[class*="blur-"] { display: none !important; }
    </style>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-[#0b0b0f] dark:to-[#121218] flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-2xl bg-white/80 dark:bg-[#1a1a20]/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-gray-200/50 dark:border-white/10 p-8 sm:p-12">
            {{-- Thank you block --}}
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
                    {{ $poll->title }}
                </p>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-xl text-sm text-green-700 dark:text-green-400 text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-xl text-sm text-blue-700 dark:text-blue-400 text-center">
                    {{ session('info') }}
                </div>
            @endif

            {{-- Average rating summary --}}
            @if ($averageRating > 0)
                <div class="mb-8 flex flex-col sm:flex-row items-center justify-center gap-3 p-4 bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/20 rounded-2xl">
                    <x-rating-display :rating="$averageRating" size="lg" />
                    <div class="text-center sm:text-left">
                        <p class="text-sm text-yellow-800/80 dark:text-yellow-300/80">Средняя оценка опроса</p>
                        <p class="text-2xl font-bold text-yellow-800 dark:text-yellow-300">
                            {{ number_format((float) $averageRating, 1) }} <span class="text-base font-normal text-yellow-700/70 dark:text-yellow-400/70">/ 5</span>
                        </p>
                    </div>
                </div>
            @endif

            @auth
                @if ($canReview)
                    <div class="border-t border-gray-200 dark:border-white/10 pt-8 mb-8"
                         x-data="{
                            rating: {{ (int) old('rating', 0) }},
                            hover: 0,
                            comment: @js(old('comment', '')),
                            maxLength: 1500,
                         }">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1 text-center sm:text-left">
                            Оставить отзыв
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 text-center sm:text-left">
                            Поделитесь впечатлением об опросе — это поможет другим участникам.
                        </p>

                        <form method="POST" action="{{ route('reviews.store') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="survey_id" value="{{ $poll->id }}">
                            <input type="hidden" name="rating" :value="rating">

                            {{-- Star rating --}}
                            <div>
                                <x-input-label value="Ваша оценка" class="dark:text-gray-300" />
                                <div class="mt-2 flex items-center gap-1"
                                     role="group"
                                     aria-label="Выберите оценку от 1 до 5">
                                    @for ($star = 1; $star <= 5; $star++)
                                        <button type="button"
                                                @click="rating = {{ $star }}"
                                                @mouseenter="hover = {{ $star }}"
                                                @mouseleave="hover = 0"
                                                class="p-1 rounded-lg transition-transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-yellow-400/50"
                                                aria-label="{{ $star }} из 5">
                                            <svg class="w-8 h-8 transition-colors"
                                                 :class="(hover >= {{ $star }} || rating >= {{ $star }}) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'"
                                                 viewBox="0 0 20 20"
                                                 fill="currentColor"
                                                 aria-hidden="true">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        </button>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400" x-show="rating > 0" x-text="rating + ' / 5'"></span>
                                </div>
                                <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                            </div>

                            {{-- Comment --}}
                            <div>
                                <x-input-label for="comment" value="Комментарий" class="dark:text-gray-300" />
                                <textarea id="comment"
                                          name="comment"
                                          rows="4"
                                          maxlength="1500"
                                          x-model="comment"
                                          required
                                          placeholder="Опишите ваш опыт (минимум 15 символов)..."
                                          class="mt-2 block w-full border-gray-300 dark:border-white/10 dark:bg-[#121216] dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('comment') }}</textarea>
                                <div class="mt-1 flex justify-between items-center">
                                    <x-input-error :messages="$errors->get('comment')" />
                                    <span class="text-xs text-gray-400 dark:text-gray-500 ml-auto"
                                          :class="comment.length > maxLength ? 'text-red-500' : ''"
                                          x-text="comment.length + ' / ' + maxLength"></span>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <x-primary-button class="!bg-blue-600 hover:!bg-blue-500 !normal-case !tracking-normal !text-sm !px-6 !py-3 !rounded-xl shadow-lg shadow-blue-500/30">
                                    Отправить отзыв
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                @endif
            @endauth

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2 border-t border-gray-200 dark:border-white/10">
                <a href="{{ route('polls.reviews', $poll) }}"
                   class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">
                    Смотреть все отзывы об опросе →
                </a>
                <a href="{{ route('dashboard') }}"
                   class="inline-block px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-semibold transition-all shadow-lg shadow-blue-500/25 text-center">
                    Вернуться на главную
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
