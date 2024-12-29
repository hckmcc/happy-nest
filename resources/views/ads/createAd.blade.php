@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <!-- Заголовок -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Создать объявление</h1>
        <p class="mt-2 text-sm text-gray-600">Заполните информацию о вашем товаре</p>
    </div>

    <!-- Форма -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('create_ad') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <!-- Фото товара -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-[var(--primary-dark)] mb-2">
                    Фото товара
                </label>

                <!-- Контейнер для предпросмотра и загрузки -->
                <div class="relative inline-block">
                    <!-- Предпросмотр фото -->
                    <div id="preview-container" class="hidden mb-4 relative">
                        <img id="preview-image"
                             alt="Предпросмотр"
                             class="w-32 h-32 object-cover rounded-lg border-2 border-[var(--primary-light)]">

                        <!-- Кнопка удаления -->
                        <button type="button"
                                onclick="removeImage()"
                                class="absolute -top-2 -right-2 bg-white rounded-full w-6 h-6 flex items-center justify-center shadow-md hover:bg-red-100 transition-colors">
                            <i class="fas fa-times text-red-500 text-xs"></i>
                        </button>
                    </div>

                    <!-- Кнопка загрузки -->
                    <label for="photo"
                           class="cursor-pointer inline-flex items-center justify-center p-2 border-2 border-dashed border-[var(--primary-light)] rounded-lg hover:border-[var(--primary-color)] transition-colors w-32 h-32">
                        <div class="text-center">
                            <i class="fas fa-camera text-[var(--primary-color)] text-xl mb-2"></i>
                            <div class="text-sm text-gray-500">Добавить фото</div>
                        </div>
                        <input type="file"
                               id="photo"
                               name="photo"
                               class="hidden"
                               accept="image/*"
                               onchange="previewImage(this)">
                    </label>
                </div>

                <p class="mt-2 text-sm text-gray-500">JPG до 2MB</p>

                @error('photo')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Название товара -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Название товара
                </label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring focus:ring-[var(--primary-color)] focus:ring-opacity-50"
                       placeholder="Например: Детская коляска">
                @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Категория -->
            <div class="mb-6">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Категория
                </label>
                <select id="category_id"
                        name="category_id"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring focus:ring-[var(--primary-color)] focus:ring-opacity-50">
                    <option value="">Выберите категорию</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Цена -->
            <div class="mb-6">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                    Цена
                </label>
                <div class="relative rounded-md shadow-sm">
                    <input type="number"
                           id="price"
                           name="price"
                           value="{{ old('price') }}"
                           class="block w-full rounded-md border-gray-300 pl-7 pr-12 focus:border-[var(--primary-color)] focus:ring focus:ring-[var(--primary-color)] focus:ring-opacity-50"
                           placeholder="0.00"
                           step="0.01">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <span class="text-gray-500 sm:text-sm">₽</span>
                    </div>
                </div>
                @error('price')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Адрес -->
            <div class="mb-6">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                    Адрес
                </label>
                <input type="text"
                       id="address"
                       name="address"
                       value="{{ old('address') }}"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring focus:ring-[var(--primary-color)] focus:ring-opacity-50"
                       placeholder="Город, улица">
                @error('address')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Описание -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Описание
                </label>
                <textarea id="description"
                          name="description"
                          rows="4"
                          class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring focus:ring-[var(--primary-color)] focus:ring-opacity-50"
                          placeholder="Подробно опишите ваш товар">{{ old('description') }}</textarea>
                @error('description')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Кнопки -->
            <div class="flex justify-end space-x-4">
                <button type="button"
                        onclick="window.location.href='{{ route('home') }}'"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)]">
                    Отмена
                </button>
                <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[var(--primary-color)] hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)]">
                    Опубликовать
                </button>
            </div>
        </form>
    </div>
</div>
<!-- JavaScript для предпросмотра изображения -->
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Проверяем размер файла (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Файл слишком большой. Максимальный размер - 2MB');
                input.value = '';
                return;
            }

            // Проверяем тип файла
            if (!file.type.startsWith('image/')) {
                alert('Пожалуйста, загрузите изображение');
                input.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                const previewContainer = document.getElementById('preview-container');
                const previewImg = document.getElementById('preview-image');
                const uploadLabel = input.parentElement;

                previewImg.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadLabel.classList.add('hidden');
            };

            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        const input = document.getElementById('photo');
        const previewContainer = document.getElementById('preview-container');
        const uploadLabel = input.parentElement;

        input.value = '';
        previewContainer.classList.add('hidden');
        uploadLabel.classList.remove('hidden');
    }

    // Drag and drop функционал
    document.addEventListener('DOMContentLoaded', function() {
        const uploadLabel = document.querySelector('label[for="photo"]');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadLabel.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadLabel.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadLabel.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            uploadLabel.classList.add('border-[var(--primary-color)]', 'bg-[var(--bg-color)]');
        }

        function unhighlight(e) {
            uploadLabel.classList.remove('border-[var(--primary-color)]', 'bg-[var(--bg-color)]');
        }

        uploadLabel.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length) {
                const input = document.getElementById('photo');
                input.files = files;
                previewImage(input);
            }
        }
    });
</script>
@endsection
