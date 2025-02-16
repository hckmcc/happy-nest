@extends('layouts.app')
@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Основной контент -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <!-- Верхняя часть с фото и основной информацией -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
                        <div class="relative aspect-square rounded-lg overflow-hidden">
                            <!-- Название и категория -->
                            <div class="mb-6">
                                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $ad->name }}</h1>
                                <div class="inline-block bg-[var(--accent-color)] text-white text-sm px-3 py-1 rounded-full">
                                    {{ $ad->category->name }}
                                </div>
                                @if($ad->hasPromotion())
                                    <div class="inline-block bg-green-600 text-white text-sm px-3 py-1 rounded-full">
                                        <p>Продвинуто</p>
                                    </div>
                                @endif
                            </div>
                            <!-- Фото товара -->
                            <div class="flex items-center" style="margin-inline: 15%">
                                <img src="{{ asset('storage/' . $ad->photo) }}"
                                     alt="{{ $ad->name }}"
                                     class="w-full object-cover">
                            </div>
                        </div>
                        <!-- Информация о товаре -->
                        <div>
                            <!-- Цена -->
                            <div class="text-4xl font-bold text-[var(--primary-color)] mb-6 mt-8">
                                {{ number_format($ad->price, 0, ',', ' ') }} ₽
                            </div>

                            <!-- Адрес и дата -->
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center text-gray-600 mt-2">
                                    <span><i class="fas fa-map-marker-alt w-5"></i> {{ $ad->address }}</span>
                                </div>
                                <div class="flex items-center text-gray-600 mt-2">
                                    <span><i class="far fa-clock w-5"></i> Размещено {{ $ad->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center text-gray-600 mt-2">
                                    <span><i class="far fa-eye w-5"></i> {{ $ad->views }} просмотров</span>
                                </div>
                            </div>

                            <!-- Продавец -->
                            <div class="border-t border-gray-100 pt-6 mb-6">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4">Продавец</h2>
                                <div class="flex items-center">
                                    @if($ad->user->photo)
                                        <img src="{{ asset('storage/' . $ad->user->photo) }}"
                                             alt="{{ $ad->user->name }}"
                                             class="size-12 rounded-full object-cover">
                                    @else
                                        <span class="size-12 rounded-full bg-[var(--accent-color)] flex items-center justify-center text-white font-medium">
                                            {{ strtoupper(substr($ad->user->name, 0, 1)) }}
                                        </span>
                                    @endif
                                    <div class="ml-4">
                                        <a href="{{ route('seller.show', $ad->user) }}" class="hover:bg-red-500"><div class="font-medium text-gray-900">{{ $ad->user->name }}</div></a>
                                        <div class="text-sm text-gray-500">На сайте с {{ $ad->user->created_at->format('F Y') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Кнопки действий -->
                            <div class="space-y-3">
                                @if(auth()->id() !== $ad->user_id)
                                    <!-- Кнопка написать сообщение -->
                                    <a href="{{ route('chat.show', ['ad' => $ad->id, 'user' => $ad->user_id]) }}" data-auth-required
                                       class="block w-full text-center mb-2 px-6 py-3 bg-[var(--primary-color)] text-white rounded-lg shadow-md hover:shadow-lg transition-colors">
                                        <i class="far fa-comment-alt mr-2"></i>
                                        Написать продавцу
                                    </a>

                                    <!-- Номер телефона -->
                                    <button onclick="showPhone(this)"
                                            data-phone="{{ $ad->user->phone }}"
                                            class="block w-full text-center px-6 py-3 border border-[var(--primary-color)] text-[var(--primary-color)] rounded-lg shadow-md hover:shadow-lg transition-colors">
                                        <i class="fas fa-phone-alt mr-2"></i>
                                        Показать телефон
                                    </button>
                                @endif

                                @if(auth()->check() && auth()->id() === $ad->user_id)
                                    @if($ad->is_completed === false)
                                        <!-- Кнопки для владельца объявления -->
                                        <div class="flex space-x-3">
                                            <a href="{{ route('ad.edit', $ad) }}"
                                               class="flex-1 text-center mr-2 px-6 py-3 bg-[var(--primary-color)] text-white rounded-lg shadow-md hover:shadow-lg transition-colors">
                                                Редактировать
                                            </a>
                                            <a href="{{ route('ad.promote.show', $ad) }}"
                                               class="flex-1 text-center mr-2 px-6 py-3 bg-[var(--primary-color)] text-white rounded-lg shadow-md hover:shadow-lg transition-colors">
                                                Продвинуть
                                            </a>
                                            <form action="{{ route('ad.complete', $ad) }}"
                                                  method="POST"
                                                  class="flex-1"
                                                  onsubmit="return confirm('Завершить объявление?')">
                                                @csrf
                                                @method('POST')
                                                <button type="submit"
                                                        class="w-full ml-2 px-6 py-3 border border-[var(--primary-color)] text-[var(--primary-color)] rounded-lg shadow-md hover:shadow-lg transition-colors">
                                                    Завершить
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <!-- Кнопки для владельца объявления -->
                                        <div class="flex space-x-3">
                                            <a href="{{ route('ad.edit', $ad) }}"
                                               class="flex-1 text-center mr-2 px-6 py-3 bg-[var(--primary-color)] text-white rounded-lg shadow-md hover:shadow-lg transition-colors">
                                                Редактировать
                                            </a>
                                            <form action="{{ route('ad.republish', $ad) }}"
                                                  method="POST"
                                                  class="flex-1"
                                                  onsubmit="return confirm('Опубликовать объявление?')">
                                                @csrf
                                                @method('POST')
                                                <button type="submit"
                                                        class="w-full ml-2 px-6 py-3 border border-[var(--primary-color)] text-[var(--primary-color)] rounded-lg shadow-md hover:shadow-lg transition-colors">
                                                    Опубликовать
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Описание -->
                    <div class="mt-8 border-t border-gray-100 pt-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Описание</h2>
                        <div class="prose max-w-none text-gray-600">
                            {{ $ad->description }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Похожие объявления -->
            @if($similarAds->count() > 0)
                <div class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Похожие объявления</h2>
                    <div class="adaptive-grid">
                        @foreach($similarAds as $similarAd)
                            <!-- Карточка похожего товара -->
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <!-- Изображение товара -->
                                <div class="relative h-44">
                                    <img src="{{ asset('storage/' . $similarAd->photo) }}" alt="{{ $similarAd->name }}" class="w-full h-full object-cover">
                                </div>
                                <span class=" bg-[var(--accent-color)] text-white text-sm rounded-full" style="padding: 2px 4px; margin: 8px;">
                                    {{ $similarAd->category ? $similarAd->category->name : 'Без категории' }}
                                </span>

                                <!-- Информация о товаре -->
                                <div class="px-4 py-1"> <!-- Увеличены отступы -->
                                    <h3 class="text-base font-semibold text-gray-800 mb-3 line-clamp-2 min-h-[2.5rem]">
                                        {{ $similarAd->name }}
                                    </h3>

                                    <div class="text-xl font-bold text-[var(--primary-color)] mb-3">
                                        {{ number_format($similarAd->price, 0, ',', ' ') }} ₽
                                    </div>

                                    <!-- Местоположение и время -->
                                    <div class="space-y-2 mb-3">
                                        @if($similarAd->address)
                                            <div class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-map-marker-alt mr-2"></i>
                                                <span class="truncate">{{ $similarAd->address }}</span>
                                            </div>
                                        @endif
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="far fa-clock mr-2"></i>
                                            <span>{{ $similarAd->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Футер карточки -->
                                <div class="px-4 py-3 bg-gray-50 border-t">
                                    <div class="flex justify-between items-center">
                                        <!-- Кнопки действий для активных объявлений -->
                                        <div class="flex space-x-4">
                                            @auth
                                                <button class="favourite-btn" data-auth-required data-ad-id="{{ $similarAd->id }}">
                                                    <i class="fa-heart {{ $similarAd->isFavouritedBy(auth()->user()) ? 'fas' : 'far' }}"></i>
                                                </button>
                                                <div x-data="{ reportModal: false }">
                                                    <!-- Кнопки -->
                                                    <button type="button" @click="$store.modal.openReport()">
                                                        <i class="fa fa-file"></i>
                                                    </button>
                                                    <!-- Модальное окно report -->
                                                    <template x-teleport="body">
                                                        <div x-show="$store.modal.isReportOpen"
                                                             x-cloak
                                                             @click="$store.modal.closeReport()"
                                                             class="modal-backdrop">
                                                            <div @click.stop
                                                                 class="modal-content">
                                                                <div class="flex justify-between items-center mb-6">
                                                                    <h2 class="text-xl font-bold">Report</h2>
                                                                    <button @click="$store.modal.closeReport()"
                                                                            class="text-gray-400 hover:text-gray-600">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>

                                                                <form action="{{ route('reports.add') }}" method="POST" class="space-y-4">
                                                                    @csrf
                                                                    <div>
                                                                        <input type="hidden"
                                                                               name="ad_id"
                                                                               value="{{ $similarAd->id }}"
                                                                               class="form-input"
                                                                               required>
                                                                        <input type="hidden"
                                                                               name="user_id"
                                                                               value="{{ auth()->user()->id }}"
                                                                               class="form-input"
                                                                               required>
                                                                        <p class="error-message hidden" id="report-error" style="text-decoration-color: red"></p>
                                                                        <textarea id="reason"
                                                                                  name="reason"
                                                                                  rows="4"
                                                                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring focus:ring-[var(--primary-color)] focus:ring-opacity-50"
                                                                                  placeholder="Подробно опишите вашу жалобу">{{ old('reason') }}</textarea>
                                                                    </div>

                                                                    <button type="submit"
                                                                            class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[var(--primary-color)] hover:bg-[var(--primary-dark)]">
                                                                        Отправить
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            @endauth
                                        </div>
                                        <!-- Кнопка подробнее -->
                                        <a href="{{ route('ad.show', $similarAd) }}"
                                           class="inline-block px-4 py-1 text-sm text-[var(--accent-color)] hover:bg-gray-100 rounded">
                                            Подробнее
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function showPhone(button) {
            const phone = button.dataset.phone;
            button.innerHTML = `<i class="fas fa-phone-alt mr-2"></i>${phone}`;
            button.onclick = null;
        }
    </script>
    <style>
        .favourite-btn {
            border: none;
            background: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .favourite-btn .far {
            color: #666;
        }

        .favourite-btn .fas {
            color: #ff4444;
        }

        .favourite-btn:hover .far {
            color: #ff4444;
        }
    </style>
    <script>
        $(document).ready(function() {
            console.log('jQuery loaded');
            $('.favourite-btn').click(function(e) {
                e.preventDefault();
                let button = $(this);
                let adId = button.data('ad-id');
                let icon = $(this).find('.fa-heart');
                $.ajax({
                    url: `/favourites/${adId}`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'added') {
                            icon.removeClass('far').addClass('fas');
                        } else {
                            icon.removeClass('fas').addClass('far');
                        }
                    },
                    error: function() {
                        alert('Произошла ошибка');
                    }
                });
            });
        });
    </script>
@endsection
