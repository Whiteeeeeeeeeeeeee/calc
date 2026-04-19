<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;

class CalculatorController extends Controller
{
    public function index()
    {
        $loans = Loan::where('is_active', true)->get();
        return view('calculator.index', compact('loans'));
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'loan_type' => 'required',
            'amount' => 'required|numeric|min:1000',
            'term_months' => 'required|integer|min:1|max:360',
            'rate' => 'nullable|numeric'
        ]);

        $loan = Loan::where('type', $request->loan_type)->first();
        $rate = $request->rate ?: ($loan ? $loan->annual_rate : 10);
        
        $monthlyRate = $rate / 12 / 100;
        $totalRate = pow(1 + $monthlyRate, $request->term_months);
        $monthlyPayment = $request->amount * $monthlyRate * $totalRate / ($totalRate - 1);
        $totalPayment = $monthlyPayment * $request->term_months;
        $overpayment = $totalPayment - $request->amount;
        
        return response()->json([
            'success' => true,
            'monthly_payment' => round($monthlyPayment, 0),
            'total_payment' => round($totalPayment, 0),
            'overpayment' => round($overpayment, 0),
            'loan_amount' => round($request->amount, 0)
        ]);
    }
}