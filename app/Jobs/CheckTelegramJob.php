<?php

namespace App\Jobs;

use App\Services\TelegramService;
use App\Services\Telegram\LowStockNotifier;
use App\Services\Telegram\OrderNotifier;
use App\Services\Telegram\DepositNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckTelegramJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(
        TelegramService $telegram,
        LowStockNotifier $lowStock,
        OrderNotifier $orderNotifier,
    ) {
        $lowStock->handle($telegram);
        $orderNotifier->handle($telegram);
    }
}
