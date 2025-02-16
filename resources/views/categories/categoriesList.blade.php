@extends('layouts.app')

@section('content')
    <div class="bg-white min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Заголовок страницы -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Категории товаров</h1>
                <p class="mt-2 text-gray-600">Найдите необходимые товары по категориям</p>
            </div>

            <!-- Сетка категорий -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories->where('parent_category_id', null) as $category)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">
                            <!-- Заголовок категории -->
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-full bg-[var(--bg-color)] flex items-center justify-center overflow-hidden">
                                    <img src="{{ asset('storage/' . $category->icon) }}"
                                         alt="{{ $category->name }}"
                                         class="w-8 h-8 object-contain">
                                </div>
                                <h2 class="ml-4 text-xl font-semibold text-gray-900">{{ $category->name }}</h2>
                            </div>

                            <!-- Подкатегории -->
                            @if($category->children && $category->children->count() > 0)
                                <div class="mt-4 space-y-2">
                                    @foreach($category->children as $child)
                                        <div class="group">
                                            @if($child->children && $child->children->count() > 0)
                                                    <span class="ml-2 font-medium text-gray-800">{{ $child->name }}</span>
                                            @else
                                                <a href="{{ route('category.show', $child) }}" class="flex items-center py-2 font-medium text-gray-800 hover:text-[var(--primary-color)] transition-colors duration-200">
                                                    <span class="ml-2">{{ $child->name }}</span>
                                                </a>
                                            @endif
                                            <!-- Подкатегории третьего уровня -->
                                            @if($child->children && $child->children->count() > 0)
                                                <div class="ml-6 mt-1 space-y-1">
                                                    @foreach($child->children as $grandchild)
                                                        <a href="{{ route('category.show', $grandchild) }}" class="flex items-center py-1 text-gray-500 hover:text-[var(--primary-color)] transition-colors duration-200">
                                                            <span class="ml-2">{{ $grandchild->name }}</span>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 mt-2">Нет подкатегорий</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
