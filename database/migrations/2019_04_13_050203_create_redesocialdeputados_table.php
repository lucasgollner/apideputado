<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedesocialdeputadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redesocialdeputados', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('idDeputado');
            $table->integer('idRedeSocial');
            $table->string('nome',255);
            $table->string('url',255);
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
        Schema::dropIfExists('redesocialdeputados');
    }
}
