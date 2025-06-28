<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('value')->nullable();
            $table->timestamps();
        });
        DB::table('settings')->insert([
            ['name' => 'title', 'value' => 'Katie - Online Fashion Store', 'created_at' => now(), 'updated_at' => '2025-06-09 16:20:28'],
            ['name' => 'description', 'value' => 'Katie', 'created_at' => now(), 'updated_at' => '2025-06-09 16:20:28'],
            ['name' => 'keywords', 'value' => 'Katie', 'created_at' => now(), 'updated_at' => '2025-06-09 16:20:28'],
            ['name' => 'author', 'value' => 'Chủ shop', 'created_at' => now(), 'updated_at' => '2025-06-09 16:20:28'],
            ['name' => 'timezone', 'value' => 'Asia/Ho_Chi_Minh', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'email', 'value' => 'tuongtacsale@gmail.com', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'hotline', 'value' => '03.56789.087', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'logo_light', 'value' => 'settings/cWnHB9xjk2vOVnzz2wYSRTlzBQzGAOyt5MsvS0Ng.png', 'created_at' => now(), 'updated_at' => '2025-06-10 14:24:02'],
            ['name' => 'logo_dark', 'value' => 'settings/DgIOCtugBgSRrsDe3AHA61t5uPbF9EJw1RiLnBtI.png', 'created_at' => now(), 'updated_at' => '2025-06-10 14:24:02'],
            [ 'name' => 'favicon', 'value' => 'settings/9dax3biFluHA9rhZ0yQYrGUR9w5jBD6BLcCDSSxv.png', 'created_at' => now(), 'updated_at' => '2025-06-10 14:25:03'],
            [ 'name' => 'image', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'address', 'value' => '123 Đường Thời Trang, Quận 1, TP.HCM', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'vat', 'value' => '10', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'prefix_autobank', 'value' => 'naptien', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'bank_min', 'value' => '10000', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'bank_max', 'value' => '10000000000', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'cron_bank_security', 'value' => 'cronbank', 'created_at' => now(), 'updated_at' => now()],
            [ 'name' => 'bank_status', 'value' => '1', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
