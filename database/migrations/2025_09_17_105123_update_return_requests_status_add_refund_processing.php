<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1) Tạm đổi sang VARCHAR để không bị kẹt bởi ENUM cũ
        DB::statement("ALTER TABLE `return_requests`
                       MODIFY `status` VARCHAR(32) NOT NULL DEFAULT 'pending'");

        // 2) Chuẩn hoá dữ liệu hiện có
        //  - hạ thấp + trim
        DB::statement("UPDATE `return_requests` SET `status` = LOWER(TRIM(`status`))");

        //  - map các giá trị lạ về 'pending'
        DB::statement("
            UPDATE `return_requests`
            SET `status` = 'pending'
            WHERE `status` IS NULL
               OR `status` = ''
               OR `status` NOT IN ('pending','approved','rejected','refunded','refund_processing')
        ");

        // 3) Đổi lại sang ENUM (đã có 'refund_processing')
        DB::statement("
            ALTER TABLE `return_requests`
            MODIFY `status` ENUM('pending','approved','rejected','refunded','refund_processing')
            NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        // Trả về ENUM cũ (nếu cần)
        DB::statement("
            ALTER TABLE `return_requests`
            MODIFY `status` ENUM('pending','approved','rejected','refunded')
            NOT NULL DEFAULT 'pending'
        ");
    }
};
