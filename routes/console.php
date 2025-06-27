<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| File này dùng để định nghĩa các Artisan command theo lịch cron.
| Laravel 12 không còn sử dụng app/Console/Kernel.php nữa.
|
*/

// Câu lệnh mặc định của Laravel
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ✅ Cron Job: Đồng bộ giao dịch từ Web2M mỗi 5 phút
Schedule::command('sync:bank-transactions')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->sendOutputTo(storage_path('logs/sync_bank_transactions.log'));
