<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    public function quickAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $method = ShippingMethod::create([
            'name' => $request->name,
        ]);

        return response()->json($method);
    }
}
