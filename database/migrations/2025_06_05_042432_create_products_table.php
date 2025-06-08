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
       Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('name');
    $table->string('slug')->nullable();
    $table->text('images')->nullable();
    $table->text('description')->nullable();
    $table->text('note')->nullable();
    $table->string('code')->nullable();
    $table->decimal('price', 12, 2)->default(0);
    $table->decimal('cost', 12, 2)->default(0);
    $table->decimal('discount', 5, 2)->default(0);
    $table->integer('min')->default(0);
    $table->integer('max')->default(0);
    $table->integer('sold')->default(0);
    $table->integer('quantity')->default(0);
    $table->integer('min_purchase_quantity')->default(1);
    $table->integer('max_purchase_quantity')->default(100);
    $table->unsignedBigInteger('category_id')->nullable();
    $table->tinyInteger('status')->default(1);
    $table->boolean('check_live')->default(false);
    $table->text('text_txt')->nullable();
    $table->string('short_desc')->nullable(); // hoặc ->default('') nếu muốn có mặc định

  $table->timestamps();

    // Khóa ngoại đến bảng categories
    $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
