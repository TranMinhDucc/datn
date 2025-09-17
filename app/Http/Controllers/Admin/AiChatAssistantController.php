<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GeminiService;
use App\Services\AiChatService;

class AiChatAssistantController extends Controller
{
    protected $gemini;
    protected $aiChat;

    public function __construct(GeminiService $gemini, AiChatService $aiChat)
    {
        $this->gemini = $gemini;
        $this->aiChat = $aiChat;
    }

    public function index()
    {
        return view('admin.aichat');
    }

    public function ask(Request $request)
    {
        $prompt = $request->input('message');

        // Toàn bộ rule + fallback Gemini sẽ xử lý trong AiChatService
        $aiResponse = $this->aiChat->process($prompt, $this->gemini);

        return response()->json([
            'success' => true,
            'answer' => $aiResponse // đổi message -> answer
        ]);
    }
}
