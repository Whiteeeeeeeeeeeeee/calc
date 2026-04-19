<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'type', 'name', 'annual_rate', 'min_amount', 'max_amount',
        'min_term_months', 'max_term_months', 'min_down_payment_percent', 'is_active'
    ];

    public function getMonthlyRateAttribute()
    {
        return $this->annual_rate / 12 / 100;
    }
}
