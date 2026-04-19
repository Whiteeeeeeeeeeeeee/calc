<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // mortgage, auto, consumer
            $table->string('name');
            $table->decimal('annual_rate', 5, 2);
            $table->decimal('min_amount', 12, 2);
            $table->decimal('max_amount', 12, 2);
            $table->integer('min_term_months');
            $table->integer('max_term_months');
            $table->decimal('min_down_payment_percent', 5, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Вставляем данные
        DB::table('loans')->insert([
            [
                'type' => 'mortgage',
                'name' => 'Ипотека',
                'annual_rate' => 9.6,
                'min_amount' => 100000,
                'max_amount' => 30000000,
                'min_term_months' => 12,
                'max_term_months' => 360,
                'min_down_payment_percent' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => 'auto',
                'name' => 'Автокредит',
                'annual_rate' => 3.5,
                'min_amount' => 50000,
                'max_amount' => 5000000,
                'min_term_months' => 6,
                'max_term_months' => 84,
                'min_down_payment_percent' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => 'consumer',
                'name' => 'Потребительский',
                'annual_rate' => 14.5,
                'min_amount' => 10000,
                'max_amount' => 5000000,
                'min_term_months' => 3,
                'max_term_months' => 60,
                'min_down_payment_percent' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
