<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Liên kết đến người đăng sản phẩm (tùy chọn)
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('name');
            $table->string('slug')->nullable()->unique();
            $table->text('images')->nullable();
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->string('code')->nullable()->unique(); // mã sản phẩm duy nhất

            $table->decimal('price', 12, 2)->default(0);     // giá bán
            $table->decimal('cost', 12, 2)->default(0);      // giá vốn
            $table->decimal('discount', 5, 2)->default(0);   // phần trăm giảm

            $table->integer('min')->default(0);              // số lượng tối thiểu hiển thị
            $table->integer('max')->default(0);              // số lượng tối đa hiển thị
            $table->integer('sold')->default(0);             // đã bán
            $table->integer('quantity')->default(0);         // tổng tồn kho

            $table->integer('min_purchase_quantity')->default(1);  // tối thiểu mua
            $table->integer('max_purchase_quantity')->default(100); // tối đa mua

            $table->unsignedBigInteger('category_id')->nullable(); // danh mục
            $table->tinyInteger('status')->default(1);             // 0: ẩn, 1: hiển thị
            $table->boolean('check_live')->default(false);         // kiểm tra live (nếu bán tài khoản)

            $table->text('text_txt')->nullable();       // dữ liệu thêm (file txt)
            $table->string('short_desc')->nullable();   // mô tả ngắn

            $table->timestamps();

            // Liên kết khóa ngoại đến categories
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();

            // Nếu có dùng user_id để quản lý người tạo:
            // $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
