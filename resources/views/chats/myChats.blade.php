@extends('layouts.app')

@section('content')
    <div class="container min-h-screen mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto ">
            <h1 class="text-2xl font-bold mb-6">Мои сообщения</h1>

            <div class="bg-white rounded-lg shadow" style="min-height: 60vh">
                @forelse($chats as $chat)
                    <div class="border-b last:border-0 hover:bg-gray-50">
                        <a href="{{ route('chat.show', ['ad' => $chat['ad_id'], 'user' => $chat['other_user']->id]) }}"
                           class="block p-4">
                            <div class="flex items-start gap-4">
                                <!-- Аватар собеседника -->
                                <div class="flex-shrink-0">
                                    @if($chat['other_user']->photo)
                                        <img src="{{ asset('storage/' . $chat['other_user']->photo) }}"
                                             alt="{{ $chat['other_user']->name }}"
                                             class="w-16 h-16 rounded-full object-cover">
                                    @else
                                        <div class="w-16 h-16 rounded-full bg-[var(--accent-color)] flex items-center justify-center">
                                        <span class="text-xl text-white">
                                            {{ substr($chat['other_user']->name, 0, 1) }}
                                        </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Информация о чате -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between mb-1">
                                        <h3 class="font-medium text-xl truncate">
                                            {{ $chat['other_user']->name }}
                                        </h3>
                                        <span class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($chat['last_message_date'])->diffForHumans() }}
                                    </span>
                                    </div>

                                    <!-- Информация об объявлении -->
                                    <div class="text-sm font-medium text-black-600 mb-1">
                                        Объявление: {{ $chat['ad']->name }}
                                    </div>

                                    <!-- Последнее сообщение -->
                                    <p class="text-sm text-gray-600 truncate">
                                        {{ $chat['last_message'] }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="flex flex-col p-8 text-center text-gray-500">
                        <p class="text-lg font-medium">У вас пока нет сообщений</p>
                        <p class="mt-2">Начните общение с продавцами, чтобы здесь появились диалоги</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
