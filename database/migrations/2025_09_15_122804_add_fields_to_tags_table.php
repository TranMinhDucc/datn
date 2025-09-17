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
        Schema::table('tags', function (Blueprint $table) {
            $table->string('description')->nullable()->after('slug');   // mô tả tag
            $table->boolean('is_active')->default(true)->after('description'); // bật/tắt tag
            $table->integer('sort_order')->default(0)->after('is_active');     // sắp xếp
        });
    }

    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn(['description', 'is_active', 'sort_order']);
        });
    }
};
