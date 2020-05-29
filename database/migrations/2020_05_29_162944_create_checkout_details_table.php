<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckoutDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkout_details', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->decimal('price', 16, 2);
            $table->decimal('sub_total', 16, 2);
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('checkout_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('checkout_id')->references('id')->on('checkouts');
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
        Schema::dropIfExists('checkout_details');
    }
}
