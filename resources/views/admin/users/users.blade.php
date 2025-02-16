@extends('layouts.admin')

@section('title', 'Пользователи')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Список пользователей</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.user.add.show') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Добавить пользователя
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Фото</th>
                                    <th>Имя</th>
                                    <th>Email</th>
                                    <th>Телефон</th>
                                    <th>Роль</th>
                                    <th>Дата регистрации</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            @if($user->photo)
                                                <img src="{{ asset('storage/' . $user->photo) }}"
                                                     alt="Фото {{ $user->name }}"
                                                     class="img-thumbnail"
                                                     style="max-width: 50px">
                                            @endif
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone ?? 'Не указан' }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                {{ $role->name . ","}}
                                            @endforeach
                                        </td>
                                        <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.user.show', $user) }}"
                                                   class="text-decoration-none">
                                                    <div class="bg-primary text-white rounded px-3 py-1">
                                                        Открыть
                                                    </div>
                                                </a>
                                                <form action="{{ route('admin.user.delete', $user) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="bg-danger text-white border-0 rounded px-3 py-1"
                                                            onclick="return confirm('Вы уверены, что хотите удалить пользователя?')">
                                                        Удалить
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Пользователей не найдено</td>
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
