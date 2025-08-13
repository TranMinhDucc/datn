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
        Schema::table('return_requests', function (Blueprint $table) {
            // Thêm cột exchange_order_id
            $table->unsignedBigInteger('exchange_order_id')->nullable()->after('order_id')->index();

            // Khóa ngoại tới bảng orders
            $table->foreign('exchange_order_id')
                ->references('id')
                ->on('orders')
                ->nullOnDelete(); // Nếu đơn mới bị xóa thì set NULL
        });
    }

    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            // Xóa khóa ngoại trước
            $table->dropForeign(['exchange_order_id']);
            $table->dropColumn('exchange_order_id');
        });
    }
};
