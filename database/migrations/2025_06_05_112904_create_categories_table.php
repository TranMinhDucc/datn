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
        Schema::create('categories', callback: function (Blueprint $table) {
            $table->id(); // chỉ gọi 1 lần
            $table->string('name');
            $table->unsignedBigInteger(column: 'parent_id')->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->nullable();
            // Khóa ngoại tham chiếu đến bảng chính nó
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');

            $table->timestamps(); // tạo cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
