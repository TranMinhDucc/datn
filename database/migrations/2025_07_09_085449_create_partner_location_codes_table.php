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
        Schema::create('partner_location_codes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['province', 'district', 'ward']);
            $table->unsignedBigInteger('location_id'); // id trong bảng tương ứng
            $table->string('partner_code'); // eg: GHN, GHTK
            $table->string('partner_id');   // mã tỉnh/huyện/xã từ đối tác
            $table->timestamps();

            $table->unique(['type', 'location_id', 'partner_code']); // tránh trùng
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_location_codes');
    }
};
