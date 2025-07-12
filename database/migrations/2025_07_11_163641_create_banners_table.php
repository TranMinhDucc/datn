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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('subtitle')->nullable();          // Create Your Style
            $table->string('title')->nullable();             // New Style For Spring & Summer
            $table->text('description')->nullable();         // mô tả ngắn
            $table->string('main_image')->nullable();        // ảnh người mẫu chính
            $table->string('sub_image_1')->nullable();       // ảnh phụ 1 (sản phẩm)
            $table->string('sub_image_1_name')->nullable();  // tên sản phẩm
            $table->decimal('sub_image_1_price', 10, 2)->nullable(); // giá
            $table->string('sub_image_2')->nullable();       // ảnh phụ 2 (sản phẩm)
            $table->string('sub_image_2_name')->nullable();  
            $table->decimal('sub_image_2_price', 10, 2)->nullable();
            $table->tinyInteger('status')->default(1);       // 1: hiển thị, 0: ẩn
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
