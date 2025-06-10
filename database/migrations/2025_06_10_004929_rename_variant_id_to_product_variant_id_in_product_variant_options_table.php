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
        $table->renameColumn('variant_id', 'product_variant_id');
    });
}

public function down()
{
    Schema::table('product_variant_options', function (Blueprint $table) {
        $table->renameColumn('product_variant_id', 'variant_id');
    });
}

};
