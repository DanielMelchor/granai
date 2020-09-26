<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResolucionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resoluciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('caja_id');
            $table->unsignedBigInteger('tipo_documento');
            $table->string('serie',3);
            $table->integer('correlativo_inicial');
            $table->integer('correlativo_final');
            $table->integer('ultimo_correlativo');
            $table->string('estado',1);
            $table->timestamps();
            $table->string('created_by',50);
            $table->string('updated_by',50);
            $table->foreign('caja_id')->references('id')->on('cajas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resoluciones');
    }
}
