<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE return_request_item_actions 
            MODIFY qc_status ENUM('pending','passed','failed','passed_import','passed_noimport') 
            DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE return_request_item_actions 
            MODIFY qc_status ENUM('pending','passed','failed') 
            DEFAULT 'pending'");
    }
};
