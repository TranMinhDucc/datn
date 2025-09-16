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
        Schema::table('orders', function (Blueprint $table) {
            // Lưu ý giao hàng: 3 option của GHN (KHONGCHOXEMHANG, CHOXEMHANGKHONGTHU, CHOTHUHANG)
            $table->enum('required_note_shipper', [
                'KHONGCHOXEMHANG',
                'CHOXEMHANGKHONGTHU',
                'CHOTHUHANG'
            ])->default('KHONGCHOXEMHANG')->after('shipping_method');

            // Ghi chú thêm (tự do text)
            $table->text('note_shipper')->nullable()->after('required_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['required_note', 'note']);
        });
    }
};
