<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('loan_type');
            $table->decimal('amount', 12, 2);
            $table->decimal('down_payment', 12, 2)->nullable();
            $table->integer('term_months');
            $table->decimal('annual_rate', 5, 2);
            $table->decimal('monthly_payment', 12, 2);
            $table->decimal('total_payment', 12, 2);
            $table->decimal('overpayment', 12, 2);
            $table->json('payment_schedule')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calculations');
    }
};
