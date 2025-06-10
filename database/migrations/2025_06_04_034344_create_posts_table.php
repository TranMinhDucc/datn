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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Bị xóa khi người dùng bị xóa
            $table->foreignId('category_id')->constrained('post_categories')->onDelete('cascade'); // Bị xóa khi danh mục bài viết bị xóa
            $table->text('title');
            $table->text('thumbnail')->nullable();
            $table->string('slug')->unique();
            $table->longText('content');
            $table->integer('status')->default(1); // 0: Nháp, 1: Đã đăng, 2: Bị ẩn
            $table->integer('view')->default(0);
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
