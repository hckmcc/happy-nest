@extends('layouts.admin')

@section('title', 'Просмотр объявления')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Информация об объявлении -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Информация об объявлении #{{ $ad->id }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Фото объявления -->
                            <div class="col-md-4">
                                <img src="{{ asset('storage/' . $ad->photo) }}"
                                     alt="{{ $ad->name }}"
                                     class="img-fluid img-thumbnail mb-3">
                            </div>

                            <!-- Детали объявления -->
                            <div class="col-md-8">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 200px">Пользователь:</th>
                                        <td>
                                            <a href="{{ route('admin.user.show', $ad->user) }}" class="text-decoration-underline">
                                                {{ $ad->user->name }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="width: 200px">Название:</th>
                                        <td>{{ $ad->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Категория:</th>
                                        <td>{{ $ad->category->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Цена:</th>
                                        <td>{{ number_format($ad->price, 0, ',', ' ') }} ₽</td>
                                    </tr>
                                    <tr>
                                        <th>Адрес:</th>
                                        <td>{{ $ad->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Статус:</th>
                                        <td>
                                        <span class="badge {{ $ad->is_completed ? 'bg-secondary' : 'bg-success'  }}">
                                            {{ $ad->is_completed ? 'Завершено' : 'Активно' }}
                                        </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Просмотры:</th>
                                        <td>{{ $ad->views }}</td>
                                    </tr>
                                    <tr>
                                        <th>Дата создания:</th>
                                        <td>{{ $ad->created_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Описание:</th>
                                        <td>{{ $ad->description }}</td>
                                    </tr>
                                </table>

                                <!-- Кнопки действий -->
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.ad.delete', ['ad' => $ad, 'from' => 'admin']) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-danger text-white border-0 rounded px-3 py-1"
                                                onclick="return confirm('Вы уверены, что хотите удалить объявление?')">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Отзывы -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Отзывы об объявлении</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Автор отзыва</th>
                                    <th>Оценка</th>
                                    <th>Комментарий</th>
                                    <th>Дата</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        <td>{{ $review->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.user.show', $review->buyer) }}" class="text-decoration-underline">
                                                {{ $review->buyer->name }}
                                            </a>
                                        </td>
                                        <td>{{ $review->rate }}</td>
                                        <td>{{ $review->text }}</td>
                                        <td>{{ $review->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <form action="{{ route('admin.review.delete', $review) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="bg-danger text-white border-0 rounded px-3 py-1"
                                                            onclick="return confirm('Вы уверены, что хотите удалить отзыв?')">
                                                        Удалить
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Отзывов пока нет</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
