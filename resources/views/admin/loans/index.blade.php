@extends('admin.layouts.app')

@section('content')
<style>
        .editable-rate {
        cursor: pointer;
        padding: 5px;
        border-radius: 5px;
        transition: all 0.3s;
    }
    
    .editable-rate:hover {
        background-color: rgba(0, 0, 0, 0.1);
    }
    
    /* Карточка */
    .card {
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        border: none;
        background: rgba(255, 255, 255, 0.85);
    }
    
    .card-header {
        background: white;
        border-bottom: 2px solid #f0f0f0;
        padding: 20px;
        font-weight: bold;
    }
    
    /* Таблицы */
    .table-responsive {
        overflow-x: auto;
    }
    
    .table {
        background-color: transparent;
    }
    
    .table td, .table th {
        background-color: transparent;
    }
    
    .table tbody tr {
        background-color: rgba(200, 200, 200, 0.15);
        transition: background-color 0.3s;
    }
    
    .table tbody tr:nth-child(even) {
        background-color: rgba(180, 180, 180, 0.1);
    }
    
    .table tbody tr:nth-child(odd) {
        background-color: rgba(200, 200, 200, 0.15);
    }
    
    .table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.4);
    }
    
    /* Кнопки */
    .btn-sm {
        padding: 5px 12px;
        font-size: 12px;
        border-radius: 6px;
    }
    
    .btn-warning {
        background: #ffc107;
        border: none;
        color: #333;
    }
    
    .btn-warning:hover {
        background: #e0a800;
    }
    
    .btn-danger {
        background: #dc3545;
        border: none;
        color: white;
    }
    
    .btn-danger:hover {
        background: #c82333;
    }
    
    .btn-primary {
        background: #4361ee;
        border: none;
        color: white;
    }
    
    .btn-primary:hover {
        background: #3a0ca3;
    }
    
    /* Модальные окна */
    .modal-header.bg-primary {
        background: #4361ee;
    }
    
    .modal-header.bg-warning {
        background: #ffc107;
    }
    
    .modal-header .btn-close-white {
        filter: brightness(0) invert(1);
    }
    
    /* Адаптация для планшетов и телефонов */
    @media (max-width: 992px) {
        .card-header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }
        
        .card-header .btn {
            width: 100%;
        }
        
        .table th, .table td {
            padding: 10px 12px;
            font-size: 14px;
        }
        
        .btn-sm {
            padding: 4px 10px;
            font-size: 11px;
        }
    }
    
    @media (max-width: 768px) {
        .card-header {
            padding: 15px;
            font-size: 16px;
        }
        
        .table th, .table td {
            padding: 8px 10px;
            font-size: 12px;
        }
        
        .table {
            min-width: 600px;
        }
        
        .btn-sm {
            padding: 3px 8px;
            font-size: 10px;
        }
        
        .editable-rate {
            padding: 3px;
            font-size: 12px;
        }
    }
    
    @media (max-width: 480px) {
        .card-header {
            padding: 12px;
            font-size: 14px;
        }
        
        .table th, .table td {
            padding: 6px 8px;
            font-size: 11px;
        }
        
        .btn-sm {
            padding: 2px 6px;
            font-size: 9px;
        }
        
        .modal-body {
            padding: 15px;
        }
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center" style="flex-wrap: wrap;">
        Управление кредитами</span>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus me-1"></i> Добавить кредит
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Ставка (%)</th>
                        <th>Мин. сумма</th>
                        <th>Макс. сумма</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $loan)
                    <tr>
                        <td>{{ $loan->id }}</td>
                        <td><strong>{{ $loan->name }}</strong></td>
                        <td>
                            <span class="editable-rate" data-id="{{ $loan->id }}">
                                {{ $loan->annual_rate }}%
                            </span>
                         </td>
                        <td>{{ number_format($loan->min_amount ?? 10000, 0, '', ' ') }} ₽</td>
                        <td>{{ number_format($loan->max_amount ?? 10000000, 0, '', ' ') }} ₽</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-loan" 
                                    data-id="{{ $loan->id }}"
                                    data-name="{{ $loan->name }}"
                                    data-type="{{ $loan->type }}"
                                    data-rate="{{ $loan->annual_rate }}"
                                    data-min="{{ $loan->min_amount ?? 10000 }}"
                                    data-max="{{ $loan->max_amount ?? 10000000 }}"
                                    data-status="{{ $loan->is_active }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-loan" data-id="{{ $loan->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal добавления -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Добавить кредит</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.loans.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Название</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Тип (уникальный)</label>
                        <input type="text" name="type" class="form-control" required placeholder="mortgage, auto, consumer">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Годовая ставка (%)</label>
                        <input type="number" name="annual_rate" class="form-control" step="0.1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Мин. сумма (₽)</label>
                        <input type="number" name="min_amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Макс. сумма (₽)</label>
                        <input type="number" name="max_amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Мин. срок (мес)</label>
                        <input type="number" name="min_term_months" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Макс. срок (мес)</label>
                        <input type="number" name="max_term_months" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal редактирования -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Редактировать кредит</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Название</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Тип</label>
                        <input type="text" id="edit_type" class="form-control" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Годовая ставка (%)</label>
                        <input type="number" name="annual_rate" id="edit_rate" class="form-control" step="0.1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Мин. сумма (₽)</label>
                        <input type="number" name="min_amount" id="edit_min" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Макс. сумма (₽)</label>
                        <input type="number" name="max_amount" id="edit_max" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" id="edit_status" class="form-check-input" value="1">
                            <label class="form-check-label">Активен</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-warning">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Редактирование кредита
    $('.edit-loan').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var type = $(this).data('type');
        var rate = $(this).data('rate');
        var minAmount = $(this).data('min');
        var maxAmount = $(this).data('max');
        var status = $(this).data('status');
        
        $('#edit_name').val(name);
        $('#edit_type').val(type);
        $('#edit_rate').val(rate);
        $('#edit_min').val(minAmount);
        $('#edit_max').val(maxAmount);
        $('#edit_status').prop('checked', status == 1);
        
        $('#editForm').attr('action', '/admin/loans/' + id);
        $('#editModal').modal('show');
    });
    
    // Удаление кредита
    $('.delete-loan').click(function() {
        if(confirm('Удалить этот кредит?')) {
            var id = $(this).data('id');
            $.ajax({
                url: '/admin/loans/' + id,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if(response.success) {
                        location.reload();
                    }
                },
                error: function() {
                    alert('Ошибка при удалении');
                }
            });
        }
    });
});
</script>
@endpush
@endsection