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
        Schema::create('debt_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->string('name');
            $table->string('phone');
            $table->integer('amount');
            $table->bigInteger('paid');
            $table->bigInteger('remain');
            $table->boolean('status')->default(0);
            $table->dateTime('delete_in')->nullable();
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
        Schema::dropIfExists('debt_sales');
    }
};
