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
        Schema::create('shipping_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('shipping_partner')->default('ghn');
            $table->string('shipping_code'); // Mã đơn GHN
            $table->enum('status', [
                'ready_to_pick',
                'picking',
                'cancel',
                'money_collect_picking',
                'picked',
                'storing',
                'transporting',
                'sorting',
                'delivering',
                'money_collect_delivering',
                'delivered',
                'delivery_fail',
                'waiting_to_return',
                'return',
                'return_transporting',
                'return_sorting',
                'returning',
                'return_fail',
                'returned',
            ])->default('ready_to_pick');

            $table->string('note')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();

            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->index('shipping_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_orders');
    }
};
