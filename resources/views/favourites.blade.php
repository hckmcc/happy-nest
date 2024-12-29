@extends('layouts.app')
@section('content')

    <div class="bg-white py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Избранные объявления</h2>

            <div class="adaptive-grid">
                @foreach($ads as $ad)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <!-- Изображение товара -->
                        <div class="relative h-44">
                            <img src="{{ asset('storage/' . $ad->photo) }}" alt="{{ $ad->name }}" class="w-full h-full object-cover">
                        </div>
                        <span class=" bg-[var(--accent-color)] text-white text-sm rounded-full" style="padding: 2px 4px; margin: 8px;">
                            {{ $ad->category ? $ad->category->name : 'Без категории' }}
                    </span>

                        <!-- Информация о товаре -->
                        <div class="px-4 py-1"> <!-- Увеличены отступы -->
                            <h3 class="text-base font-semibold text-gray-800 mb-3 line-clamp-2 min-h-[2.5rem]">
                                {{ $ad->name }}
                            </h3>

                            <div class="text-xl font-bold text-[var(--primary-color)] mb-3">
                                {{ number_format($ad->price, 0, ',', ' ') }} ₽
                            </div>

                            <!-- Местоположение и время -->
                            <div class="space-y-2 mb-3">
                                @if($ad->address)
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                        <span class="truncate">{{ $ad->address }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="far fa-clock mr-2"></i>
                                    <span>{{ $ad->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Футер карточки -->
                        <div class="px-4 py-3 bg-gray-50 border-t">
                            <!-- Кнопка подробнее на всю ширину -->
                            <a href="{{ route('ad.show', $ad) }}" class="block w-full text-center text-sm text-[var(--accent-color)] hover:bg-gray-100 rounded py-1">
                                Подробнее
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
