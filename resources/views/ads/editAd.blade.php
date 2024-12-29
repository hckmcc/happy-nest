@extends('layouts.app')
@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Заголовок -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Редактирование объявления</h1>
                <p class="mt-2 text-sm text-gray-600">Измените информацию о вашем товаре</p>
            </div>

            <!-- Форма редактирования -->
            <div class="bg-white rounded-lg shadow-sm">
                <form action="{{ route('ad.update', $ad) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Фото товара -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Фото товара
                        </label>
                        <div class="relative inline-block">
                            <!-- Текущее фото -->
                            <div id="preview-container" class="mb-4 relative">
                                <img id="preview-image"
                                     src="{{ asset('storage/' . $ad->photo) }}"
                                     alt="{{ $ad->name }}"
                                     class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">

                                <!-- Кнопка удаления -->
                                <button type="button"
                                        onclick="removeImage()"
                                        class="absolute -top-2 -right-2 bg-white rounded-full w-6 h-6 flex items-center justify-center shadow-md hover:bg-red-100 transition-colors">
                                    <i class="fas fa-times text-red-500 text-xs"></i>
                                </button>
                            </div>

                            <!-- Кнопка загрузки нового фото -->
                            <label for="photo"
                                   class="cursor-pointer inline-flex items-center justify-center p-2 border-2 border-dashed border-gray-300 rounded-lg hover:border-[var(--primary-color)] transition-colors w-32 h-32">
                                <div class="text-center">
                                    <i class="fas fa-camera text-gray-400 text-xl mb-2"></i>
                                    <div class="text-sm text-gray-500">Изменить фото</div>
                                </div>
                                <input type="file"
                                       id="photo"
                                       name="photo"
                                       class="hidden"
                                       accept="image/*"
                                       onchange="previewImage(this)">
                            </label>
                        </div>

                        <p class="mt-2 text-sm text-gray-500">JPG или PNG до 2MB</p>

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
                               value="{{ old('name', $ad->name) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)]"
                               required>
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
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)]"
                                required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $ad->category_id) == $category->id ? 'selected' : '' }}>
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
                        <div class="relative rounded-lg shadow-sm">
                            <input type="number"
                                   id="price"
                                   name="price"
                                   value="{{ old('price', $ad->price) }}"
                                   class="block w-full rounded-lg border-gray-300 pl-7 pr-12 focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)]"
                                   step="0.01"
                                   required>
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
                               value="{{ old('address', $ad->address) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)]"
                               required>
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
                                  rows="5"
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)]"
                                  required>{{ old('description', $ad->description) }}</textarea>
                        @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Кнопки -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('ad.show', $ad) }}"
                           class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)]">
                            Отмена
                        </a>
                        <button type="submit"
                                class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-[var(--primary-color)] hover:bg-[var(--primary-dark)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)]">
                            Сохранить изменения
                        </button>
                    </div>
                </form>
            </div>

            <!-- Кнопка удаления объявления -->
            <div class="mt-6">
                <form action="{{ route('ad.delete', $ad) }}"
                      method="POST"
                      onsubmit="return confirm('Вы уверены, что хотите удалить это объявление?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex justify-center items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 w-full">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Удалить объявление
                    </button>
                </form>
            </div>
        </div>
    </div>

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
                    const previewImg = document.getElementById('preview-image');
                    previewImg.src = e.target.result;
                    document.getElementById('preview-container').style.display = 'block';
                };

                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            const previewImg = document.getElementById('preview-image');
            const input = document.getElementById('photo');

            previewImg.src = "{{ asset('storage/' . $ad->photo) }}";
            input.value = '';
        }
    </script>
@endsection
