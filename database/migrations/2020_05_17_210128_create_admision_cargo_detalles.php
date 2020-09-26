<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmisionCargoDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('admision_cargo_detalles');
        Schema::create('admision_cargo_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admision_id');
            $table->unsignedBigInteger('admision_cargo_id');
            $table->string('facturar_a',1);
            $table->unsignedBigInteger('aseguradora_id')->nullable();
            $table->decimal('porcentaje',6,3);
            $table->decimal('valor',12,2);
            $table->string('estado', 1);
            $table->timestamps();
            $table->string('created_by',50);
            $table->string('updated_by',50);
            $table->foreign('admision_id')->references('id')->on('admisiones');
            $table->foreign('admision_cargo_id')->references('id')->on('admision_cargos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admision_cargo_detalles');
    }
}
