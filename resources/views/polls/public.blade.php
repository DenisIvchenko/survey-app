<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Все опросы</h2>
            <a href="{{ route('polls.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">
                ← Мои опросы
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($polls as $poll)
                {{-- 🔹 ВСЯ КАРТОЧКА — КЛИКАБЕЛЬНАЯ ССЫЛКА --}}
                <a href="{{ route('polls.take', $poll) }}" 
                   class="block relative bg-white dark:bg-[#1a1a20] rounded-2xl border border-gray-200 dark:border-white/10 hover:shadow-2xl transition-all duration-300 overflow-hidden group">
                    
                    {{-- Зелёная шапка --}}
                    <div class="h-32 rounded-t-2xl relative z-10"
                         style="background: linear-gradient(135deg, #34d399 0%, #22c55e 50%, #10b981 100%);">
                        
                        {{-- Бейдж: Активный --}}
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 bg-white/95 dark:bg-[#1a1a20]/95 backdrop-blur-sm rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300 shadow-sm">
                                Активный
                            </span>
                        </div>
                        
                        {{-- Бейдж: Автор с аватаром --}}
                        <div class="absolute top-3 right-3 flex items-center gap-2 bg-white/95 dark:bg-[#1a1a20]/95 backdrop-blur-sm rounded-lg px-3 py-1.5 shadow-sm">
                            {{-- Аватар автора (с проверкой на фото) --}}
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold shadow-lg flex-shrink-0 overflow-hidden border-2 border-white dark:border-[#1a1a20]">
                                @if($poll->user->profile_photo_path)
                                    <img src="{{ asset('storage/' . $poll->user->profile_photo_path) }}" 
                                         class="w-full h-full object-cover"
                                         alt="{{ $poll->user->name }}">
                                @else
                                    @php
                                        $colors = ['from-purple-500 to-pink-500', 'from-blue-500 to-cyan-500', 'from-green-500 to-emerald-500', 'from-orange-500 to-red-500', 'from-indigo-500 to-purple-500'];
                                        $gradient = $colors[abs(crc32($poll->user->name)) % count($colors)];
                                    @endphp
                                    <span class="text-white bg-gradient-to-br {{ $gradient }} w-full h-full flex items-center justify-center">
                                        {{ strtoupper(substr($poll->user->name, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300 truncate max-w-[100px]">
                                {{ $poll->user->name }}
                            </span>
                        </div>
                    </div>
                    
                    {{-- Тело карточки --}}
                    <div class="p-5 bg-white dark:bg-[#1a1a20] relative z-10 rounded-b-2xl">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $poll->title }}
                        </h3>
                        
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                            {{ $poll->questions_count ?? 0 }} вопросов • {{ $poll->created_at->format('d.m.Y') }}
                        </p>
                        
                        <div class="pt-3 border-t border-gray-100 dark:border-white/5">
                            <span class="text-xs text-gray-400 dark:text-gray-500">Публичный опрос</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white dark:bg-[#1a1a20] border border-gray-200 dark:border-white/10 rounded-2xl p-12 text-center">
                    <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-2">Пока нет активных опросов</p>
                    <a href="{{ route('polls.create') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Создать первый опрос →</a>
                </div>
            @endforelse
        </div>
        
        <div class="mt-8">{{ $polls->links() }}</div>
    </div>
</x-app-layout>