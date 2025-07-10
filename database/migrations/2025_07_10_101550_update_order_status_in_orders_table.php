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
    public function up()
    {
        DB::statement("ALTER TABLE orders MODIFY status ENUM(
            'pending',
            'confirmed',
            'shipping',
            'completed',
            'cancelled',
            'returning',
            'returned'
        ) DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE orders MODIFY status ENUM(
            'pending',
            'confirmed',
            'shipping',
            'completed',
            'cancelled'
        ) DEFAULT 'pending'");
    }
};
