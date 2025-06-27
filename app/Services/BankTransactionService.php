<?php

namespace App\Services;

use App\Models\Bank;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Setting;

class BankTransactionService
{
    public function fetchTransactionsFromWeb2M(Bank $bank)
    {
        $url = sprintf(
            'https://api.web2m.com/historyapiacbv3/%s/%s/%s',
            trim($bank->password),
            trim($bank->account_number),
            trim($bank->token)
        );
        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Web2M Response:', $data);

                if (isset($data['status']) && $data['status'] === true && !empty($data['transactions'])) {
                    return $data['transactions'];
                } else {
                    Log::warning('Không có giao dịch nào.', ['bank_id' => $bank->id]);
                }
            } else {
                Log::error('Lỗi kết nối Web2M', ['status' => $response->status(), 'body' => $response->body()]);
            }
        } catch (\Exception $e) {
            Log::error('Lỗi gọi Web2M: ' . $e->getMessage());
        }

        return null;
    }

    public function processTransactions(array $transactions, Bank $bank)
    {
        $isAutoBankEnabled = Setting::where('name', 'bank_status')->value('value') === '1';

        if (!$isAutoBankEnabled) {
            Log::info("⛔ Auto bank đang tắt. Không xử lý giao dịch.");
            return;
        }
        foreach ($transactions as $txn) {
            // Bỏ qua nếu đã tồn tại
            if (Transaction::where('transactionID', $txn['transactionID'])->exists()) {
                continue;
            }

            // CHỈ xử lý nếu là giao dịch nạp (type = IN)
            if (isset($txn['type']) && strtoupper($txn['type']) !== 'IN') {
                continue;
            }
            $description = $txn['description'];
            $username = null;
            $prefix = Setting::where('name', 'prefix_autobank')->value('value') ?? 'NAPTIEN';
            $regex = sprintf('/%s([a-zA-Z0-9_]+)/i', preg_quote($prefix, '/'));

            if (preg_match($regex, $description, $matches)) {
                $username = $matches[1];
            }


            if ($username) {
                $matchedUser = User::where('username', $username)->first();

                if ($matchedUser) {
                    DB::transaction(function () use ($txn, $bank, $matchedUser) {
                        Transaction::create([
                            'user_id' => $matchedUser->id,
                            'bank_id' => $bank->id,
                            'transactionID' => $txn['transactionID'],
                            'amount' => $txn['amount'],
                            'description' => $txn['description'],
                            'bank' => $bank->account ?? 'UNKNOWN',
                            'unique_id' => uniqid('txn_'),
                            'created_at' => \Carbon\Carbon::createFromFormat('d/m/Y', $txn['transactionDate']),
                            'updated_at' => now(),
                        ]);


                        $matchedUser->increment('balance', $txn['amount']);
                        Log::info("✅ Cộng tiền thành công cho user {$matchedUser->username} ({$txn['amount']}đ)");
                    });
                } else {
                    Log::warning("❌ Không tìm thấy user: {$username} trong description: {$description}");
                }
            } else {
                Log::warning("⚠️ Không tìm thấy username trong description: {$description}");
            }
        }
    }
}
