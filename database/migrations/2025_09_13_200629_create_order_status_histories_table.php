<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->string('status', 30);              // pending, confirmed, shipping, completed, cancelled...
            $table->string('changed_by')->nullable();  // ai cập nhật (user_id, admin_id hoặc text)
            $table->text('note')->nullable();          // ghi chú
            $table->json('meta')->nullable();          // dữ liệu thêm (GPS, ảnh, v.v.)
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('order_id')
                  ->references('id')->on('orders')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};
