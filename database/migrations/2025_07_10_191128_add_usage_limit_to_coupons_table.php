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
        $table->unsignedInteger('usage_limit')->default(0)->after('id');  // 0 nghĩa là không giới hạn
        $table->unsignedInteger('used_count')->default(0)->after('usage_limit');
    });
}

public function down()
{
    Schema::table('coupons', function (Blueprint $table) {
        $table->dropColumn(['usage_limit', 'used_count']);
    });
}

};
