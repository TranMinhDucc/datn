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
        ['id' => 1, 'name' => 'title', 'value' => 'Katie - Online Fashion Store', 'created_at' => now(), 'updated_at' => '2025-06-09 16:20:28'],
        ['id' => 2, 'name' => 'description', 'value' => 'Katie', 'created_at' => now(), 'updated_at' => '2025-06-09 16:20:28'],
        ['id' => 3, 'name' => 'keywords', 'value' => 'Katie', 'created_at' => now(), 'updated_at' => '2025-06-09 16:20:28'],
        ['id' => 4, 'name' => 'author', 'value' => 'Chủ shop', 'created_at' => now(), 'updated_at' => '2025-06-09 16:20:28'],
        ['id' => 5, 'name' => 'timezone', 'value' => 'Asia/Ho_Chi_Minh', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 6, 'name' => 'email', 'value' => 'tuongtacsale@gmail.com', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 7, 'name' => 'hotline', 'value' => '03.56789.087', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 8, 'name' => 'logo_light', 'value' => 'settings/cWnHB9xjk2vOVnzz2wYSRTlzBQzGAOyt5MsvS0Ng.png', 'created_at' => now(), 'updated_at' => '2025-06-10 14:24:02'],
        ['id' => 9, 'name' => 'logo_dark', 'value' => 'settings/DgIOCtugBgSRrsDe3AHA61t5uPbF9EJw1RiLnBtI.png', 'created_at' => now(), 'updated_at' => '2025-06-10 14:24:02'],
        ['id' => 10, 'name' => 'favicon', 'value' => 'settings/9dax3biFluHA9rhZ0yQYrGUR9w5jBD6BLcCDSSxv.png', 'created_at' => now(), 'updated_at' => '2025-06-10 14:25:03'],
        ['id' => 11, 'name' => 'image', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
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
