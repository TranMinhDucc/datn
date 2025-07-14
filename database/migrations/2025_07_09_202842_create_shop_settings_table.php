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
        Schema::create('shop_settings', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name');               // Tên cửa hàng
            $table->string('shop_phone');              // SĐT cửa hàng
            $table->string('address');                 // Địa chỉ cụ thể
            $table->unsignedBigInteger('province_id'); // Mã tỉnh
            $table->unsignedBigInteger('district_id'); // Mã huyện
            $table->unsignedBigInteger('ward_id');     // Mã phường
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_settings');
    }
};
