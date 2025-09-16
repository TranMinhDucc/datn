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
        Schema::table('banners', function (Blueprint $table) {
            // Xóa các cột sub_*
            $table->dropColumn([
                'sub_image_1',
                'sub_image_1_name',
                'sub_image_1_price',
                'sub_image_2',
                'sub_image_2_name',
                'sub_image_2_price',
            ]);

            // Thêm các cột product_id_1, product_id_2 sau main_image
            $table->unsignedBigInteger('product_id_1')->nullable()->after('main_image');
            $table->unsignedBigInteger('product_id_2')->nullable()->after('product_id_1');

            // Khóa ngoại
            $table->foreign('product_id_1')
                  ->references('id')->on('products')
                  ->onDelete('cascade');

            $table->foreign('product_id_2')
                  ->references('id')->on('products')
                  ->onDelete('cascade');       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
             // Xóa khóa ngoại
            $table->dropForeign(['product_id_1']);
            $table->dropForeign(['product_id_2']);

            // Xóa cột product_id
            $table->dropColumn(['product_id_1', 'product_id_2']);

            // Thêm lại các cột sub_* nếu rollback
            $table->string('sub_image_1')->nullable();
            $table->string('sub_image_1_name')->nullable();
            $table->decimal('sub_image_1_price', 10, 2)->nullable();
            $table->string('sub_image_2')->nullable();
            $table->string('sub_image_2_name')->nullable();
            $table->decimal('sub_image_2_price', 10, 2)->nullable();
        });
    }
};
