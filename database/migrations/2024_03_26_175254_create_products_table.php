<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->integer('price');
            $table->unsignedBigInteger('storeId')->nullable();
            $table->string('nid')->nullable();
            $table->string('image')->nullable();
            $table->integer('ratingScore')->nullable();
            $table->integer('originalPrice')->nullable();
            $table->string('itemSoldCntShow')->nullable();
            $table->string('location')->nullable();
            $table->string('discount', 11)->nullable();
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
}
