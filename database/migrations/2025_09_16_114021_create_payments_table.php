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
        Schema::create('payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->enum('kind', ['payment', 'refund']);      // payment = thu thêm, refund = hoàn lại
            $t->string('method', 50)->nullable();        // cod, bank, vnpay, momo, wallet,...
            $t->decimal('amount', 12, 2);
            $t->enum('status', ['pending', 'completed', 'failed', 'void'])->default('completed');
            $t->string('note', 500)->nullable();
            $t->json('meta')->nullable();
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();

            $t->index(['order_id', 'kind', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
