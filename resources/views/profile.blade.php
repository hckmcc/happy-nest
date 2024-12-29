@extends('layouts.app')
@section('content')
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Заголовок страницы -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900">Профиль</h1>
                <p class="mt-2 text-sm text-gray-600">Управляйте вашей персональной информацией</p>
            </div>

            <!-- Основной контент -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <!-- Форма редактирования профиля -->
                    <form action="{{ route('profile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Фото профиля -->
                        <div class="mb-8">
                            <div class="flex items-center">
                                <div class="relative">
                                    <div id="current-photo" class="mb-4 relative">
                                        @if(auth()->user()->photo)
                                            <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('avatars/default.jpg') }}"
                                                 alt="{{ auth()->user()->name }}"
                                                 class="w-16 h-16 rounded-full object-cover">
                                        @else
                                            <div class="w-16 h-16 rounded-full bg-[var(--accent-color)] flex items-center justify-center text-white font-medium">
                                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div id="preview-container" class="hidden mb-4 relative">
                                        <img id="preview-image"
                                             alt="Предпросмотр"
                                             class="w-16 h-16 rounded-full object-cover">
                                    </div>

                                    <label for="photo" class="absolute bottom-0 right-0 bg-white rounded-full p-2 shadow-lg cursor-pointer hover:bg-gray-50 transition">
                                        <i class="fas fa-camera text-gray-600"></i>
                                        <input type="file"
                                               id="photo"
                                               name="photo"
                                               class="hidden"
                                               accept="image/*"
                                               onchange="previewImage(this)">
                                    </label>
                                </div>
                                <div class="ml-6">
                                    <h3 class="text-lg font-medium text-gray-900">Фото профиля</h3>
                                    <p class="text-sm text-gray-500">JPG, PNG или GIF, максимум 2MB</p>
                                </div>
                            </div>
                            @error('photo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Основная информация -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Имя -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Имя</label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name', auth()->user()->name) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring focus:ring-[var(--primary-color)] focus:ring-opacity-50">
                                @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       value="{{ old('email', auth()->user()->email) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring focus:ring-[var(--primary-color)] focus:ring-opacity-50">
                                @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Телефон -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Телефон</label>
                                <input type="tel"
                                       name="phone"
                                       id="phone"
                                       value="{{ old('phone', auth()->user()->phone) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring focus:ring-[var(--primary-color)] focus:ring-opacity-50">
                                @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Изменение пароля -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Изменение пароля</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Текущий пароль -->
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700">
                                        Текущий пароль
                                    </label>
                                    <input type="password"
                                           name="current_password"
                                           id="current_password"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring focus:ring-[var(--primary-color)] focus:ring-opacity-50">
                                    @error('current_password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Новый пароль -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">
                                        Новый пароль
                                    </label>
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring focus:ring-[var(--primary-color)] focus:ring-opacity-50">
                                    @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Подтверждение нового пароля -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                        Подтвердите новый пароль
                                    </label>
                                    <input type="password"
                                           name="password_confirmation"
                                           id="password_confirmation"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[var(--primary-color)] focus:ring focus:ring-[var(--primary-color)] focus:ring-opacity-50">
                                </div>
                            </div>
                        </div>

                        <!-- Кнопки действий -->
                        <div class="mt-8 flex justify-end">
                            <button type="button"
                                    onclick="window.location.href='{{ route('home') }}'"
                                    class="mr-3 px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)]">
                                Отмена
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[var(--primary-color)] hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-color)]">
                                Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript для предпросмотра загружаемого изображения -->
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
                    const currentPhoto = document.getElementById('current-photo');
                    const previewContainer = document.getElementById('preview-container');
                    const previewImg = document.getElementById('preview-image');
                    const uploadLabel = input.parentElement;

                    currentPhoto.classList.add('hidden');
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
