<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaestroPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maestro_pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('caja_id');
            $table->unsignedBigInteger('paciente_id')->nullable();
            $table->unsignedBigInteger('tipodocumento_id');
            $table->unsignedBigInteger('resolucion_id');
            $table->date('fecha_emision');
            $table->string('serie');
            $table->unsignedInteger('correlativo');
            $table->unsignedBigInteger('motivo_anulacion_id')->nullable();
            $table->string('observacion_anulacion')->nullable();
            $table->unsignedBigInteger('anulacion_usuario_id')->nullable();
            $table->datetime('fecha_anulacion')->nullable();
            $table->string('estado', 1);
            $table->timestamps();
            $table->string('created_by',50);
            $table->string('updated_by',50);
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('caja_id')->references('id')->on('cajas');
            $table->foreign('tipodocumento_id')->references('id')->on('tipo_documentos');
            $table->foreign('resolucion_id')->references('id')->on('resoluciones');
            $table->foreign('motivo_anulacion_id')->references('id')->on('motivo_anulaciones');
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->foreign('anulacion_usuario_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maestro_pagos');
    }
}
