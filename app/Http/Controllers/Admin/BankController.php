<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankController extends Controller
{
    // Danh sách các ngân hàng
    public function view_payment()
    {
        return view('admin.payment_banks.index');
    }

    public function config()
    {
        $banks = Bank::orderByDesc('id')->paginate(10);
        return view('admin.payment_banks.config', compact('banks'));
    }

    public function config_add(Request $request)
    {

        $validated = $request->validate([
            'short_name'     => 'required|string|max:50',
            'image'          => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'account_name'   => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'password'       => 'nullable|string|max:100',
            'token'          => 'nullable|string|unique:banks,token',
            'status'         => 'nullable|boolean',
        ]);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banks', 'public');
            $validated['image'] = $path;
        }

        Bank::create($validated);
        return redirect()->back()->with('success', 'Thêm ngân hàng thành công!');
    }

    public function config_edit($id)
    {
        $bank = Bank::findOrFail($id);
        return view('admin.payment_banks.config_edit', compact('bank'));
    }

    public function config_update(Request $request, $id)
    {
        $validated = $request->validate([
            'short_name'     => 'required|string|max:50',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'account_name'   => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'password'       => 'nullable|string|max:100',
            'token'          => 'nullable|string|unique:banks,token,' . $id,
            'status'         => 'nullable|boolean',
        ]);

        $bank = Bank::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($bank->image && Storage::disk('public')->exists($bank->image)) {
                Storage::disk('public')->delete($bank->image);
            }

            $validated['image'] = $request->file('image')->store('banks', 'public');
        }

        $bank->update($validated);

        return redirect()->route('admin.bank.config')->with('success', 'Cập nhật ngân hàng thành công!');
    }

    // Thêm mới ngân hàng
    public function store(Request $request) {}

    // Cập nhật ngân hàng
    public function update(Request $request, $id)
    {
        $bank = Bank::findOrFail($id);

        $validated = $request->validate([
            'short_name'     => 'nullable|string|max:50',
            'image'          => 'nullable|string',
            'account_name'   => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'password'       => 'nullable|string|max:100',
            'token'          => 'required|string|unique:banks,token,' . $bank->id,
            'status'         => 'nullable|boolean',
        ]);

        $bank->update($validated);
        return response()->json(['message' => 'Bank updated successfully', 'data' => $bank]);
    }

    // Xoá ngân hàng
    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        return response()->json(['message' => 'Bank deleted successfully']);
    }
}
