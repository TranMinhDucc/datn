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
        Schema::create('refunds', function (Blueprint $t) {
            $t->id();

            // Liên kết tới RMA, Order và User
            $t->foreignId('return_request_id')->constrained()->cascadeOnDelete();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Số tiền CK + snapshot chi tiết (giá trị hàng/ship/restocking/extra...)
            $t->decimal('amount', 14, 2);
            $t->json('breakdown')->nullable();
            $t->string('currency', 3)->default('VND');

            // Hình thức & kết quả CK thủ công
            $t->string('method')->default('bank'); // bank|momo|...
            $t->enum('status', ['pending', 'done', 'failed', 'canceled'])->default('pending');
            $t->string('bank_ref')->nullable();      // mã giao dịch để đối soát
            $t->timestamp('transferred_at')->nullable();

            // Ai tạo/ai xử lý
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();

            $t->timestamps();

            // Index hay dùng
            $t->index(['return_request_id', 'status']);
            $t->index(['user_id', 'status']);
            $t->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
