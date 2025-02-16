@extends('layouts.admin')

@section('title', 'Профиль пользователя ' . $user->name)

@section('content')
    <div class="container-fluid">
        <!-- Профиль пользователя -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Информация о пользователе</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Фото пользователя -->
                            <div class="col-md-3 text-center">
                                @if($user->photo)
                                    <img src="{{ asset('storage/' . $user->photo) }}"
                                         alt="Фото {{ $user->name }}"
                                         class="img-thumbnail mb-3"
                                         style="max-width: 200px">
                                @else
                                    <img src="{{ asset('images/no-photo.png') }}"
                                         alt="Нет фото"
                                         class="img-thumbnail mb-3"
                                         style="max-width: 200px">
                                @endif
                            </div>

                            <!-- Информация о пользователе -->
                            <div class="col-md-9">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 200px">ID:</th>
                                            <td>{{ $user->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Имя:</th>
                                            <td>{{ $user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Телефон:</th>
                                            <td>{{ $user->phone ?? 'Не указан' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Рейтинг:</th>
                                            <td>{{ $user->rating }}</td>
                                        </tr>
                                        <tr>
                                            <th>Дата регистрации:</th>
                                            <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Последнее обновление:</th>
                                            <td>{{ $user->updated_at->format('d.m.Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="card my-3">
                                    <div class="card-header">
                                        <h4>Роли пользователя</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.user.updateRoles', $user) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-3">
                                                @foreach($roles as $role)
                                                    <div class="form-check">
                                                        <input class="form-check-input"
                                                               type="checkbox"
                                                               name="roles[]"
                                                               value="{{ $role->id }}"
                                                               id="role{{ $role->id }}"
                                                            {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="role{{ $role->id }}">
                                                            {{ $role->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                                @error('roles')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <button type="submit" class="btn btn-primary">
                                                Обновить роли
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <!-- Кнопки действий -->
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.user.delete', $user) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-danger text-white border-0 rounded px-3 py-1"
                                                onclick="return confirm('Вы уверены, что хотите удалить пользователя?')">
                                            Удалить пользователя
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Объявления пользователя -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Объявления пользователя</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Фото</th>
                                    <th>Название</th>
                                    <th>Категория</th>
                                    <th>Цена</th>
                                    <th>Адрес</th>
                                    <th>Просмотры</th>
                                    <th>Статус</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($user->ads as $ad)
                                    <tr>
                                        <td>{{ $ad->id }}</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $ad->photo) }}"
                                                 alt="Фото {{ $ad->name }}"
                                                 class="img-thumbnail"
                                                 style="max-width: 100px">
                                        </td>
                                        <td>{{ $ad->name }}</td>
                                        <td>{{ $ad->category->name }}</td>
                                        <td>{{ number_format($ad->price, 0, ',', ' ') }} ₽</td>
                                        <td>{{ $ad->address }}</td>
                                        <td>{{ $ad->views }}</td>
                                        <td>
                                            <span class="badge {{ $ad->is_completed ? 'bg-secondary' : 'bg-success' }}">
                                                {{ $ad->is_completed ? 'Завершено' : 'Активно' }}
                                            </span>
                                        </td>
                                        <td>{{ $ad->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.ad.show', $ad) }}"
                                                   class="text-decoration-none">
                                                    <div class="bg-primary text-white rounded px-3 py-1">
                                                        Открыть
                                                    </div>
                                                </a>
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
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">У пользователя нет объявлений</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Отзывы о пользователе -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Отзывы о пользователе</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Автор отзыва</th>
                                    <th>Объявление</th>
                                    <th>Оценка</th>
                                    <th>Комментарий</th>
                                    <th>Дата оставления</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($reviews as $review)
                                    <tr>
                                        <td>{{ $review->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.user.show', $review->buyer) }}" class="text-decoration-underline">
                                            {{ $review->buyer->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.ad.show', $review->ad) }}" class="text-decoration-underline">
                                            {{ $review->ad->name }}
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
                                        <td colspan="10" class="text-center">О пользователе не оставляли отзывов</td>
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
