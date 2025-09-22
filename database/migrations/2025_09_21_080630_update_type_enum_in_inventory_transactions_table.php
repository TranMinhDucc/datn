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
            // Cập nhật lại enum cho cột type
            $table->enum('type', ['import', 'export', 'adjust', 'return', 'discard'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('inventory_transactions', function (Blueprint $table) {
            // Quay về như cũ (không có discard)
            $table->enum('type', ['import', 'export', 'adjust', 'return'])->change();
        });
    }
};
