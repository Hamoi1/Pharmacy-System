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
            $table->id();
            $table->string('name');
            $table->bigInteger('barcode')->unique();
            $table->bigInteger('quantity')->nullable();
            $table->date('expiry_date');
            $table->decimal('purches_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->longText('description')->nullable();
            $table->longText('image')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categorys')->onDelete('set null');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
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
