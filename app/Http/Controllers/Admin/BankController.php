<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BankController extends Controller
{
    // Danh sách các ngân hàng
    public function view_payment(Request $request)
    {
        $transactions = Transaction::with('user')
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->username, fn($q) => $q->whereHas('user', fn($q2) =>
            $q2->where('username', 'like', '%' . $request->username . '%')))
            ->when($request->tid, fn($q) => $q->where('transactionID', 'like', '%' . $request->tid . '%'))
            ->when($request->method, fn($q) => $q->where('bank', 'like', '%' . $request->method . '%'))
            ->when($request->description, fn($q) => $q->where('description', 'like', '%' . $request->description . '%'))
            ->when($request->date, fn($q) => $q->whereDate('created_at', $request->date))
            ->latest()
            ->paginate(20);
        $settings = Setting::whereIn('name', [
            'bank_status',
            'bank_min',
            'bank_max',
            'cron_bank_security',
            'prefix_autobank'
        ])->pluck('value', 'name');
        $cronKey = Setting::where('name', 'cron_bank_security')->value('value');
        $cronUrl = url('/cron/sync-bank-transactions?key=' . $cronKey);

        return view('admin.payment_banks.index', compact('transactions', 'settings', 'cronUrl'));
    }
    public function config()
    {
        $banks = Bank::orderByDesc('id')->paginate(10);
        $user = User::all();
        $username = $user->first()->username;
        $settings = Setting::whereIn('name', [
            'bank_status',
            'bank_min',
            'bank_max',
            'cron_bank_security',
            'prefix_autobank'
        ])->pluck('value', 'name');

        return view('admin.payment_banks.config', compact('banks', 'settings', 'username'));
    }
    public function config_update_two(Request $request)
    {
        $request->validate([
            'bank_status' => 'nullable|in:0,1',
            'bank_min' => 'nullable|numeric|min:0',
            'bank_max' => 'nullable|numeric|min:0',
            'cron_bank_security' => 'nullable|string|max:255',
            'prefix_autobank' => 'nullable|string|max:255',
        ]);

        $data = $request->only([
            'bank_status',
            'bank_min',
            'bank_max',
            'cron_bank_security',
            'prefix_autobank'
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['name' => $key],
                ['value' => $value]
            );
        }

        Cache::forget('global_settings'); // Nếu bạn dùng caching

        return redirect()->back()->with('success', 'Cài đặt ngân hàng nâng cao đã được lưu thành công!');
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

        return redirect()->back()->with('success', 'Xóa ngân hàng thành công!');
    }
}
