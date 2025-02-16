@extends('layouts.admin')

@section('title', 'Категории')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Список категорий</h3>
                    </div>

                    <div class="card-body">
                        <!-- Кнопка добавления -->
                        <div class="mb-4">
                            <a href="{{ route('admin.category.create.show') }}" class="btn btn-primary">
                                Добавить категорию
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Иконка</th>
                                    <th>Название</th>
                                    <th>Родительская категория</th>
                                    <th>Кол-во подкатегорий</th>
                                    <th>Кол-во объявлений</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $category->icon) }}"
                                                 alt=" {{ $category->name }}"
                                                 class="img-thumbnail"
                                                 style="max-width: 50px">
                                        </td>
                                        <td>
                                            @if($category->parent_category_id)
                                                <span class="ms-3">↳</span>
                                            @endif
                                            {{ $category->name }}
                                        </td>
                                        <td>
                                            @if($category->parent)
                                                {{ $category->parent->name }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $category->children_count }}
                                        </td>
                                        <td>
                                            {{ $category->ads_count }}
                                        </td>
                                        <td>{{ $category->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.category.edit.show', $category) }}"
                                                   class="text-decoration-none">
                                                    <div class="bg-primary text-white rounded px-3 py-1">
                                                        Редактировать
                                                    </div>
                                                </a>
                                                @if($category->children_count == 0 && $category->ads_count == 0)
                                                    <form action="{{ route('admin.category.delete', $category) }}"
                                                          method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="bg-danger text-white border-0 rounded px-3 py-1"
                                                                onclick="return confirm('Вы уверены, что хотите удалить категорию?')">
                                                            Удалить
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
