<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('maestro_pago_id');
            $table->string('forma_pago',1);
            $table->unsignedBigInteger('banco_id')->nullable();
            $table->string('cuenta_no')->nullable();
            $table->string('documento_no')->nullable();
            $table->string('autoriza_no')->nullable();
            $table->decimal('monto',12,2);
            $table->unsignedBigInteger('motivo_rechazo_id')->nullable();
            $table->string('rechazo_observacion',175)->nullable();
            $table->string('estado',1);
            $table->timestamps();
            $table->string('created_by',50);
            $table->string('updated_by',50);
            $table->foreign('maestro_pago_id')->references('id')->on('maestro_pagos')->onDelete('cascade');
            $table->foreign('motivo_rechazo_id')->references('id')->on('motivo_rechazos');
            $table->foreign('banco_id')->references('id')->on('bancos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_pagos');
    }
}
