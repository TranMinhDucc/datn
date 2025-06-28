<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants');

            $table->string('product_name', 255);
            $table->string('sku', 100);
            $table->text('image_url')->nullable();
            $table->json('variant_values')->nullable();

            $table->decimal('price', 12, 2);
            $table->integer('quantity');
            $table->decimal('total_price', 12, 2);

            $table->integer('refunded_quantity')->default(0);
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('order_items');
    }
};
