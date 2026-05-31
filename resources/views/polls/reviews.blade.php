<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('polls.index') }}"
               class="inline-flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 mb-4 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Назад к опросам
            </a>

            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0 flex-1">
                    @if ($poll->user_id === auth()->id())
                        <a href="{{ route('polls.show', $poll) }}"
                           class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors break-words">
                            {{ $poll->title }}
                        </a>
                    @else
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white break-words">
                            {{ $poll->title }}
                        </h1>
                    @endif
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Отзывы участников
                        @if ($poll->surveyRating?->total_reviews)
                            @php
                                $reviewCount = (int) $poll->surveyRating->total_reviews;
                                $reviewWord = ($reviewCount % 10 === 1 && $reviewCount % 100 !== 11)
                                    ? 'отзыв'
                                    : (($reviewCount % 10 >= 2 && $reviewCount % 10 <= 4 && ($reviewCount % 100 < 10 || $reviewCount % 100 >= 20))
                                        ? 'отзыва'
                                        : 'отзывов');
                            @endphp
                            <span class="text-gray-400 dark:text-gray-500">·</span>
                            {{ $reviewCount }} {{ $reviewWord }}
                        @endif
                    </p>
                </div>

                <div class="flex flex-col items-end gap-2 shrink-0">
                    <div class="flex items-center gap-2 px-4 py-2 bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/20 rounded-xl">
                        <x-rating-display :rating="$averageRating" size="md" />
                        <span class="text-lg font-bold text-yellow-800 dark:text-yellow-300">
                            {{ number_format((float) $averageRating, 1) }}
                        </span>
                    </div>
                    @if ((float) $averageRating <= 0)
                        <span class="text-xs text-gray-500 dark:text-gray-400">Средняя оценка появится после первых отзывов</span>
                    @endif
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-xl text-sm text-green-700 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        {{-- Reviews list (SSR + optional AJAX refresh) --}}
        <div x-data="reviewsFragmentLoader('{{ route('polls.reviews.fragment', $poll) }}')"
             class="relative">
            <div x-show="loading"
                 x-cloak
                 class="absolute inset-0 z-10 flex items-center justify-center bg-white/60 dark:bg-[#0b0b0f]/60 backdrop-blur-sm rounded-2xl">
                <svg class="animate-spin w-8 h-8 text-blue-600"
                     xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <div id="reviews-list-container">
                @include('polls.partials.reviews_list', ['poll' => $poll, 'reviews' => $reviews])
            </div>

            @if ($reviews->hasPages())
                <div class="mt-8 flex justify-center" @click="onPaginationClick($event)">
                    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-1">
                        @if ($reviews->onFirstPage())
                            <span class="px-3 py-2 text-sm text-gray-400 dark:text-gray-600 rounded-lg cursor-not-allowed">←</span>
                        @else
                            <a href="{{ $reviews->previousPageUrl() }}"
                               class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-[#1a1a20] border border-gray-200 dark:border-white/10 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                ←
                            </a>
                        @endif

                        @foreach ($reviews->getUrlRange(max(1, $reviews->currentPage() - 2), min($reviews->lastPage(), $reviews->currentPage() + 2)) as $page => $url)
                            @if ($page == $reviews->currentPage())
                                <span class="px-3 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg shadow-sm shadow-blue-500/30"
                                      aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                   class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-[#1a1a20] border border-gray-200 dark:border-white/10 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if ($reviews->hasMorePages())
                            <a href="{{ $reviews->nextPageUrl() }}"
                               class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-[#1a1a20] border border-gray-200 dark:border-white/10 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                →
                            </a>
                        @else
                            <span class="px-3 py-2 text-sm text-gray-400 dark:text-gray-600 rounded-lg cursor-not-allowed">→</span>
                        @endif
                    </nav>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
