<?php

namespace App\Services\Telegram;

use App\Models\ProductVariant;
use App\Services\TelegramService;

class LowStockNotifier
{
    public function handle(TelegramService $telegram)
    {
        $threshold = setting('low_stock_alert') ?? 10;

        $variants = ProductVariant::with('product')
            ->where('quantity', '<=', $threshold)
            ->get();

        foreach ($variants as $variant) {
            $message = render_telegram_template('telegram_low_stock_template', [
                'product' => $variant->product->name . ' - ' . $variant->variant_name,
                'stock'   => $variant->quantity,
                'time'    => now()->format('d/m/Y H:i'),
            ]);

            $telegram->sendMessage($message);
        }
    }
}
