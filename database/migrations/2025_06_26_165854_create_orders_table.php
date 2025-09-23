<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('coupon_id')->nullable()->constrained('coupons');

            $table->foreignId('address_id')->constrained('shipping_addresses');
            $table->foreignId('payment_method_id')->constrained('payment_methods');

            $table->string('coupon_code', 50)->nullable();
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);

            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'ready_for_dispatch',
                'shipping',
                'delivery_failed',
                'delivered',
                'completed',
                'cancelled',
                'return_requested',
                'returning',
                'returned',
                'exchange_requested',
                'exchanged',
                'refund_processing',
                'refunded',
                'exchange_in_progress',
                'exchange_and_refund_processing',
                'exchanged_and_refunded',
                'closed'
            ])->default('pending')->change();
            $table->boolean('is_paid')->default(false);
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->string('payment_reference', 100)->nullable();

            $table->string('shipping_method', 50)->nullable();
            $table->string('shipping_tracking_code', 100)->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->dateTime('delivered_at')->nullable();

            $table->text('cancel_reason')->nullable();
            $table->text('note')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
