@extends('admin.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="fas fa-history me-2"></i>История расчетов
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Пользователь</th>
                        <th>Тип кредита</th>
                        <th>Сумма</th>
                        <th>Ежем. платеж</th>
                        <th>Общая выплата</th>
                        <th>Переплата</th>
                        <th>Дата</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($calculations as $calc)
                    <tr>
                        <td>{{ $calc->id }}</td>
                        <td>{{ $calc->user->name ?? 'Гость' }}</td>
                        <td><span class="badge bg-info">{{ $calc->loan_type }}</span></td>
                        <td>{{ number_format($calc->amount, 0, '', ' ') }} ₽</td>
                        <td><strong class="text-primary">{{ number_format($calc->monthly_payment, 0, '', ' ') }} ₽</strong></td>
                        <td>{{ number_format($calc->total_payment, 0, '', ' ') }} ₽</td>
                        <td class="text-danger">{{ number_format($calc->overpayment, 0, '', ' ') }} ₽</td>
                        <td>{{ $calc->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <button class="btn btn-sm btn-danger delete-calculation" data-id="{{ $calc->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $calculations->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.delete-calculation').click(function() {
        if(confirm('Удалить этот расчет?')) {
            var id = $(this).data('id');
            $.ajax({
                url: '/admin/calculations/' + id,
                method: 'DELETE',
                data: {_token: '{{ csrf_token() }}'},
                success: function() {
                    location.reload();
                }
            });
        }
    });
});
</script>
@endpush
@endsection