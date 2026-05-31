<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Заголовок --}}
        <div class="mb-8">
            <a href="{{ route('polls.index') }}" class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:underline mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Назад к опросам
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $poll->title }}</h1>
            @if($poll->description)<p class="text-gray-600 dark:text-gray-400 mt-2">{{ $poll->description }}</p>@endif
        </div>

        {{-- Сводная статистика --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white dark:bg-[#1a1a20] rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                    {{ $poll->questions->sum('total_votes') }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Всего ответов</div>
            </div>
            <div class="bg-white dark:bg-[#1a1a20] rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $poll->questions->count() }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Вопросов</div>
            </div>
            <div class="bg-white dark:bg-[#1a1a20] rounded-2xl p-6 border border-gray-200 dark:border-white/10">
                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $poll->is_active ? 'Активен' : 'Черновик' }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Статус</div>
            </div>
        </div>

        {{-- Вопросы и ответы --}}
        @forelse($poll->questions as $question)
            <div class="bg-white dark:bg-[#1a1a20] rounded-2xl p-6 mb-6 border border-gray-200 dark:border-white/10">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ $loop->iteration }}. {{ $question->text }}
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">
                        ({{ $question->total_votes ?? 0 }} ответов)
                    </span>
                </h3>
                
                @if(($question->stats ?? collect())->count() > 0)
                    <div class="space-y-3">
                        @foreach($question->stats as $stat)
                            @php
                                $percent = $question->total_votes > 0 
                                    ? round(($stat['count'] / $question->total_votes) * 100) 
                                    : 0;
                            @endphp
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-white/5 rounded-xl">
                                <span class="text-gray-700 dark:text-gray-300">{{ $stat['text'] }}</span>
                                <div class="flex items-center gap-3">
                                    <div class="w-32 bg-gray-200 dark:bg-white/10 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" 
                                             style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400 w-10 text-right">
                                        {{ $stat['count'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">Нет ответов</p>
                @endif
            </div>
        @empty
            <div class="text-center py-12 bg-white dark:bg-[#1a1a20] rounded-2xl border border-gray-200 dark:border-white/10">
                <p class="text-gray-500 dark:text-gray-400">В опросе нет вопросов.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>