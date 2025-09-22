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
        Schema::table('return_request_items', function (Blueprint $table) {
            $table->enum('qc_status', ['pending', 'passed', 'failed'])
                ->default('pending')
                ->after('item_status');
            $table->text('qc_note')->nullable()->after('qc_status');
        });
    }

    public function down(): void
    {
        Schema::table('return_request_items', function (Blueprint $table) {
            $table->dropColumn(['qc_status', 'qc_note']);
        });
    }
};
