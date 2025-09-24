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
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->integer('before_quantity')->nullable()->after('quantity');
            $table->integer('after_quantity')->nullable()->after('before_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            $table->dropColumn(['before_quantity', 'after_quantity']);
        });
    }
};
