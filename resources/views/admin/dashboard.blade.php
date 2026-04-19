@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Всего кредитов</h6>
                        <h2 class="mb-0">{{ $totalLoans }}</h2>
                    </div>
                    <i class="fas fa-chart-line fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Всего расчетов</h6>
                        <h2 class="mb-0">{{ $totalCalculations }}</h2>
                    </div>
                    <i class="fas fa-calculator fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Пользователей</h6>
                        <h2 class="mb-0">{{ $totalUsers }}</h2>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card stat-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Активных кредитов</h6>
                        <h2 class="mb-0">{{ $activeLoans }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-history me-2"></i>Последние расчеты
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr><th>Пользователь</th><th>Тип</th><th>Сумма</th><th>Платеж</th><th>Дата</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentCalculations as $calc)
                            <tr>
                                <td>{{ $calc->user->name ?? 'Гость' }}</td>
                                <td>{{ $calc->loan_type }}</td>
                                <td>{{ number_format($calc->amount, 0, '', ' ') }} ₽</td>
                                <td>{{ number_format($calc->monthly_payment, 0, '', ' ') }} ₽</td>
                                <td>{{ $calc->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                            @empty
                                <td><td colspan="5" class="text-center">Нет расчетов</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-users me-2"></i>Новые пользователи
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr><th>Имя</th><th>Email</th><th>Роль</th><th>Дата регистрации</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge {{ $user->role == 'admin' ? 'bg-danger' : 'bg-secondary' }}">{{ $user->role }}</span></td>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">Нет пользователей</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection