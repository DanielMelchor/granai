<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagoDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago_documentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('maestro_documento_id');
            $table->unsignedBigInteger('maestro_pago_id');
            $table->decimal('saldo_documento',12,2);
            $table->decimal('total_aplicado',12,2);
            $table->string('estado',1);
            $table->timestamps();
            $table->string('created_by',50);
            $table->string('updated_by',50);
            $table->foreign('maestro_documento_id')->references('id')->on('maestro_documentos');
            $table->foreign('maestro_pago_id')->references('id')->on('maestro_pagos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pago_documentos');
    }
}
