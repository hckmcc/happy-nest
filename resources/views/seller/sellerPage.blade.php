@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Профиль продавца -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
            <div class="md:flex">
                <!-- Аватар и основная информация -->
                <div class="md:w-1/3 p-6 bg-gray-50">
                    <div class="text-center">
                        @if($seller->photo)
                            <img src="{{ asset('storage/' . $seller->photo) }}"
                                 alt="Аватар {{ $seller->name }}"
                                 class="w-16 h-16 rounded-full mx-auto mb-4 object-cover">
                        @else
                            <div class="w-16 h-16 rounded-full mx-auto mb-4 bg-[var(--accent-color)] flex items-center justify-center">
                                <span class="text-3xl text-white font-medium">{{ substr($seller->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h1 class="text-2xl font-bold mb-2">{{ $seller->name }}</h1>
                        <p class="text-gray-600">На сайте с {{ $seller->created_at->format('d.m.Y') }}</p>
                    </div>

                    <div class="mt-6 space-y-3">
                        @if($seller->phone)
                            <div class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span>{{ $seller->phone }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Статистика -->
                    <div class="mt-6 grid grid-cols-2 gap-4 text-center">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="text-2xl font-bold text-blue-600">{{ $seller->ads_count }}</div>
                            <div class="text-sm text-gray-600">Объявлений</div>
                        </div>
                        <div class="items-center bg-white p-4 rounded-lg shadow">
                            <div class="flex items-center">
                                <span class="text-2xl font-bold">{{ $seller->rating }}</span>
                                <div class="flex text-yellow-400 ml-1" style="color: gold">
                                    @for ($i = 0; $i < 5; $i++)
                                        @if ($i < floor($seller->rating))
                                            <span class="text-yellow-400">★</span>
                                        @else
                                            <span class="text-gray-300">★</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <div class="text-sm text-gray-600">Средняя оценка</div>
                        </div>
                    </div>
                    @if(auth()->check() && auth()->id() !== $seller->id)
                    <button
                        type="button"
                        onclick="document.getElementById('review-modal').showModal()"
                        class="block w-full text-center mt-3 px-6 py-3 border border-[var(--primary-color)] text-[var(--primary-color)] rounded-lg shadow-md hover:shadow-lg transition-colors"
                    >
                        Оставить отзыв
                    </button>
                    @endif
                </div>

                <!-- Описание и дополнительная информация -->
                <div class="md:w-2/3 p-6">
                    <!-- Отзывы -->
                    @if($seller->reviews_count > 0)
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold mb-3">Отзывы ({{ $seller->reviews_count }})</h2>
                            <div class="space-y-4">
                                @foreach($reviews as $review)
                                    <div class="border-b pb-4 mb-4">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <div class="font-medium">{{ $review->buyer->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $review->created_at->format('d.m.Y') }}</div>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="text-yellow-400" style="color: gold">★</span>
                                                <span class="ml-1">{{ $review->rate }}</span>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-gray-700">{{ $review->text }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Активные объявления -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-6">Объявления продавца</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($ads as $ad)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        @if($ad->photo)
                            <img src="{{ asset('storage/' . $ad->photo) }}"
                                 alt="{{ $ad->name }}"
                                 class="w-full h-48 object-cover">
                        @endif
                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-2">{{ $ad->name }}</h3>
                            <p class="text-gray-600 mb-2">{{ Str::limit($ad->description, 100) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-blue-600">{{ number_format($ad->price, 0, '.', ' ') }} ₽</span>
                                <a href="{{ route('ad.show', $ad) }}"
                                   class="text-blue-600 hover:text-blue-800">Подробнее</a>
                            </div>
                            <div class="text-sm text-gray-500 mt-2">
                                {{ $ad->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-500">У продавца пока нет активных объявлений</p>
                    </div>
                @endforelse
            </div>

            <!-- Пагинация -->
            @if($ads->hasPages())
                <div class="mt-6">
                    {{ $ads->links() }}
                </div>
            @endif
        </div>
    </div>
    <!-- Модальное окно для отзыва -->
    <dialog id="review-modal" class="w-full max-w-2xl bg-white p-6 rounded-lg">
        <div class="w-full max-w-3xl bg-white p-6 rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Оставить отзыв</h3>
                <button
                    onclick="document.getElementById('review-modal').close()"
                    class="text-gray-500 hover:text-gray-700"
                >
                    ✕
                </button>
            </div>

            <form action="{{ route('reviews.add') }}" method="POST">
                @csrf
                <input type="hidden" name="seller_id" value="{{ $seller->id }}">

                <!-- Выбор объявления -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Выберите объявление
                    </label>
                    <select name="ad_id" required class="w-full rounded border-gray-300">
                        <option value="">Выберите объявление</option>
                        @foreach($seller->ads as $ad)
                            <option value="{{ $ad->id }}">{{ $ad->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Рейтинг -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Оценка
                    </label>
                    <div class="flex gap-4">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex items-center">
                                <input type="radio" name="rate" value="{{ $i }}" required>
                                <span class="ml-1">{{ $i }}</span>
                            </label>
                        @endfor
                    </div>
                </div>

                <!-- Комментарий -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Комментарий
                    </label>
                    <textarea
                        name="text"
                        required
                        rows="4"
                        class="w-full rounded border-gray-300"
                        placeholder="Напишите ваш отзыв"
                    ></textarea>
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="block w-full text-center px-6 py-3 border border-[var(--primary-color)] text-[var(--primary-color)] rounded-lg shadow-md hover:shadow-lg transition-colors"
                    >
                        Отправить
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <style>
        dialog::backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
@endsection
