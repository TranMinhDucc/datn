<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('name');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $tab = $request->input('tab', 'general'); // Xác định tab (mặc định là 'general')

        // Xác định validation rules dựa trên tab
        $validationRules = [];
        $data = $request->except(['_token', '_method', 'tab']);

        switch ($tab) {
            case 'general':
                $validationRules = [
                    'title' => 'nullable|string|max:255',
                    'description' => 'nullable|string',
                    'keywords' => 'nullable|string',
                    'author' => 'nullable|string',
                    'address' => 'nullable|string',
                    'vat' => 'nullable|string',
                    'return_policy' => 'nullable|string',
                ];
                break;
            case 'set_images':
                $validationRules = [
                    'logo_light' => 'nullable|image|max:2048',
                    'logo_dark' => 'nullable|image|max:2048',
                    'favicon' => 'nullable|image|max:2048',
                    'image' => 'nullable|image|max:2048',
                ];
                break;
            case 'products':
                $validationRules = [
                    'low_stock_alert' => 'required|integer|min:0',
                ];
                break;
            case 'integrations':
                $validationRules = [
                    // SMTP
                    'smtp_status' => 'nullable|in:0,1',
                    'smtp_host' => 'nullable|string|max:255',
                    'smtp_encryption' => 'nullable|string|max:50',
                    'smtp_port' => 'nullable|integer',
                    'smtp_email' => 'nullable|email|max:255',
                    'smtp_from_name' => 'nullable|max:255',
                    'smtp_password' => 'nullable|string|max:255',

                    // Google Analytics
                    'google_analytics_status' => 'nullable|in:0,1',
                    'google_analytics_id' => 'nullable|string|max:255',

                    // Google Ads
                    'google_ads_status' => 'nullable|in:0,1',
                    'google_ads_id' => 'nullable|string|max:255',

                    // ChatGPT
                    'chatgpt_api_key' => 'nullable|string|max:255',
                    'chatgpt_model' => 'nullable|string|max:50',

                    // Gemini
                    'gemini_api_key' => 'nullable|string|max:255',

                    // Telegram
                    'telegram_status' => 'nullable|in:0,1',
                    'telegram_token' => 'nullable|string|max:255',
                    'telegram_chat_id' => 'nullable|string|max:50',
                    'telegram_url' => 'nullable|string|max:255',
                    'telegram_proxy' => 'nullable|string|max:255',
                    'telegram_proxy_type' => 'nullable|string|max:20',

                    // Check live Gmail
                    'api_check_live_gmail' => 'nullable|string|max:255',
                    'api_key_check_live_gmail' => 'nullable|string|max:255',
                    'time_limit_check_live_gmail' => 'nullable|integer|min:0',

                    // Check live Instagram
                    'api_check_live_instagram' => 'nullable|string|max:255',
                    'api_key_check_live_instagram' => 'nullable|string|max:255',
                    'time_limit_check_live_instagram' => 'nullable|integer|min:0',
                ];
                break;
            case 'notifications':
                $validationRules = [
                    'telegram_low_stock_template' => 'nullable',
                ];
                break;
        }

        $validatedData = $request->validate($validationRules);
        // Xử lý upload ảnh
        $imageFields = ['logo_light', 'logo_dark', 'favicon', 'image'];
        // $settings = Setting::whereIn('name', $imageFields)->get()->keyBy('name');
        $settings = Setting::all()->keyBy('name');
        $tab = $request->input('tab');
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Xóa ảnh cũ
                if (isset($settings[$field]) && $settings[$field]->value) {
                    $oldPath = $settings[$field]->value; // Giả sử value đã chứa 'settings/xxx'
                    if (Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }
                // Lưu ảnh mới
                $path = $request->file($field)->store('settings', 'public');
                $validatedData[$field] = $path;
            }
        }
        foreach ($validatedData as $key => $value) {
            Setting::where('name', $key)->update(['value' => $value]);
        }
        Cache::forget('global_settings');
        return redirect()->route('admin.settings.index', ['tab' => $tab])->with('success', 'Cài đặt đã được cập nhật.');
    }

    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        if ($setting->value && in_array($setting->name, ['logo_light', 'logo_dark', 'favicon', 'image'])) {
            Storage::delete('settings/' . $setting->value);
        }
        $setting->delete();
        return redirect()->route('admin.settings.index')->with('success', 'Cài đặt đã được xóa.');
    }
}
