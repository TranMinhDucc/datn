<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('support_tickets', function (Blueprint $t) {
      $t->id();
      $t->foreignId('user_id')->constrained()->cascadeOnDelete();
      $t->string('subject', 120);
      $t->enum('category', ['order','product','payment','account','other'])->index();
      $t->enum('priority', ['normal','high','urgent'])->default('normal')->index();
      $t->string('order_code', 100)->nullable()->index();
      $t->string('carrier_code', 100)->nullable()->index();
      $t->json('contact_via')->nullable();      // ['email','sms','phone']
      $t->string('contact_time', 50)->nullable(); // morning/afternoon/evening
      $t->enum('status', ['open','in_progress','waiting_customer','waiting_carrier','resolved','closed','cancelled'])
        ->default('open')->index();
      $t->timestamps();
      $t->index(['user_id','status','created_at']);
    });
  }
  public function down(): void { Schema::dropIfExists('support_tickets'); }
};

