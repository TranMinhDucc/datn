<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private $baseUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent";

    public function ask($prompt)
    {
        $apiKey = setting('gemini_api_key');

        if (!$apiKey) {
            return "⚠️ Chưa cấu hình GEMINI_API_KEY trong Settings!";
        }

        $payload = [
            "contents" => [
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "Bạn là trợ lý phân tích dữ liệu bán hàng, hãy trả lời ngắn gọn và thân thiện."]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ]
        ];

        for ($i = 0; $i < 3; $i++) { // thử tối đa 3 lần
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . "?key=" . $apiKey, $payload);

            Log::info('Gemini API response', [
                'status' => $response->status(),
                'body'   => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text']
                    ?? "🤖 Không có phản hồi từ Gemini.";
            }

            // Nếu 503 thì retry sau 1s
            if ($response->status() == 503) {
                sleep(1);
                continue;
            }

            // Các lỗi khác thì break luôn
            break;
        }

        $data = $response->json();
        if (isset($data['error'])) {
            return "⚠️ Gemini báo lỗi: {$data['error']['message']}";
        }

        return "⚠️ Lỗi khi gọi Gemini API (HTTP {$response->status()})";
    }
}
