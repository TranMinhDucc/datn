<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title', 100)->nullable();
            $table->text('address');
            $table->string('country', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('pincode', 20)->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('is_default')->default(false);
            $table->tinyInteger('status')->default(1); // 1 = hoạt động, 0 = vô hiệu hóa
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping_addresses');
    }
}
