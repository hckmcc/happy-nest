@extends('layouts.admin')

@section('title', 'Редактирование категории')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Редактирование категории</h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.category.update', $category) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Основная информация -->
                                <div class="col-md-8">
                                    <!-- Название -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Название категории</label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name', $category->name) }}"
                                               required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Родительская категория -->
                                    <div class="mb-3">
                                        <label for="parent_category_id" class="form-label">Родительская категория</label>
                                        <select class="form-select @error('parent_category_id') is-invalid @enderror"
                                                id="parent_category_id"
                                                name="parent_category_id">
                                            <option value="">Нет (корневая категория)</option>
                                            @foreach($categories as $parentCategory)
                                                <option value="{{ $parentCategory->id }}"
                                                    {{ old('parent_category_id', $category->parent_category_id) == $parentCategory->id ? 'selected' : '' }}>
                                                    {{ $parentCategory->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('parent_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Иконка -->
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="icon" class="form-label">Иконка категории</label>
                                        <input type="file"
                                               class="form-control @error('icon') is-invalid @enderror"
                                               id="icon"
                                               name="icon"
                                               accept="image/*"
                                               onchange="previewImage(this)">
                                        @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            Загрузите новое изображение, чтобы изменить иконку
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Кнопки -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                                <a href="{{ route('admin.categories') }}" class="btn btn-secondary">Отмена</a>
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
                        document.getElementById('icon-preview').src = e.target.result;
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush
@endsection
