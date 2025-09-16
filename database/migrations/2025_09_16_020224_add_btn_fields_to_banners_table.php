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
        Schema::table('banners', function (Blueprint $table) {
             $table->string('btn_title')->nullable()->after('product_id_2');
            // dùng 2048 để chứa URL dài
            $table->string('btn_link', 2048)->nullable()->after('btn_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
             $table->dropColumn(['btn_title', 'btn_link']);
        });
    }
};
