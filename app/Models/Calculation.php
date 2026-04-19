<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calculation extends Model
{
    protected $fillable = [
        'user_id', 'loan_type', 'amount', 'down_payment', 'term_months',
        'annual_rate', 'monthly_payment', 'total_payment', 'overpayment', 'payment_schedule'
    ];

    protected $casts = [
        'payment_schedule' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
