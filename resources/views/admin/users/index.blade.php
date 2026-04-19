@extends('admin.layouts.app')

@section('content')
<style>
    /* Прозрачные строки таблицы */
    .table tbody tr {
        background-color: rgba(200, 200, 200, 0.15);
        backdrop-filter: blur(0px);
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
    
    .table {
        background-color: transparent;
    }
    
    .table td, .table th {
        background-color: transparent;
    }
    
    .card-body {
        background: transparent;
    }
    
    .card {
        background: rgba(255, 255, 255, 0.85);
    }
    
    /* Бейджи для ролей */
    .role-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .role-admin {
        background: #dc3545;
        color: white;
    }
    
    .role-user {
        background: #28a745;
        color: white;
    }
    
       
    /* Планшеты (768px - 992px) */
    @media (max-width: 992px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            min-width: 650px;
        }
        
        .table th, .table td {
            padding: 10px 12px;
            font-size: 14px;
        }
        
        .card {
            margin: 10px;
        }
        
        .card-header {
            font-size: 18px;
            padding: 15px;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .role-badge {
            font-size: 11px;
            padding: 3px 8px;
        }
    }
    
    /* Мобильные телефоны (до 768px) */
    @media (max-width: 768px) {
        .card-header {
            font-size: 16px;
            padding: 12px;
            text-align: center;
        }
        
        .table th, .table td {
            padding: 8px 10px;
            font-size: 12px;
        }
        
        .btn-sm {
            padding: 4px 8px;
            font-size: 11px;
        }
        
        .btn-sm i {
            margin-right: 3px;
        }
        
        .role-badge {
            font-size: 10px;
            padding: 2px 6px;
        }
        
        .main-content {
            padding: 10px;
        }
    }
    
    /* Маленькие телефоны (до 480px) */
    @media (max-width: 480px) {
        .card-header {
            font-size: 14px;
            padding: 10px;
        }
        
        .table th, .table td {
            padding: 6px 8px;
            font-size: 11px;
        }
        
        .btn-sm {
            padding: 3px 6px;
            font-size: 10px;
        }
    }
   
        .table tbody tr {
           height: 60px;
    }
        .table td, .table th {
       vertical-align: middle;
       padding: 12px 15px;
    }
       .table .btn-sm {
       min-width: 75px;
       height: 32px;
    }
</style>

<div class="card">
    <div class="card-header">
         Управление пользователями
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'admin')Администратор
                            @else Пользователь
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            @if($user->id !== auth()->id())
                                <button class="btn btn-sm btn-danger delete-user" data-id="{{ $user->id }}">
                                    <i class="fas fa-trash"></i> Удалить
                                </button>
                            @else
                                <span class="text-muted">
                                    <i class="fas fa-check-circle"></i> Вы
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.delete-user').click(function() {
        if(confirm('Удалить пользователя?')) {
            var id = $(this).data('id');
            $.ajax({
                url: '/admin/users/' + id,
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
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