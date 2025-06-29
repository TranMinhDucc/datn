<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bank;
use App\Services\BankTransactionService;

class SyncBankTransactions extends Command
{
    protected $signature = 'sync:bank-transactions';
    protected $description = 'Đồng bộ giao dịch ngân hàng từ Web2M và cộng tiền cho người dùng';

    public function handle()
    {
        $this->info('Đang bắt đầu đồng bộ...');

        $banks = Bank::all();
        $service = new BankTransactionService();

        foreach ($banks as $bank) {
            $transactions = $service->fetchTransactionsFromWeb2M($bank);

            if ($transactions) {
                $service->processTransactions($transactions, $bank);
                $this->info("✓ Đồng bộ xong bank: {$bank->account}");
            } else {
                $this->warn("⚠ Không lấy được giao dịch từ bank: {$bank->account}");
            }
        }

        $this->info('✔ Toàn bộ giao dịch đã được xử lý.');
    }
}
