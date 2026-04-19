<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Calculation;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Перенаправляем на страницу управления кредитами
        return redirect()->route('admin.loans.index');
    }
}
