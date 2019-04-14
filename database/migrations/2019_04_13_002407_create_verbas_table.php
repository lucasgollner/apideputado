<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerbasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verbas', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('idDeputado');
            $table->date('dataReferencia');
            $table->integer('codTipoDespesa');
            $table->double('valor',12,2);
            $table->string('descTipoDespesa', 255);
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
        Schema::dropIfExists('verbas');
    }
}
