<?php

use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\CalculationController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/', [CalculatorController::class, 'index'])->name('home');
Route::post('/calculate', [CalculatorController::class, 'calculate'])->name('calculate');

// Маршруты аутентификации
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
    
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }
    
    return back()->withErrors([
        'email' => 'Неверный email или пароль.',
    ]);
});

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);
    
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user',
    ]);
    
    Auth::login($user);
    
    return redirect('/');
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Админ маршруты - ТОЛЬКО ДЛЯ АДМИНИСТРАТОРОВ
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::get('/loans', [LoanController::class, 'index'])->name('admin.loans.index');
    Route::post('/loans', [LoanController::class, 'store'])->name('admin.loans.store');
    Route::put('/loans/{loan}', [LoanController::class, 'update'])->name('admin.loans.update');
    Route::put('/loans/{loan}/rate', [LoanController::class, 'updateRate'])->name('admin.loans.rate');
    Route::put('/loans/{loan}/toggle', [LoanController::class, 'toggle'])->name('admin.loans.toggle');
    Route::delete('/loans/{loan}', [LoanController::class, 'destroy'])->name('admin.loans.destroy');
    
    //Route::get('/calculations', [CalculationController::class, 'index'])->name('admin.calculations.index');
   // Route::delete('/calculations/{calculation}', [CalculationController::class, 'destroy'])->name('admin.calculations.destroy');
    
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('admin.users.update-role');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});