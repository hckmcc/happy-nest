@extends('layouts.app')
@section('content')
<!-- Герой-секция -->
<div class="hero-pattern py-20 bg-[var(--secondary-color)]">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
            Найдите идеальные товары для вашего малыша
        </h1>
        <p class="text-xl text-gray-600 mb-8">
            Безопасная платформа для покупки и продажи детских товаров
        </p>
        <a href="{{ route('create_ad') }}" data-auth-required class="bg-[var(--primary-color)] text-white px-8 py-3 rounded-full text-lg font-semibold hover:bg-opacity-90 transition">
            Разместить объявление
        </a>
    </div>
</div>

<!-- Категории -->
<div class="container mx-auto px-4 py-16">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Популярные категории</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($categories as $category)
            <a href="" class="category-card block p-6 bg-white rounded-lg shadow-md hover:shadow-lg">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-[var(--accent-color)] bg-opacity-20 rounded-full">
                        <img src="{{ $category->icon }}" alt="" class="category-img">
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $category->name }}</h3>
                </div>
            </a>
        @endforeach
    </div>
</div>

<div class="bg-white py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Рекомендованные объявления</h2>

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
                    <div class="px-4 py-3 bg-gray-50 border-t flex-row">
                        <!-- Кнопка подробнее на всю ширину -->
                        <a href="{{ route('ad.show', $ad) }}" class="block text-center text-sm text-[var(--accent-color)] hover:bg-gray-100 rounded py-1">
                            Подробнее
                        </a>
                        @auth
                            <button class="favourite-btn" data-auth-required data-ad-id="{{ $ad->id }}">
                                <i class="fa-heart {{ $ad->isFavouritedBy(auth()->user()) ? 'fas' : 'far' }}"></i>
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
                                                           value="{{ $ad->id }}"
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
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Преимущества -->
<div class="container mx-auto px-4 py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-[var(--primary-color)] bg-opacity-20 rounded-full">
                <i class="fas fa-shield-alt text-[var(--primary-color)] text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Безопасные сделки</h3>
            <p class="text-gray-600">Проверенные продавцы и система отзывов</p>
        </div>
        <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-[var(--accent-color)] bg-opacity-20 rounded-full">
                <i class="fas fa-comments text-[var(--accent-color)] text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Удобное общение</h3>
            <p class="text-gray-600">Встроенный чат для обсуждения деталей</p>
        </div>
        <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center bg-[var(--primary-color)] bg-opacity-20 rounded-full">
                <i class="fas fa-hand-holding-heart text-[var(--primary-color)] text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Забота о детях</h3>
            <p class="text-gray-600">Только качественные детские товары</p>
        </div>
    </div>
</div>

<style>
    .favourite-btn {
        border: none;
        background: none;
        cursor: pointer;
        padding: 5px;
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
