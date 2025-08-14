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
        Schema::table('orders', function (Blueprint $t) {
            if (!Schema::hasColumn('orders', 'delivered_at')) $t->timestamp('delivered_at')->nullable()->after('status');
            if (!Schema::hasColumn('orders', 'completed_at')) $t->timestamp('completed_at')->nullable()->after('delivered_at');
            if (!Schema::hasColumn('orders', 'cancelled_at')) $t->timestamp('cancelled_at')->nullable()->after('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            if (Schema::hasColumn('orders', 'cancelled_at')) $t->dropColumn('cancelled_at');
            if (Schema::hasColumn('orders', 'completed_at')) $t->dropColumn('completed_at');
            if (Schema::hasColumn('orders', 'delivered_at')) $t->dropColumn('delivered_at');
        });
    }
};
