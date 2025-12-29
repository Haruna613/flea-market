<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->comment('購入者ID');
            $table->foreignId('item_id')->constrained()->comment('購入商品ID');
            $table->integer('price')->comment('購入時の価格');
            $table->string('status')->default('pending')->comment('注文ステータス: pending, paid, shipped, completed, cancelled');
            $table->string('shipping_address_line1');
            $table->string('shipping_postal_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
