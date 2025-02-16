@extends('layouts.admin')

@section('title', 'Жалобы')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Список жалоб</h3>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Объявление</th>
                                    <th>Отправитель</th>
                                    <th>Причина</th>
                                    <th>Дата</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($reports as $report)
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.ad.show', $report->ad) }}"
                                               class="text-decoration-none">
                                                {{ $report->ad->name }}
                                            </a>
                                            <div class="text-muted small">
                                                ID: {{ $report->ad->id }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($report->user)
                                                <a href="{{ route('admin.user.show', $report->user) }}"
                                                   class="text-decoration-none">
                                                    {{ $report->user->name }}
                                                </a>
                                                <div class="text-muted small">
                                                    {{ $report->user->email }}
                                                </div>
                                            @else
                                                <span class="text-muted">Пользователь удален</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="max-width: 300px;">
                                                {{ $report->reason }}
                                            </div>
                                        </td>
                                        <td>{{ $report->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <form action="{{ route('admin.report.delete', $report) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="bg-danger text-white border-0 rounded px-3 py-1"
                                                            onclick="return confirm('Вы уверены, что хотите удалить жалобу?')">
                                                        Удалить жалобу
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Жалоб не найдено</td>
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
