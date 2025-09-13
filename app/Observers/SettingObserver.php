<?php

namespace App\Observers;

use App\Models\Setting;

class SettingObserver
{
    public function created(Setting $setting)
    {
        clear_settings_cache();
    }

    public function updated(Setting $setting)
    {
        clear_settings_cache();
    }

    public function deleted(Setting $setting)
    {
        clear_settings_cache();
    }
}
