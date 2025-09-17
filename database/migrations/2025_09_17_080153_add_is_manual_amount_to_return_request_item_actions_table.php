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
        Schema::table('return_request_item_actions', function (Blueprint $t) {
            // thêm cột boolean sau refund_amount, mặc định = false
            if (!Schema::hasColumn('return_request_item_actions', 'is_manual_amount')) {
                $t->boolean('is_manual_amount')->default(false)->after('refund_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('return_request_item_actions', function (Blueprint $t) {
            if (Schema::hasColumn('return_request_item_actions', 'is_manual_amount')) {
                $t->dropColumn('is_manual_amount');
            }
        });
    }
};
