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
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_name');
            $table->string('email_subject');
            $table->text('email_body');
            $table->text('cc')->nullable();
            $table->text('bcc')->nullable();

            $table->json('target_emails')->nullable(); // danh sách email người dùng

            $table->enum('status', ['Đang gửi', 'Bản nháp', 'Đã gửi'])->default('Đang gửi');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_campaigns');
    }
};
