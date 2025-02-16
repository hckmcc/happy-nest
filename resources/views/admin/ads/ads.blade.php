@extends('layouts.admin')

@section('title', 'Объявления')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Список объявлений</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.ad.create.show') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Добавить объявление
                            </a>
                        </div>
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
                                    <th>Автор</th>
                                    <th>Цена</th>
                                    <th>Адрес</th>
                                    <th>Просмотры</th>
                                    <th>Статус</th>
                                    <th>Дата создания</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($ads as $ad)
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
                                        <td>{{ $ad->user->name }}</td>
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
                                                <form action="{{ route('admin.ad.delete', ['ad' => $ad, 'from' => 'admin']) }}" method="POST">
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
                                        <td colspan="11" class="text-center">Объявлений не найдено</td>
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
