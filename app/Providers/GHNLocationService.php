<?php

namespace App\Services;

use App\Models\PartnerLocationCode;

class GHNLocationService
{
    public function getDistrictCode($districtId)
    {
        return PartnerLocationCode::where('type', 'district')
            ->where('location_id', $districtId)
            ->value('partner_code');
    }

    public function getWardCode($wardId)
    {
        return PartnerLocationCode::where('type', 'ward')
            ->where('location_id', $wardId)
            ->value('partner_code');
    }

    public function getProvinceCode($provinceId)
    {
        return PartnerLocationCode::where('type', 'province')
            ->where('location_id', $provinceId)
            ->value('partner_code');
    }
}
