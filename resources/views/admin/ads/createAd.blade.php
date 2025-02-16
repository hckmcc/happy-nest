@extends('layouts.admin')

@section('title', 'Добавление объявления')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Создание объявления</h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.ad.create', ['from' => 'admin']) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <!-- Основная информация -->
                                <div class="col-md-8">
                                    <!-- Название -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Название</label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name') }}"
                                               required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Описание -->
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Описание</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description"
                                                  name="description"
                                                  rows="5"
                                                  required>{{ old('description') }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Цена -->
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Цена</label>
                                        <div class="input-group">
                                            <input type="number"
                                                   class="form-control @error('price') is-invalid @enderror"
                                                   id="price"
                                                   name="price"
                                                   value="{{ old('price') }}"
                                                   step="0.01"
                                                   required>
                                            <span class="input-group-text">₽</span>
                                            @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Адрес -->
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Адрес</label>
                                        <input type="text"
                                               class="form-control @error('address') is-invalid @enderror"
                                               id="address"
                                               name="address"
                                               value="{{ old('address') }}"
                                               required>
                                        @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Категория -->
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Категория</label>
                                        <select class="form-select @error('category_id') is-invalid @enderror"
                                                id="category_id"
                                                name="category_id"
                                                required>
                                            <option value="">Выберите категорию</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Фото -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="photo" class="form-label">Фото объявления</label>
                                        <input type="file"
                                               class="form-control @error('photo') is-invalid @enderror"
                                               id="photo"
                                               name="photo"
                                               accept="image/*"
                                               onchange="previewImage(this)">
                                        @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Кнопки -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                                <a href="{{ route('admin.ads') }}" class="btn btn-secondary">Отмена</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        document.getElementById('photo-preview').src = e.target.result;
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush
@endsection
