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
        Schema::create('shipping_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            $table->string('provider'); // ghi 'ghn', 'ghtk', 'viettelpost'...
            $table->string('tracking_code')->nullable(); // mã vận đơn
            $table->string('status'); // ví dụ: ready_to_pick, delivering, returned...
            $table->text('description')->nullable(); // mô tả chi tiết trạng thái từ đơn vị vận chuyển
            $table->timestamp('received_at')->nullable(); // thời điểm nhận callback
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_logs');
    }
};
