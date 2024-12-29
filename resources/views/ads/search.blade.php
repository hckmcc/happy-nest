{{-- resources/views/products/search.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Поиск товаров</h1>

        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <form action="{{ route('search') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Категория -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Категория</label>
                        <select name="category"
                                id="category"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Все категории</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Цена от -->
                    <div>
                        <label for="price_from" class="block text-sm font-medium text-gray-700 mb-1">Цена от</label>
                        <input type="number"
                               name="price_from"
                               id="price_from"
                               value="{{ request('price_from') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               min="0">
                    </div>

                    <!-- Цена до -->
                    <div>
                        <label for="price_to" class="block text-sm font-medium text-gray-700 mb-1">Цена до</label>
                        <input type="number"
                               name="price_to"
                               id="price_to"
                               value="{{ request('price_to') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               min="0">
                    </div>

                    <!-- Сортировка -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Сортировка</label>
                        <select name="sort"
                                id="sort"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Сначала новые</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Сначала старые</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>По цене (возрастание)</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>По цене (убывание)</option>
                        </select>
                    </div>

                    <!-- Кнопка поиска -->
                    <div class="col-span-full">
                        <button type="submit"
                                class="block text-center mt-3 px-6 py-3 bg-[var(--primary-color)] border border-[var(--primary-color)] text-white rounded-lg shadow-md hover:shadow-lg transition-colors">
                            Применить
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Результаты поиска -->
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

        <!-- Пагинация -->
        <div class="mt-8">
            {{ $ads->links() }}
        </div>
    </div>
@endsection
