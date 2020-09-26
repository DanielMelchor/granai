<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('admisiones');
        Schema::create('admisiones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('agenda_id')->nullable();
            $table->date('fecha');
            $table->char('tipo_admision',1);
            $table->char('serie',1)->nullable();
            $table->unsignedInteger('admision');
            $table->unsignedBigInteger('paciente_id');
            $table->unsignedInteger('edad');
            $table->unsignedBigInteger('medico_id');
            $table->unsignedBigInteger('hospital_id');
            $table->Integer('admision_tercero')->nullable();
            $table->string('referido_por')->nullable();
            $table->unsignedBigInteger('aseguradora_id')->nullable();
            $table->string('poliza_no')->nullable();
            $table->unsignedInteger('deducible');
            $table->unsignedInteger('copago');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('resumen_egreso',1000)->nullable();
            $table->char('estado');
            $table->String('created_by');
            $table->String('updated_by');
            $table->timestamps();
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('agenda_id')->references('id')->on('agendas');
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->foreign('medico_id')->references('id')->on('medicos');
            $table->foreign('hospital_id')->references('id')->on('hospitales');
            //$table->foreign('aseguradora_id')->references('id')->on('aseguradoras');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admisions');
    }
}
