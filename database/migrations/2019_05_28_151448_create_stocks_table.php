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
            $table->integer('filter_id');
            $table->string('color');
            $table->decimal('quantity');
            $table->decimal('size');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
