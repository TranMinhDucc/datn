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
        Schema::create('order_adjustments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->string('code', 50)->nullable();         // CLEANING, RESTOCK, RETURN_SHIP, CREDIT_FROM_RR,...
            $t->string('label');                         // nhãn hiển thị
            $t->enum('type', ['charge', 'discount'])->default('charge'); // charge = cộng, discount = trừ
            $t->decimal('amount', 12, 2);               // luôn dương, chiều do type quyết định
            $t->boolean('taxable')->default(false);
            $t->json('meta')->nullable();
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();

            $t->index(['order_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_adjustments');
    }
};
