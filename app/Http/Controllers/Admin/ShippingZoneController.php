<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    public function quickAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);


        $zone = ShippingZone::create([
            'name' => $request->name,
        ]);

        return response()->json($zone);
    }
}
