<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected $token;
    protected $chatId;
    protected $url;
    protected $status;

    public function __construct()
    {
        $this->status = setting('telegram_status');
        $this->token  = setting('telegram_token');
        $this->chatId = setting('telegram_chat_id');
        $this->url    = rtrim(setting('telegram_url') ?? 'https://api.telegram.org', '/');
    }

    public function sendMessage($message)
    {
        if (!$this->status || !$this->token || !$this->chatId) {
            Log::warning("⚠️ Telegram config missing!");
            return false;
        }

        $endpoint = "{$this->url}/bot{$this->token}/sendMessage";

        $response = Http::post($endpoint, [
            'chat_id'    => $this->chatId,
            'text'       => $message,
            'parse_mode' => 'HTML',
        ]);

        if ($response->failed()) {
            Log::error("❌ Telegram error: " . $response->body());
        }

        return $response->successful();
    }
}
