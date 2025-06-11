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
        Schema::table('product_variant_options', function (Blueprint $table) {
            $table->unsignedBigInteger('attribute_id')->after('variant_id')->nullable();

            // Nếu muốn có ràng buộc khoá ngoại
            $table->foreign('attribute_id')
                ->references('id')->on('variant_attributes')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('product_variant_options', function (Blueprint $table) {
            $table->dropForeign(['attribute_id']);
            $table->dropColumn('attribute_id');
        });
    }
};
