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
            $table->integer('barcode')->unique();
            $table->integer('quantity');
            $table->date('expiry_date');
            $table->integer('purches_price');
            $table->integer('sale_price');
            $table->longText('description')->nullable();
            $table->longText('image')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
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
        Schema::dropIfExists('products');
    }
};
