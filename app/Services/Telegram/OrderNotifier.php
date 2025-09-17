<?php

namespace App\Services\Telegram;

use App\Models\Order;
use App\Services\TelegramService;

class OrderNotifier
{
    public function handle(TelegramService $telegram)
    {
        $orders = Order::where('created_at', '>=', now()->subMinutes(5))->get();

        foreach ($orders as $order) {
            $message = render_telegram_template('telegram_order_template', [
                'customer' => $order->user->name,
                'product'  => $order->items->pluck('product.name')->join(', '),
                'quantity' => $order->items->sum('quantity'),
                'total'    => number_format($order->total) . ' VND',
                'time'     => now()->format('d/m/Y H:i'),
            ]);

            $telegram->sendMessage($message);
        }
    }
}
