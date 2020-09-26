<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmisionConsultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('admision_consultas');
        Schema::create('admision_consultas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admision_id');
            $table->unsignedBigInteger('paciente_id');
            $table->string('subjetivo',1000)->nullable();
            $table->string('objetivo',1000)->nullable();
            $table->string('impresion_clinica',1000)->nullable();
            $table->string('plan',1000)->nullable();
            $table->string('tratamiento',1000)->nullable();
            $table->decimal('peso')->nullable();
            $table->decimal('talla')->nullable();
            $table->Integer('pulso')->nullable();
            $table->decimal('temperatura')->nullable();
            $table->Integer('respiracion')->nullable();
            $table->Integer('presion_sistolica')->nullable();
            $table->Integer('presion_diastolica')->nullable();
            $table->decimal('bmi')->nullable();
            $table->timestamps();
            $table->string('created_by',50)->nullable();
            $table->string('updated_by',50)->nullable();
            $table->foreign('admision_id')->references('id')->on('admisiones');
            $table->foreign('paciente_id')->references('id')->on('pacientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admision_consultas');
    }
}
