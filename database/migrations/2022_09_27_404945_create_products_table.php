<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->bigInteger('barcode')->unique();
            $table->bigInteger('quantity');
            $table->date('expiry_date');
            $table->bigInteger('purches_price');
            $table->bigInteger('sale_price');
            $table->longText('description')->nullable();
            $table->longText('image')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
