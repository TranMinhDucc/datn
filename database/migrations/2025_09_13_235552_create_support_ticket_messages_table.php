<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('support_ticket_messages', function (Blueprint $t) {
      $t->id();
      $t->foreignId('support_ticket_id')->constrained()->cascadeOnDelete();
      $t->foreignId('user_id')->constrained()->cascadeOnDelete(); // người gửi (client)
      $t->boolean('is_staff')->default(false)->index();           // client=false (admin sẽ true, để sau)
      $t->text('body');
      $t->timestamp('seen_at')->nullable();
      $t->timestamps();
      $t->index(['support_ticket_id','created_at']);
    });
  }
  public function down(): void { Schema::dropIfExists('support_ticket_messages'); }
};
