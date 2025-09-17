<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm cột nếu chưa có
        if (! Schema::hasColumn('orders', 'refunded_out')) {
            Schema::table('orders', function (Blueprint $table) {
                // tổng tiền đã hoàn trả cho KH
                $table->decimal('refunded_out', 12, 2)
                    ->default(0)
                    ->after('paid_in');
            });
        }

        if (! Schema::hasColumn('orders', 'balance')) {
            Schema::table('orders', function (Blueprint $table) {
                // số dư = net_total - paid_in - refunded_out
                $table->decimal('balance', 12, 2)
                    ->default(0)
                    ->after('refunded_out');
            });
        }

        // Backfill giá trị balance hiện có
        DB::statement(
            'UPDATE orders 
             SET balance = COALESCE(net_total,0) - COALESCE(paid_in,0) - COALESCE(refunded_out,0)'
        );
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'balance')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('balance');
            });
        }
        if (Schema::hasColumn('orders', 'refunded_out')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('refunded_out');
            });
        }
    }
};
