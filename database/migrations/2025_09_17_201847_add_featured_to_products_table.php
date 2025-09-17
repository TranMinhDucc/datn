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
        Schema::table('products', function (Blueprint $table) {
            // thêm cột boolean sau refund_amount, mặc định = false
            if (!Schema::hasColumn('products', 'is_special_offer')) {
                $table->boolean('is_special_offer')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'is_special_offer')) {
                $table->dropColumn('is_special_offer');
            }
        });
    }
};
