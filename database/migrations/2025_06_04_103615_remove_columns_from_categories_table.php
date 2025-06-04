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
        Schema::table('categories', function (Blueprint $table) {
            //
            $table->dropColumn([
                'parent_id',
                'title',
                'keywords',
                'content'
            
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            //
                $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('title')->nullable();
            $table->string('keywords')->nullable();
            $table->text('content')->nullable();
        });
    }
};
