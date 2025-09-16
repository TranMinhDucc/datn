<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Bảng return_request_items
        Schema::table('return_request_items', function (Blueprint $table) {
            if (!Schema::hasColumn('return_request_items', 'approved_quantity')) {
                $table->integer('approved_quantity')->default(0)->after('quantity')
                    ->comment('Số lượng admin duyệt');
            }
            if (!Schema::hasColumn('return_request_items', 'status')) {
                $table->enum('status', [
                    'pending',      // Chờ xử lý
                    'approved',     // Admin duyệt
                    'rejected',     // Admin từ chối
                    'returned',     // Khách đã gửi hàng về
                    'refunded',     // Đã hoàn tiền
                    'exchanged'     // Đã đổi hàng
                ])->default('pending')->after('approved_quantity');
            }
        });

        // Bảng return_requests
        Schema::table('return_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('return_requests', 'total_refund_amount')) {
                $table->decimal('total_refund_amount', 12, 2)
                    ->nullable()
                    ->after('status')
                    ->comment('Tổng số tiền cần hoàn lại');
            }
        });

        // Bảng orders (nếu muốn xoá field dư thừa)
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'return_reason')) {
                $table->dropColumn('return_reason');
            }
            if (Schema::hasColumn('orders', 'return_attachments')) {
                $table->dropColumn('return_attachments');
            }
        });
    }

    public function down(): void
    {
        Schema::table('return_request_items', function (Blueprint $table) {
            if (Schema::hasColumn('return_request_items', 'approved_quantity')) {
                $table->dropColumn('approved_quantity');
            }
            if (Schema::hasColumn('return_request_items', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('return_requests', function (Blueprint $table) {
            if (Schema::hasColumn('return_requests', 'total_refund_amount')) {
                $table->dropColumn('total_refund_amount');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'return_reason')) {
                $table->string('return_reason', 255)->nullable();
            }
            if (!Schema::hasColumn('orders', 'return_attachments')) {
                $table->json('return_attachments')->nullable();
            }
        });
    }
};
