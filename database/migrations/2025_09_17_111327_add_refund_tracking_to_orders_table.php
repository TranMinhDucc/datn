<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) refunded_out
        if (!Schema::hasColumn('orders', 'refunded_out')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'paid_in')) {
                    $table->decimal('refunded_out', 12, 2)->default(0)->after('paid_in');
                } else {
                    // Nếu không có paid_in, thêm không chỉ định vị trí
                    $table->decimal('refunded_out', 12, 2)->default(0);
                }
            });
        }

        // 2) balance
        if (!Schema::hasColumn('orders', 'balance')) {
            Schema::table('orders', function (Blueprint $table) {
                // nếu đã có refunded_out thì đặt sau refunded_out cho đẹp, không thì thêm bình thường
                if (Schema::hasColumn('orders', 'refunded_out')) {
                    $table->decimal('balance', 12, 2)->default(0)->after('refunded_out');
                } else {
                    $table->decimal('balance', 12, 2)->default(0);
                }
            });
        }

        // 3) Backfill balance: chỉ dùng cột nào tồn tại
        $parts = [];
        $parts[] = Schema::hasColumn('orders', 'net_total')    ? 'COALESCE(net_total,0)'    : '0';
        $parts[] = Schema::hasColumn('orders', 'paid_in')      ? 'COALESCE(paid_in,0)'      : '0';
        $parts[] = Schema::hasColumn('orders', 'refunded_out') ? 'COALESCE(refunded_out,0)' : '0';

        // balance = part0 - part1 - part2
        $expr = $parts[0] . ' - ' . $parts[1] . ' - ' . $parts[2];

        if (Schema::hasColumn('orders', 'balance')) {
            DB::statement("UPDATE orders SET balance = {$expr}");
        }
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
