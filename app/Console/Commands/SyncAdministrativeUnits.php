<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AdministrativeUnitService;

class SyncAdministrativeUnits extends Command
{
    protected $signature = 'sync:admin-units';
    protected $description = 'Đồng bộ dữ liệu tỉnh/quận/xã từ API';

    public function handle(AdministrativeUnitService $service)
    {
        $this->info('🚀 Bắt đầu đồng bộ...');
        $service->syncAll();
        $this->info('✅ Đã đồng bộ toàn bộ dữ liệu hành chính xong.');
    }
}
