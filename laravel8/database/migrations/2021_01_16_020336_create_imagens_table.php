<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantores_id')->unsigned();
            $table->string('name_album')->unsigned();
            $table->string('imagem');
            $table->timestamps();
            
            $table->foreign('cantores_id')->references('id')->on('cantores');
            $table->foreign('name_album')->references('name')->on('albuns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imagens');
    }
}
