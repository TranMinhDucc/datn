<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BestSellerSection;
use Illuminate\Support\Facades\Storage;

class BestSellerSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = BestSellerSection::latest()->paginate(10);
        return view('admin.best-seller.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.best-seller.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ Validate trực tiếp
        $data = $request->validate([
            'title_small'       => 'nullable|string|max:255',
            'title_main'        => 'nullable|string|max:255',
            'subtitle'          => 'nullable|string|max:255',
            'btn_text'          => 'required|string|max:100',
            'btn_url'           => 'nullable|url|max:255',
            'left_image'        => 'nullable|image|max:2048',
            'right_image'       => 'nullable|image|max:2048',
            'side_title'        => 'nullable|string|max:255',
            'side_offer_title'  => 'nullable|string|max:255',
            'side_offer_desc'   => 'nullable|string',
            'side_offer_code'   => 'nullable|string|max:50',
            'is_active'         => 'boolean',
        ]);

        if ($request->hasFile('left_image')) {
            $data['left_image'] = $request->file('left_image')->store('best-seller', 'public');
        }
        if ($request->hasFile('right_image')) {
            $data['right_image'] = $request->file('right_image')->store('best-seller', 'public');
        }

        BestSellerSection::create($data);
        return redirect()->route('admin.best-seller.index')->with('success', 'Created!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BestSellerSection $bestSellerSection)
    {
        return view('admin.best-seller.edit', ['item' => $bestSellerSection]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BestSellerSection $bestSellerSection)
    {
        $data = $request->validate([
            'title_small'       => 'nullable|string|max:255',
            'title_main'        => 'nullable|string|max:255',
            'subtitle'          => 'nullable|string|max:255',
            'btn_text'          => 'required|string|max:100',
            'btn_url'           => 'nullable|url|max:255',
            'left_image'        => 'nullable|image|max:2048',
            'right_image'       => 'nullable|image|max:2048',
            'side_title'        => 'nullable|string|max:255',
            'side_offer_title'  => 'nullable|string|max:255',
            'side_offer_desc'   => 'nullable|string',
            'side_offer_code'   => 'nullable|string|max:50',
            'is_active'         => 'boolean',
        ]);

        if ($request->hasFile('left_image')) {
            if ($bestSellerSection->left_image) Storage::disk('public')->delete($bestSellerSection->left_image);
            $data['left_image'] = $request->file('left_image')->store('best-seller', 'public');
        }
        if ($request->hasFile('right_image')) {
            if ($bestSellerSection->right_image) Storage::disk('public')->delete($bestSellerSection->right_image);
            $data['right_image'] = $request->file('right_image')->store('best-seller', 'public');
        }

        $bestSellerSection->update($data);
        return redirect()->route('admin.best-seller.index')->with('success', 'Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BestSellerSection $bestSellerSection) {
        if ($bestSellerSection->left_image) Storage::disk('public')->delete($bestSellerSection->left_image);
        if ($bestSellerSection->right_image) Storage::disk('public')->delete($bestSellerSection->right_image);
        $bestSellerSection->delete();
        return back()->with('success','Deleted!');
    }
}
