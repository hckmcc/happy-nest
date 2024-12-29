@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow" style="min-height: 80vh">
            <!-- Шапка чата -->
            <div class="border-b p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Кнопка назад -->
                        <a href="{{ route('my_chats') }}" class="text-gray-600 hover:text-gray-800">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <!-- Информация о собеседнике -->
                        <div class="flex items-center space-x-3">
                            @if($otherUser->photo)
                                <img src="{{ asset('storage/' . $otherUser->photo) }}"
                                     alt="{{ $otherUser->name }}"
                                     class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-[var(--accent-color)] flex items-center justify-center">
                                    <span class="text-lg text-white">{{ substr($otherUser->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <h2 class="font-medium">{{ $otherUser->name }}</h2>
                                <p class="text-sm text-gray-500">{{ $ad->name }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ссылка на объявление -->
                    <a href="{{ route('ad.show', $ad) }}"
                       class="text-blue-600 hover:text-blue-800 text-sm">
                        Перейти к объявлению
                    </a>
                </div>
            </div>

            <!-- Контейнер сообщений с прокруткой -->
            <div class="flex-1 overflow-y-auto p-4" id="messages-container" style="min-height: 60vh">
                @foreach($messages as $message)
                    <div class="flex mb-4 {{ $message->message_author_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[70%] {{ $message->message_author_id === auth()->id() ? 'bg-[var(--accent-color)] text-white' : 'bg-[var(--primary-color)] text-white' }} rounded-lg px-4 py-2 break-words">
                            <div class="text-sm mb-1">
                                {{ $message->message }}
                            </div>
                            <div class="text-xs {{ $message->message_author_id === auth()->id() ? 'text-black-100' : 'text-black-500' }}">
                                {{ $message->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Форма отправки сообщения -->
            <div class="border-t p-4 h-full">
                <form action="{{ route('chats.add') }}" method="POST" class="flex space-x-2" id="message-form">
                    @csrf
                    <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                    <input type="hidden" name="message_author_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="seller_id" value="{{ $ad->user_id }}">
                    <input type="hidden" name="buyer_id" value="{{ auth()->id() === $ad->user_id ? $otherUser->id : auth()->id() }}">

                    <input type="text"
                           name="message"
                           class="flex-1 rounded-lg mr-2"
                           placeholder="Введите сообщение..."
                           required
                           autocomplete="off">

                    <button type="submit"
                            class="bg-[var(--primary-color)] text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Отправить
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('messages-container');

            // Прокрутка к последнему сообщению при загрузке
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // Обработка формы через AJAX
            const form = document.getElementById('message-form');
            const input = form.querySelector('input[name="message"]');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!input.value.trim()) return;

                const formData = new FormData(this);
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        // Создаем новый элемент сообщения
                        const messageDiv = document.createElement('div');
                        messageDiv.className = 'flex justify-end mb-4'; // Добавляем отступ снизу

                        const now = new Date();
                        const time = now.getHours().toString().padStart(2, '0') + ':' +
                            now.getMinutes().toString().padStart(2, '0');

                        messageDiv.innerHTML = `
                <div class="max-w-[70%] bg-[var(--accent-color)] text-white rounded-lg px-4 py-2 break-words">
                    <div class="text-sm mb-1">${data.message}</div>
                    <div class="text-xs text-black-100">${time}</div>
                </div>
            `;

                        // Добавляем сообщение прямо в контейнер
                        messagesContainer.appendChild(messageDiv);
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;

                        // Очищаем поле ввода и сбрасываем форму
                        form.reset();
                        input.focus();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Произошла ошибка при отправке сообщения');
                    })
                    .finally(() => {
                        submitButton.disabled = false;
                    });
            });

            // Добавляем обработчик Enter для отправки
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    form.dispatchEvent(new Event('submit'));
                }
            });
        });
    </script>

@endsection
