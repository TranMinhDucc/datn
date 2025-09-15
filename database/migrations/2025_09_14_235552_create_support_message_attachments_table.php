<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('support_message_attachments', function (Blueprint $t) {
      $t->id();
      $t->foreignId('support_ticket_message_id')->constrained('support_ticket_messages')->cascadeOnDelete();
      $t->string('path');
      $t->string('original_name');
      $t->string('mime', 120)->nullable();
      $t->unsignedBigInteger('size')->nullable();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('support_message_attachments'); }
};
