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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('fullname')->nullable();
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('password');
            $table->text('avatar')->nullable();
            $table->enum('gender', ['Nam', 'Nữ', 'Khác'])->nullable();
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->decimal('balance', 15, 2)->default(0);
            $table->integer('point')->default(0);
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('token_2fa')->nullable();
            $table->string('SecretKey_2fa')->nullable();
            $table->integer('limit_2fa')->default(0);
            $table->integer('status_2fa')->default(0);
            $table->dateTime('create_date')->nullable();
            $table->dateTime('update_date')->nullable();
            $table->dateTime('registered_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->string('last_login_device')->nullable();
            $table->dateTime('last_login_at')->nullable(); // ✅ gộp cột từ migration bổ sung
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('banned')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
