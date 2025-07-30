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
    Schema::table('orders', function (Blueprint $table) {
        $table->json('return_attachments')->nullable()->after('return_reason');
        $table->dropColumn('return_image');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attachments_on_orders', function (Blueprint $table) {
            //
        });
    }
};
