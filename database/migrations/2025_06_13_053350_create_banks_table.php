<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('short_name')->nullable()->comment('Tên viết tắt ngân hàng');
            $table->string('image')->nullable()->comment('Đường dẫn ảnh ngân hàng');
            $table->string('account_name')->comment('Tên chủ tài khoản');
            $table->string('account_number')->comment('Số tài khoản');
            $table->string('password')->nullable()->comment('Mật khẩu tài khoản (nên mã hóa)');
            $table->string('token')->unique()->comment('Token để xác thực Webhook');
            $table->boolean('status')->default(true)->comment('Trạng thái hoạt động: 1 - kích hoạt, 0 - vô hiệu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
