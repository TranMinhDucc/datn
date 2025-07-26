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
    Schema::table('coupons', function (Blueprint $table) {
        $table->boolean('active')->default(1)->after('id'); // hoặc after bất kỳ cột nào bạn muốn
    });
}

public function down()
{
    Schema::table('coupons', function (Blueprint $table) {
        $table->dropColumn('active');
    });
}

};
