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
        Schema::create('traffic_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->string('source')->nullable();   // Direct, Referral, Social
            $table->string('referer')->nullable();  // domain gốc
            $table->ipAddress('ip')->nullable();    // IP khách
            $table->string('user_agent')->nullable(); // Trình duyệt, thiết bị
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traffic_logs');
    }
};
