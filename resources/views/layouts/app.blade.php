<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Онлайн калькулятор онлайн — рассчитать кредит на сайте кредитный</title>
    <meta name="description" content="Рассчитайте кредит онлайн на нашем калькуляторе. Ипотека, автокредит, потребительский кредит. Быстрый расчет ежемесячного платежа, переплаты и общей суммы выплат.">
    <meta name="keywords" content="кредитный калькулятор, рассчитать кредит, калькулятор ипотеки, калькулятор автокредита, расчет кредита онлайн">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
    background: #b8d4f0;
    min-height: 100vh;
    padding: 50px 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Шапка - синий фон, белые буквы */
.navbar {
    background: #4361ee;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.navbar .navbar-brand {
    color: white;
    font-weight: bold;
}

.navbar .nav-link {
    color: white;
}

.navbar .navbar-brand i {
    color: white;
}

.navbar .nav-link:hover {
    color: #d4d4d4;
}

.main-card {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    border: none;
}

.card-header {
    background: #4361ee;
    padding: 30px;
    border-bottom: none;
}

.card-header h3 {
    color: white;
}

/* Остальные ваши стили... */
                .loan-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            background: white;
            text-align: center;
            padding: 20px;
            margin-bottom: 15px;
        }
                .loan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .loan-card.selected {
            border-color: #4361ee;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            box-shadow: 0 5px 20px rgba(67,97,238,0.3);
        }
        
        .loan-icon {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #4361ee;
        }
        
        .loan-name {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }
        
        .loan-rate {
            font-size: 0.9rem;
            color: #666;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 12px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.2rem rgba(67,97,238,0.25);
        }
        
        .btn-calculate {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-size: 1.1rem;
            font-weight: bold;
            transition: transform 0.2s;
        }
        
        .btn-calculate:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67,97,238,0.4);
        }
        
        .result-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .result-card:hover {
            transform: translateY(-3px);
        }
        
        .result-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #4361ee;
            margin: 10px 0 0;
        }
        
        .result-label {
            font-size: 0.85rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .hidden {
            display: none;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        input[type="number"] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Навигационная панель -->
        <nav class="navbar navbar-expand-lg rounded-3 shadow-sm mb-4" style="background: #4361ee;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}" style="color: white;">
            <i class="fas fa-calculator me-2" style="color: white;"></i>Онлайн калькулятор
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon" style="background-color: white; border-radius: 3px;"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}" style="color: white;">
                            <i class="fas fa-sign-in-alt me-1"></i>Вход
                        </a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}" style="color: white;">
                                <i class="fas fa-user-plus me-1"></i>Регистрация
                            </a>
                        </li>
                    @endif
                @else
                    @if(auth()->user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/admin') }}" style="color: white;">
                                ⚙️ Админ панель
                            </a>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" style="color: white;">
                            <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-1"></i>Выйти
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
        <!-- Основной контент -->
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>