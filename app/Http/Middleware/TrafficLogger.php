<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\TrafficLog;
use Illuminate\Support\Facades\Log;

class TrafficLogger
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            // Detect user info
            $userId = auth()->check() ? auth()->id() : null;
            $ip = $request->ip();
            $userAgent = $request->userAgent();
            $referer = $request->headers->get('referer');
            $sessionId = $request->session()->getId();

            // UTM parameters
            $utmSource = $request->get('utm_source');
            $utmMedium = $request->get('utm_medium');
            $utmCampaign = $request->get('utm_campaign');

            // Chuẩn hóa source
            $source = 'direct'; // mặc định
            if ($utmSource) {
                $source = $this->mapSource($utmSource);
            } elseif ($referer && !str_contains($referer, $request->getHost())) {
                $source = 'referral';
            }

            // Save log
            // Save log
            TrafficLog::create([
                'user_id'      => $userId,
                'session_id'   => $sessionId,
                'source'       => $source ?? 'direct',  // ✅ bắt buộc có
                'referer'      => $referer,
                'utm_source'   => $utmSource,
                'utm_medium'   => $utmMedium,
                'utm_campaign' => $utmCampaign,
                'ip'           => $ip,
                'user_agent'   => $userAgent,
                'visited_at'   => now(),
            ]);
        } catch (\Exception $e) {
            Log::error("TrafficLogger error: " . $e->getMessage());
        }

        return $response;
    }

    private function mapSource($utmSource)
    {
        $utmSource = strtolower($utmSource);

        // Gom tất cả social (bắt cả domain con, ví dụ fb.com, m.facebook.com, youtube.com)
        if (preg_match('/facebook|fb\.com|instagram|zalo|tiktok|twitter|linkedin|youtube/', $utmSource)) {
            return 'social';
        }

        // Các công cụ tìm kiếm → referral
        if (preg_match('/google|bing|coccoc|yahoo/', $utmSource)) {
            return 'referral';
        }

        // Nếu không match gì thì trả nguyên
        return $utmSource;
    }
}
