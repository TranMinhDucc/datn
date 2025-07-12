<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();

            $table->enum('type', ['product_discount', 'shipping_discount', 'order_discount']);
            $table->enum('value_type', ['fixed', 'percentage']);
            $table->unsignedInteger('discount_value');
            $table->unsignedInteger('max_discount_amount')->nullable();
            $table->unsignedInteger('min_order_amount')->nullable();

            $table->json('applicable_product_ids')->nullable();
            $table->json('applicable_category_ids')->nullable();

            $table->boolean('only_for_new_users')->default(false);
            $table->boolean('is_exclusive')->default(false);

            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->unsignedInteger('per_user_limit')->nullable();

            // Đã xoá: $table->json('eligible_user_roles')->nullable();

            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
