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
        Schema::create('return_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            $table->text('reason');
            $table->json('attachments')->nullable(); // ảnh + video

            $table->enum('status', ['pending', 'approved', 'rejected', 'refunded'])->default('pending');

            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete(); // admin xử lý
            $table->timestamp('handled_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_requests');
    }
};
