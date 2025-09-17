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
        Schema::create('best_seller_sections', function (Blueprint $table) {
           $table->id();
            $table->string('title_small')->nullable();
            $table->string('title_main')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('btn_text')->default('Shop Collection');
            $table->string('btn_url')->nullable();

            $table->string('left_image')->nullable();   // storage path
            $table->string('right_image')->nullable();  // storage path

            $table->string('side_title')->nullable();
            $table->string('side_offer_title')->nullable();
            $table->text('side_offer_desc')->nullable();
            $table->string('side_offer_code', 50)->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('best_seller_sections');
    }
};
