<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('order_adjustments', function (Blueprint $table) {
            // Cột cho biết có hiển thị cho khách không
            $table->boolean('visible_to_customer')
                ->default(true)
                ->after('taxable');

            // Cột phân loại (exchange_credit, price_diff, shipping, discount, …)
            $table->string('category', 50)
                ->nullable()
                ->after('visible_to_customer');
        });
    }

    public function down()
    {
        Schema::table('order_adjustments', function (Blueprint $table) {
            $table->dropColumn(['visible_to_customer', 'category']);
        });
    }
};
