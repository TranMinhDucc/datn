<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GeminiService
{
    private $baseUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent";

    public function ask($prompt)
    {
        $apiKey = setting('gemini_api_key');

        if (!$apiKey) {
            return "⚠️ Chưa cấu hình API Key cho Gemini trong phần Settings!";
        }

        // Phân loại câu hỏi để tối ưu response
        $questionType = $this->classifyQuestion($prompt);

        // System prompt chi tiết và được tối ưu
        $systemPrompt = $this->getSystemPrompt($questionType);

        $payload = [
            "contents" => [
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => $systemPrompt]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "NGỮ CẢNH HIỆN TẠI: " . now()->format('d/m/Y H:i') . " - Hệ thống bán quần áo"]
                    ]
                ],
                [
                    "role" => "user",
                    "parts" => [
                        ["text" => "CÂU HỎI: " . $prompt]
                    ]
                ]
            ],
            "generationConfig" => [
                "temperature" => 0.3,
                "topK" => 40,
                "topP" => 0.8,
                "maxOutputTokens" => 1024,
            ]
        ];

        // Sử dụng cache để tránh gọi API liên tục với cùng câu hỏi
        $cacheKey = 'gemini_response_' . md5($prompt);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        for ($i = 0; $i < 3; $i++) {
            try {
                $response = Http::timeout(30)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post($this->baseUrl . "?key=" . $apiKey, $payload);

                Log::info('Gemini API request', [
                    'prompt' => $prompt,
                    'question_type' => $questionType
                ]);

                Log::info('Gemini API response', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                        $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'];

                        // Thêm gợi ý tiếp theo
                        $suggestion = $this->getFollowUpSuggestion($questionType);
                        $finalResponse = $aiResponse . "\n\n💡 " . $suggestion;

                        // Cache response trong 5 phút
                        Cache::put($cacheKey, $finalResponse, 300);

                        return $finalResponse;
                    }

                    return "🤖 Gemini không trả về nội dung phù hợp.";
                }

                if ($response->status() == 503) {
                    sleep(1);
                    continue;
                }

                break;
            } catch (\Exception $e) {
                Log::error('Gemini API error: ' . $e->getMessage());
                if ($i == 2) {
                    return "⚠️ Lỗi kết nối đến Gemini: " . $e->getMessage();
                }
                sleep(1);
            }
        }

        // Xử lý lỗi
        if ($response->failed()) {
            $data = $response->json();
            if (isset($data['error']['message'])) {
                return "⚠️ Lỗi từ Gemini: " . $data['error']['message'];
            }
            return "⚠️ Lỗi khi gọi Gemini API (HTTP {$response->status()})";
        }

        return "⚠️ Không thể kết nối với Gemini. Vui lòng thử lại sau.";
    }

    /**
     * Phân loại câu hỏi để tối ưu prompt
     */
    private function classifyQuestion($prompt)
    {
        $prompt = mb_strtolower($prompt);

        $questionTypes = [
            'statistical' => ['doanh thu', 'thống kê', 'bao nhiêu', 'tổng', 'trung bình', 'số lượng', 'đếm'],
            'analytical' => ['phân tích', 'so sánh', 'xu hướng', 'tại sao', 'nguyên nhân', 'insight'],
            'predictive' => ['dự báo', 'ước tính', 'tương lai', 'dự đoán', 'forecast'],
            'recommendation' => ['gợi ý', 'nên', 'cách', 'chiến lược', 'giải pháp', 'khuyên'],
            'product' => ['sản phẩm', 'hàng hóa', 'quần áo', 'áo', 'quần', 'đầm', 'váy'],
            'customer' => ['khách hàng', 'user', 'người dùng', 'khách', 'mua hàng']
        ];

        foreach ($questionTypes as $type => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($prompt, $keyword)) {
                    return $type;
                }
            }
        }

        return 'general';
    }

    /**
     * Lấy system prompt phù hợp với loại câu hỏi
     */
    private function getSystemPrompt($questionType)
    {
        $basePrompt = "Bạn là trợ lý AI chuyên nghiệp cho hệ thống bán quần áo trực tuyến. 
            TRẢ LỜI BẰNG TIẾNG VIỆT, thân thiện, tự nhiên như đang trò chuyện.
            Sử dụng emoji phù hợp để tăng tính sinh động.
            Luôn trung thực - nếu không có dữ liệu thì nói không biết.
            Định dạng rõ ràng: xuống dòng, bullet points khi cần thiết.
            
            QUY TẮC:
            - KHÔNG bịa dữ liệu hoặc số liệu
            - Ưu tiên sự chính xác thay vì sáng tạo
            - Nếu cần thêm thông tin, hãy yêu cầu cụ thể
            - Giữ câu trả lời tập trung và có giá trị";

        $typeSpecificPrompts = [
            'statistical' => "Bạn đang trả lời câu hỏi về THỐNG KÊ - tập trung vào con số chính xác, 
                so sánh dữ liệu, và đưa ra context cho các con số.",

            'analytical' => "Bạn đang trả lời câu hỏi về PHÂN TÍCH - tìm insights, xu hướng, 
                nguyên nhân kết quả, và đưa ra nhận định sâu sắc.",

            'predictive' => "Bạn đang trả lời câu hỏi về DỰ BÁO - sử dụng dữ liệu lịch sử 
                để đưa ra dự đoán có cơ sở, luôn đề cập đến độ tin cậy của dự báo.",

            'recommendation' => "Bạn đang trả lời câu hỏi về GỢI Ý - đưa ra giải pháp thực tế, 
                khả thi, và có tính ứng dụng cao cho doanh nghiệp.",

            'product' => "Bạn đang trả lời về SẢN PHẨM - tập trung vào quần áo, xu hướng thời trang, 
                quản lý kho, và chất lượng sản phẩm.",

            'customer' => "Bạn đang trả lời về KHÁCH HÀNG - phân tích hành vi mua hàng, 
                segmentation, và insights về khách hàng."
        ];

        return $basePrompt . "\n\n" . ($typeSpecificPrompts[$questionType] ?? "Hãy trả lời câu hỏi một cách tổng quan và hữu ích.");
    }

    /**
     * Gợi ý câu hỏi tiếp theo dựa trên loại câu hỏi hiện tại
     */
    private function getFollowUpSuggestion($questionType)
    {
        $suggestions = [
            'statistical' => [
                "Bạn muốn xem chi tiết theo tuần/tháng không?",
                "Bạn có muốn so sánh với kỳ trước không?"
            ],
            'analytical' => [
                "Bạn muốn phân tích sâu hơn về khía cạnh nào?",
                "Bạn quan tâm đến xu hướng theo mùa không?"
            ],
            'predictive' => [
                "Bạn muốn dự báo cho khoảng thời gian nào?",
                "Bạn có muốn xem các kịch bản khác nhau không?"
            ],
            'recommendation' => [
                "Bạn muốn gợi ý cho danh mục sản phẩm cụ thể?",
                "Bạn quan tâm đến chiến lược giá hay marketing?"
            ],
            'product' => [
                "Bạn muốn tìm hiểu về danh mục sản phẩm nào?",
                "Bạn quan tâm đến tồn kho hay doanh thu sản phẩm?"
            ],
            'customer' => [
                "Bạn muốn phân tích nhóm khách hàng cụ thể?",
                "Bạn quan tâm đến hành vi mua hay demographics?"
            ],
            'general' => [
                "Bạn muốn tìm hiểu về doanh thu, sản phẩm hay khách hàng?",
                "Tôi có thể giúp bạn phân tích dữ liệu nào?"
            ]
        ];

        $selected = $suggestions[$questionType] ?? $suggestions['general'];
        return $selected[array_rand($selected)];
    }

    /**
     * Phương thức mới: hỏi với context dữ liệu thực
     */
    // ... (phần gọi API tương tự như method ask) là ghi ra luôn cho tôi

    public function askWithContext($prompt, $contextData = [])
    {
        $apiKey = setting('gemini_api_key');
        if (!$apiKey) {
            return "⚠️ Chưa cấu hình API Key cho Gemini!";
        }

        $contextString = "";
        if (!empty($contextData)) {
            $contextString = "DỮ LIỆU THỰC TẾ:\n";
            foreach ($contextData as $key => $value) {
                $contextString .= "- {$key}: {$value}\n";
            }
        }

        $systemPrompt = "Bạn là AI phân tích dữ liệu thực tế cho cửa hàng quần áo. 
        Dựa vào dữ liệu được cung cấp để trả lời chính xác.
        Nếu dữ liệu không đủ, hãy nói rõ điều đó.
        Luôn trung thực và dựa trên facts.
        Trả lời bằng tiếng Việt, ngắn gọn, súc tích.
        Sử dụng emoji phù hợp để dễ hiểu.";

        $payload = [
            "contents" => [
                [
                    "role" => "user",
                    "parts" => [["text" => $systemPrompt]]
                ],
                [
                    "role" => "user",
                    "parts" => [["text" => $contextString]]
                ],
                [
                    "role" => "user",
                    "parts" => [["text" => "CÂU HỎI: " . $prompt]]
                ]
            ],
            "generationConfig" => [
                "temperature" => 0.1, // Rất thấp để đảm bảo chính xác
                "topK" => 20,
                "topP" => 0.7,
                "maxOutputTokens" => 1024,
            ]
        ];

        // PHẦN GỌI API TƯƠNG TỰ NHƯ METHOD ASK
        for ($i = 0; $i < 3; $i++) {
            try {
                $response = Http::timeout(30)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post($this->baseUrl . "?key=" . $apiKey, $payload);

                Log::info('Gemini API with context request', [
                    'prompt' => $prompt,
                    'context_data' => $contextData
                ]);

                Log::info('Gemini API with context response', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                        return $data['candidates'][0]['content']['parts'][0]['text'];
                    }

                    return "🤖 Gemini không trả về nội dung phù hợp cho câu hỏi với context.";
                }

                if ($response->status() == 503) {
                    sleep(1);
                    continue;
                }

                break;
            } catch (\Exception $e) {
                Log::error('Gemini API with context error: ' . $e->getMessage());
                if ($i == 2) {
                    return "⚠️ Lỗi kết nối đến Gemini: " . $e->getMessage();
                }
                sleep(1);
            }
        }

        // Xử lý lỗi
        if (isset($response) && $response->failed()) {
            $data = $response->json();
            if (isset($data['error']['message'])) {
                return "⚠️ Lỗi từ Gemini: " . $data['error']['message'];
            }
            return "⚠️ Lỗi khi gọi Gemini API (HTTP {$response->status()})";
        }

        return "⚠️ Không thể kết nối với Gemini. Vui lòng thử lại sau.";
    }
}
