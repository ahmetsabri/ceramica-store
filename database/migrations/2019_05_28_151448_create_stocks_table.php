<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocksTable extends Migration
{
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('mark_id');
            $table->string('mark_name');
            $table->string('filter_id');
            $table->string('color');
            $table->integer('quantity');
            $table->decimal('size');
            $table->decimal('price');
            $table->string('dimension')->nullable();

            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
