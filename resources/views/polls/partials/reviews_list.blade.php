@php
    /** @var \App\Models\Poll $poll */
    /** @var \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection $reviews */
@endphp

<div class="space-y-4"
     x-data="{
        votingReviewId: null,
        voteFeedback: null,
        voteError: null,
        async castVote(reviewId, voteType) {
            this.votingReviewId = reviewId;
            this.voteFeedback = null;
            this.voteError = null;
            try {
                const response = await fetch(`{{ url('/reviews') }}/${reviewId}/vote`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ vote_type: voteType }),
                });
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Не удалось отправить голос');
                }
                this.voteFeedback = data.message;
            } catch (error) {
                this.voteError = error.message;
            } finally {
                this.votingReviewId = null;
            }
        },
     }">

    <div x-show="voteFeedback"
         x-cloak
         x-transition
         class="p-4 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 rounded-xl text-sm text-green-700 dark:text-green-400"
         x-text="voteFeedback"></div>

    <div x-show="voteError"
         x-cloak
         x-transition
         class="p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl text-sm text-red-700 dark:text-red-400"
         x-text="voteError"></div>

    @forelse ($reviews as $review)
        <article class="bg-white dark:bg-[#1a1a20] rounded-2xl border border-gray-200 dark:border-white/10 p-5 sm:p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                <div class="flex flex-wrap items-center gap-2 min-w-0">
                    <span class="font-semibold text-gray-900 dark:text-white truncate">
                        {{ $review->user?->name ?? 'Аноним' }}
                    </span>
                    @if ($review->user)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-500/20 dark:text-indigo-300"
                              title="Репутация">
                            ★ {{ number_format((int) $review->user->reputation) }}
                        </span>
                    @endif
                </div>
                <time class="text-xs text-gray-500 dark:text-gray-400 shrink-0"
                      datetime="{{ $review->created_at->toIso8601String() }}">
                    {{ $review->created_at->diffForHumans() }}
                </time>
            </div>

            <div class="mb-3">
                <x-rating-display :rating="$review->rating" size="sm" />
            </div>

            <p class="text-gray-700 dark:text-gray-300 text-sm sm:text-base leading-relaxed whitespace-pre-wrap break-words">
                {{ $review->comment }}
            </p>

            @auth
                @can('vote', $review)
                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-white/5 flex flex-wrap items-center gap-2">
                        <button type="button"
                                @click="castVote({{ $review->id }}, 'useful')"
                                :disabled="votingReviewId === {{ $review->id }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 hover:bg-emerald-100 dark:hover:bg-emerald-500/20 border border-emerald-200 dark:border-emerald-500/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                            </svg>
                            Полезно
                        </button>
                        <button type="button"
                                @click="castVote({{ $review->id }}, 'useless')"
                                :disabled="votingReviewId === {{ $review->id }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 border border-gray-200 dark:border-white/10 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.737 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"></path>
                            </svg>
                            Бесполезно
                        </button>
                    </div>
                @endcan
            @endauth
        </article>
    @empty
        <div class="bg-white dark:bg-[#1a1a20] border border-gray-200 dark:border-white/10 rounded-2xl p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-lg font-medium">Отзывов пока нет</p>
            <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Станьте первым, кто оставит отзыв об этом опросе.</p>
        </div>
    @endforelse
</div>
