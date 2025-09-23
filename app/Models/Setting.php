<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['name', 'value'];
     public static function getValue($name, $default = null)
    {
        return optional(self::where('name', $name)->first())->value ?? $default;
    }
     public function getToken(): ?string
    {
        return Setting::where('name', 'ghn_token')->value('value');
    }

    public function getShopId(): ?string
    {
        return Setting::where('name', 'ghn_shop_id')->value('value');
    }
}
