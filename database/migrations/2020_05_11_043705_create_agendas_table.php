<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('agendas');
        Schema::create('agendas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('medico_id');
            $table->unsignedBigInteger('hospital_id');
            $table->unsignedBigInteger('paciente_id')->nullable();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_final');
            $table->string('nombre_completo');
            $table->string('telefonos');
            $table->string('observaciones')->nullable();
            $table->string('estado', 1);
            $table->timestamps();
            $table->string('created_by');
            $table->string('updated_by');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('medico_id')->references('id')->on('medicos');
            $table->foreign('hospital_id')->references('id')->on('hospitales');
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
        Schema::dropIfExists('agendas');
    }
}
