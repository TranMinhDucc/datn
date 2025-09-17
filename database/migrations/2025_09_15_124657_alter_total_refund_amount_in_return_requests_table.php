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
            $table->decimal('total_refund_amount', 15, 2)
                ->default(0)   // 👈 để số, không để '0'
                ->nullable(false)
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table) {
            $table->decimal('total_refund_amount', 15, 2)
                ->nullable()
                ->default(null)
                ->change();
        });
    }
};
