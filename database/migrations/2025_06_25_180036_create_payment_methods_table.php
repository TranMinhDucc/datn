<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePaymentMethodsTable extends Migration
{
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // Tên hiển thị
            $table->string('code')->unique();    // Mã duy nhất (ví dụ: cod, wallet, qr_code)
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // ✅ Thêm dữ liệu mặc định
        DB::table('payment_methods')->insert([
            [
                'name' => 'Thanh toán khi nhận hàng',
                'code' => 'cod',
                'description' => 'Khách hàng thanh toán khi nhận hàng (COD)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thanh toán bằng ví',
                'code' => 'wallet',
                'description' => 'Thanh toán bằng số dư tài khoản',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Quét mã QR',
                'code' => 'qr_code',
                'description' => 'Quét mã VNPay hoặc Momo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}

