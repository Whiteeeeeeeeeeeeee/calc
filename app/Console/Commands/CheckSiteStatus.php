<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckSiteStatus extends Command
{
    protected $signature = 'site:check-status';
    protected $description = 'Проверка доступности сайта';

    public function handle()
    {
        $this->info('Проверка доступности сайта...');
        
        try {
            $response = Http::timeout(10)->get(config('app.url'));
            
            if ($response->successful()) {
                $this->info('✅ Сайт доступен');
                Log::info('Сайт доступен', ['status' => $response->status()]);
            } else {
                $this->error('❌ Сайт недоступен! Код: ' . $response->status());
                Log::error('Сайт недоступен', ['status' => $response->status()]);
                $this->sendEmailAlert('❌ Сайт недоступен! Код ошибки: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('❌ Ошибка подключения: ' . $e->getMessage());
            Log::error('Ошибка проверки сайта', ['error' => $e->getMessage()]);
            $this->sendEmailAlert('❌ Ошибка проверки сайта: ' . $e->getMessage());
        }
    }
    
    protected function sendEmailAlert($message)
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
        
        try {
            Mail::raw($message, function ($mail) use ($adminEmail) {
                $mail->to($adminEmail)
                    ->subject('⚠️ КРИТИЧЕСКОЕ: Проблема с доступностью сайта')
                    ->from(env('MAIL_FROM_ADDRESS', 'noreply@example.com'), 'Мониторинг сайта');
            });
            
            $this->info('Уведомление отправлено на почту: ' . $adminEmail);
            Log::info('Email уведомление отправлено', ['to' => $adminEmail]);
        } catch (\Exception $e) {
            $this->error('Ошибка отправки email: ' . $e->getMessage());
            Log::error('Ошибка отправки email', ['error' => $e->getMessage()]);
        }
    }
}