<?php

namespace App\Services;

use App\Models\Calculation;
use Illuminate\Support\Facades\Auth;

class LoanCalculatorService
{
    /**
     * Расчет ежемесячного платежа по аннуитетной схеме
     */
    public function calculateAnnuityPayment($loanAmount, $annualRate, $termMonths)
    {
        // Ежемесячная ставка = Годовая ставка / 12 / 100
        $monthlyRate = $annualRate / 12 / 100;
        
        if ($monthlyRate == 0) {
            $monthlyPayment = $loanAmount / $termMonths;
        } else {
            // Общая ставка = (1 + Ежемесячная ставка) ^ Срок в месяцах
            $totalRate = pow(1 + $monthlyRate, $termMonths);
            
            // Ежемесячный платеж = Сумма кредита * Ежемесячная ставка * Общая ставка / (Общая ставка - 1)
            $monthlyPayment = $loanAmount * $monthlyRate * $totalRate / ($totalRate - 1);
        }
        
        $totalPayment = $monthlyPayment * $termMonths;
        $overpayment = $totalPayment - $loanAmount;
        
        return [
            'monthly_payment' => round($monthlyPayment, 2),
            'total_payment' => round($totalPayment, 2),
            'overpayment' => round($overpayment, 2),
            'annual_rate' => $annualRate,
            'monthly_rate' => round($monthlyRate * 100, 3)
        ];
    }

    /**
     * Расчет для ипотеки/автокредита с первоначальным взносом
     */
    public function calculateWithDownPayment($cost, $downPaymentPercent, $annualRate, $termMonths)
    {
        $downPayment = $cost * ($downPaymentPercent / 100);
        $loanAmount = $cost - $downPayment;
        
        $result = $this->calculateAnnuityPayment($loanAmount, $annualRate, $termMonths);
        $result['loan_amount'] = round($loanAmount, 2);
        $result['down_payment'] = round($downPayment, 2);
        $result['down_payment_percent'] = $downPaymentPercent;
        $result['property_cost'] = $cost;
        
        return $result;
    }

    /**
     * Сохранение расчета
     */
    public function saveCalculation($data)
    {
        return Calculation::create([
            'user_id' => Auth::id(),
            'loan_type' => $data['loan_type'],
            'amount' => $data['loan_amount'],
            'down_payment' => $data['down_payment'] ?? null,
            'term_months' => $data['term_months'],
            'annual_rate' => $data['annual_rate'],
            'monthly_payment' => $data['monthly_payment'],
            'total_payment' => $data['total_payment'],
            'overpayment' => $data['overpayment'],
            'payment_schedule' => $data['payment_schedule'] ?? null
        ]);
    }
}