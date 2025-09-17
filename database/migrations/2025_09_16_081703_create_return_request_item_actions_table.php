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
        Schema::create('return_request_item_actions', function (Blueprint $table) {
            $table->id();

            // Thuộc về 1 dòng yêu cầu đổi/trả
            $table->foreignId('return_request_item_id')
                ->constrained('return_request_items')
                ->cascadeOnDelete();

            // Loại hành động
            $table->enum('action', ['exchange', 'refund', 'reject'])->index();

            // Nếu là "exchange" có thể chọn variant đích (để null = giữ SKU cũ)
            $table->foreignId('exchange_variant_id')
                ->nullable()
                ->constrained('product_variants')
                ->nullOnDelete();

            // Số lượng áp dụng cho hành động này (>=1)
            $table->unsignedInteger('quantity');

            // Số tiền hoàn (chỉ dùng cho action = refund)
            $table->decimal('refund_amount', 12, 2)->nullable();

            // Ghi chú tuỳ chọn
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_request_item_actions');
    }
};
