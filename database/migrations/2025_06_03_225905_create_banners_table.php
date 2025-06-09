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
        Schema::create('banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ten'); // tiêu đề
            $table->string('hinh_anh'); // link ảnh nền
            $table->text('mo_ta'); // mô tả
            $table->string('ngon_ngu')->default('vi');
            $table->integer('thu_tu')->default(0);
            $table->boolean('status')->default(false);
            $table->boolean('is_app')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
