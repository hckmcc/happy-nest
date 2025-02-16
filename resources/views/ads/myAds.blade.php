@extends('layouts.app')
@section('content')
    <div class="bg-white min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Заголовок -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900">Мои объявления</h1>
                <p class="mt-2 text-sm text-gray-600">Управляйте своими объявлениями</p>
            </div>

            <!-- Табы -->
            <div x-data="{ activeTab: 'active' }">
                <!-- Кнопки табов -->
                <div class="border-b border-gray-200">
                    <div class="flex space-x-8">
                        <button @click="activeTab = 'active'"
                                class="pb-4 px-4 relative"
                                :class="{
                                'text-[var(--primary-color)] font-medium': activeTab === 'active',
                                'text-gray-500 hover:text-gray-700': activeTab !== 'active'
                            }">
                            Активные
                            <span class="text-sm bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                            {{ $activeAds->count() }}
                            </span>
                            <!-- Индикатор активного таба -->
                            <div class="absolute bottom-0 left-0 right-0 h-0.5"
                                 :class="{ 'bg-[var(--primary-color)]': activeTab === 'active' }">
                            </div>
                        </button>

                        <button @click="activeTab = 'completed'"
                                class="pb-4 px-1 relative"
                                :class="{
                                'text-[var(--primary-color)] font-medium': activeTab === 'completed',
                                'text-gray-500 hover:text-gray-700': activeTab !== 'completed'
                            }">
                            Завершенные
                            <span class="text-sm bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                            {{ $completedAds->count() }}
                            </span>
                            <!-- Индикатор активного таба -->
                            <div class="absolute bottom-0 left-0 right-0 h-0.5"
                                 :class="{ 'bg-[var(--primary-color)]': activeTab === 'completed' }">
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Содержимое табов -->
                <div class="mt-6">
                    <!-- Активные объявления -->
                    <div x-show="activeTab === 'active'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100">
                        @if($activeAds->count() > 0)
                            <div class="adaptive-grid">
                                @foreach($activeAds as $ad)
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 {{ $ad->is_completed ? 'opacity-75' : '' }}">
                                        <!-- Изображение товара -->
                                        <div class="relative h-44">
                                            <img src="{{ asset('storage/' . $ad->photo) }}"
                                                 alt="{{ $ad->name }}"
                                                 class="w-full h-full object-cover {{ $ad->is_completed ? 'grayscale' : '' }}">
                                        </div>
                                        <span class=" bg-[var(--accent-color)] text-white text-sm rounded-full" style="padding: 2px 4px; margin: 8px;">
                                            {{ $ad->category ? $ad->category->name : 'Без категории' }}
                                        </span>
                                        @if($ad->hasPromotion())
                                            <span class=" bg-green-600 text-white text-sm rounded-full" style="padding: 2px 4px; margin: 8px;">
                                                Продвинуто
                                            </span>
                                        @endif
                                        <!-- Информация о товаре -->
                                        <div class="px-4 py-3">
                                            <h3 class="text-base font-semibold {{ $ad->is_completed ? 'text-gray-600' : 'text-gray-800' }} mb-3 line-clamp-2 min-h-[2.5rem]">
                                                {{ $ad->name }}
                                            </h3>

                                            <div class="text-xl font-bold {{ $ad->is_completed ? 'text-gray-500' : 'text-[var(--primary-color)]' }} mb-3">
                                                {{ number_format($ad->price, 0, ',', ' ') }} ₽
                                            </div>

                                            <!-- Местоположение и время -->
                                            <div class="space-y-2 mb-3">
                                                @if($ad->address)
                                                    <div class="flex items-center text-sm {{ $ad->is_completed ? 'text-gray-400' : 'text-gray-500' }}">
                                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                                        <span class="truncate">{{ $ad->address }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex items-center text-sm {{ $ad->is_completed ? 'text-gray-400' : 'text-gray-500' }}">
                                                    <i class="far fa-clock mr-2"></i>
                                                    <span>
                                                        Размещено {{ $ad->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <!-- Просмотры -->
                                                <div class="flex items-center text-sm {{ $ad->is_completed ? 'text-gray-400' : 'text-gray-500' }}">
                                                    <i class="far fa-eye mr-2"></i>
                                                    <span>{{ $ad->views }} просмотров</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Футер карточки -->
                                        <div class="px-4 py-3 bg-gray-50 border-t">
                                            <div class="flex justify-between items-center">
                                                <!-- Кнопки действий для активных объявлений -->
                                                <div class="flex space-x-4">
                                                    <a href="{{ route('ad.edit', $ad) }}" data-auth-required
                                                       class="text-[var(--primary-color)] hover:text-[var(--primary-dark)]"
                                                       title="Редактировать">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('ad.complete', $ad) }}"
                                                          method="POST"
                                                          class="inline"
                                                          onsubmit="return confirm('Вы уверены, что хотите завершить это объявление?')">
                                                        @csrf
                                                        <button type="submit"
                                                                class="text-gray-400 hover:text-gray-600"
                                                                title="Завершить объявление">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                <!-- Кнопка подробнее -->
                                                <a href="{{ route('ad.show', $ad) }}"
                                                   class="inline-block px-4 py-1 text-sm text-[var(--accent-color)] hover:bg-gray-100 rounded">
                                                    Подробнее
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">У вас пока нет активных объявлений</p>
                                <a href="{{ route('create_ad') }}" data-auth-required
                                   class="inline-block mt-4 px-4 py-2 bg-[var(--primary-color)] text-white rounded-lg hover:bg-[var(--primary-dark)]">
                                    Разместить объявление
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Завершенные объявления -->
                    <div x-show="activeTab === 'completed'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100">
                        @if($completedAds->count() > 0)
                            <div class="adaptive-grid">
                                @foreach($completedAds as $ad)
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 {{ $ad->is_completed ? 'opacity-75' : '' }}">
                                        <!-- Изображение товара -->
                                        <div class="relative h-44">
                                            <img src="{{ asset('storage/' . $ad->photo) }}"
                                                 alt="{{ $ad->name }}"
                                                 class="w-full h-full object-cover {{ $ad->is_completed ? 'grayscale' : '' }}">
                                            <span class=" bg-[var(--accent-color)] text-white text-sm rounded-full" style="padding: 2px 4px; margin: 8px;">
                                                {{ $ad->category ? $ad->category->name : 'Без категории' }}
                                            </span>
                                            <div class="absolute inset-0 bg-black/10"></div>
                                            <span class="absolute top-2 right-2 bg-gray-800/80 text-white px-2 py-1 rounded text-xs">
                                                Завершено
                                            </span>

                                        </div>

                                        <!-- Информация о товаре -->
                                        <div class="px-4 py-3">
                                            <h3 class="text-base font-semibold {{ $ad->is_completed ? 'text-gray-600' : 'text-gray-800' }} mb-3 line-clamp-2 min-h-[2.5rem]">
                                                {{ $ad->name }}
                                            </h3>

                                            <div class="text-xl font-bold {{ $ad->is_completed ? 'text-gray-500' : 'text-[var(--primary-color)]' }} mb-3">
                                                {{ number_format($ad->price, 0, ',', ' ') }} ₽
                                            </div>

                                            <!-- Местоположение и время -->
                                            <div class="space-y-2 mb-3">
                                                @if($ad->address)
                                                    <div class="flex items-center text-sm {{ $ad->is_completed ? 'text-gray-400' : 'text-gray-500' }}">
                                                        <i class="fas fa-map-marker-alt mr-2"></i>
                                                        <span class="truncate">{{ $ad->address }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex items-center text-sm {{ $ad->is_completed ? 'text-gray-400' : 'text-gray-500' }}">
                                                    <i class="far fa-clock mr-2"></i>
                                                    <span>
                                                        Размещено {{ $ad->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <!-- Просмотры -->
                                                <div class="flex items-center text-sm {{ $ad->is_completed ? 'text-gray-400' : 'text-gray-500' }}">
                                                    <i class="far fa-eye mr-2"></i>
                                                    <span>{{ $ad->views }} просмотров</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Футер карточки -->
                                        <div class="px-4 py-3 bg-gray-50 border-t">
                                            <div class="flex justify-between items-center">
                                                <!-- Для завершенных объявлений только просмотр -->
                                                <span class="text-sm text-gray-400">
                                                    Объявление завершено
                                                </span>
                                                <a href="{{ route('ad.show', $ad) }}"
                                                   class="inline-block px-4 py-1 text-sm text-gray-400 hover:bg-gray-100 rounded">
                                                    Просмотреть
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-check-circle text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">У вас нет завершенных объявлений</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
