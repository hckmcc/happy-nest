@extends('layouts.app')
@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <!-- Заголовок -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Продвинуть объявление {{$ad->name}}</h1>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="container p-6">
                    <h1 class="text-xl font-medium">Информация о типах продвижения</h1>
                    <div class="row px-2">
                        @foreach($promotionTypes as $promotionType)
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title text-lg font-medium">{{ $promotionType->name }}</h5>
                                        <p class="card-text">{{ $promotionType->description }}</p>
                                        <p class="card-text">
                                            Стоимость: {{ number_format($promotionType->price, 2) }} руб.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <form action="{{ route('payment.create', $ad) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <!-- Тип продвижения -->
                    <div class="mb-6">
                        <label for="promotion_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Тип продвижения
                        </label>
                        <select id="promotion_id"
                                name="promotion_id"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring focus:ring-[var(--primary-color)] focus:ring-opacity-50">
                            <option value="">Выберите тип продвижения</option>
                            @foreach($promotionTypes as $promotionType)
                                <option value="{{ $promotionType->id }}" {{ old('promotion_id') == $promotionType->id ? 'selected' : '' }}>
                                    {{ $promotionType->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('promotion_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Кнопки -->
                    <div class="flex justify-end space-x-4">
                        <button type="button"
                                onclick="window.location.href='{{ route('ad.show', $ad) }}'"
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)]">
                            Отмена
                        </button>
                        <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[var(--primary-color)] hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)]">
                            Продвинуть
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
