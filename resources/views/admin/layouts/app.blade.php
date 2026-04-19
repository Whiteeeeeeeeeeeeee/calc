<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Админ панель - Кредитный калькулятор</title>
    <meta name="description" content="Административная панель кредитного калькулятора. Управление типами кредитов и пользователями.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        
        body {
            background: #b8d4f0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        
        .sidebar {
            min-height: 100vh;
            background: #b8d4f0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        }
        
        
        .sidebar .nav-link {
            color: #2c3e50;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 10px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link:hover {
            background: rgba(67,97,238,0.1);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: #4361ee;
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 25px;
            margin-right: 10px;
        }
        
        
        .sidebar hr {
            background-color: #c0c0c0;
            margin: 10px 0;
        }
        
        .main-content {
            padding: 20px;
        }
        
        
        .card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: none;
            background: white;
        }
        
        .card-header {
            background: white;
            border-bottom: 2px solid #f0f0f0;
            padding: 20px;
            font-weight: bold;
        }
        
        .btn {
            border-radius: 8px;
            padding: 8px 20px;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .stat-card {
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        /* Таблицы */
        .table tbody tr {
            background-color: rgba(255, 255, 255, 0.4);
            transition: background-color 0.3s;
        }
        
        .table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.8);
        }
        
        .table {
            background-color: transparent;
        }
        
        .table td, .table th {
            background-color: transparent;
        }
        
        .card-body {
            background: white;
        }
        
        /* Верхняя навигация */
        .navbar-light.bg-white {
            background: white;
        }
        
               
        @media (max-width: 992px) {
            .sidebar .nav-link {
                padding: 10px 15px;
                font-size: 14px;
            }
            
            .sidebar .nav-link i {
                width: 22px;
                font-size: 14px;
            }
            
            .card-header {
                padding: 15px;
                font-size: 16px;
            }
            
            .main-content {
                padding: 15px;
            }
            
            .table th, .table td {
                padding: 10px 12px;
                font-size: 14px;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -260px;
                top: 0;
                width: 260px;
                height: 100%;
                z-index: 1000;
                transition: left 0.3s ease;
                box-shadow: 2px 0 15px rgba(0,0,0,0.2);
            }
            
            .sidebar.open {
                left: 0;
            }
            
            .menu-toggle {
                display: block;
                position: fixed;
                top: 15px;
                left: 15px;
                z-index: 1001;
                background: #4361ee;
                border: none;
                color: white;
                font-size: 22px;
                padding: 8px 12px;
                border-radius: 8px;
                cursor: pointer;
            }
            
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            
            .overlay.active {
                display: block;
            }
            
            .col-md-10 {
                width: 100%;
            }
            
            .main-content {
                padding: 12px;
                margin-top: 50px;
            }
            
            .navbar {
                margin-left: 50px;
            }
            
            .navbar .container-fluid {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            
            .navbar-brand {
                font-size: 16px;
            }
            
            .d-flex.align-items-center {
                flex-direction: column;
                gap: 10px;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
            
            .table {
                min-width: 600px;
            }
            
            .table th, .table td {
                padding: 8px 10px;
                font-size: 12px;
            }
            
            .card-header {
                font-size: 14px;
                padding: 12px;
            }
            
            .btn-sm {
                padding: 4px 8px;
                font-size: 11px;
            }
        }
        
        @media (min-width: 769px) {
            .menu-toggle, .overlay {
                display: none;
            }
            
            .sidebar {
                position: relative;
                left: 0;
            }
        }
        
        @media (max-width: 480px) {
            .main-content {
                padding: 10px;
            }
            
            .card-header {
                font-size: 13px;
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
            
            .navbar-brand {
                font-size: 14px;
            }
            
            .text-muted {
                font-size: 11px;
            }
        }
    </style>
</head>
<body>
        <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
    </button>
    <div class="overlay" id="overlay"></div>
    
    <div class="container-fluid">
        <div class="row">
                <div class="col-md-2 p-0 sidebar" id="sidebar">
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('admin.loans*') ? 'active' : '' }}" href="{{ route('admin.loans.index') }}">Управление кредитами
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"> Управление пользователями
                    </a>
                    <hr class="bg-light">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="fas fa-arrow-left"></i> На сайт
                    </a>
                </nav>
            </div>
            <!-- Main Content -->
            <div class="col-md-10 p-0">
                <nav class="navbar navbar-light bg-white shadow-sm">
                    
                    <div class="container-fluid" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <span class="navbar-brand mb-0 h5" style="display: flex; align-items: center; font-size: clamp(14px, 4vw, 18px);">
                            Управление сервисом
                        </span>
                        <div class="d-flex align-items-center" style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                            <span class="text-muted" style="display: flex; align-items: center; gap: 5px; font-size: clamp(11px, 3vw, 14px);">
                                <i class="fas fa-user"></i> 
                                <strong>{{ Auth::user()->name }}</strong>
                                <small class="text-secondary">({{ Auth::user()->role }})</small>
                            </span>
                        </div>
                        <div>
                             <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="display: flex; align-items: center; gap: 5px; padding: clamp(4px, 2vw, 8px) clamp(8px, 3vw, 12px); font-size: clamp(10px, 2.5vw, 13px);">
                                    <i class="fas fa-sign-out-alt"></i> 
                                    Выйти
                                </button>
                            </form>
                         </div>
                    </div>
                </nav>

                <div class="main-content">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: clamp(12px, 3vw, 14px); padding: clamp(8px, 2vw, 12px);">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: clamp(12px, 3vw, 14px); padding: clamp(8px, 2vw, 12px);">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Скрипт для мобильного меню
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                    overlay.classList.toggle('active');
                });
            }
            
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>