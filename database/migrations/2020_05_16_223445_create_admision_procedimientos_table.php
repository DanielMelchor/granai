<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmisionProcedimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('admision_procedimientos');
        Schema::create('admision_procedimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admision_id');
            $table->unsignedBigInteger('paciente_id');
            $table->unsignedBigInteger('procedimiento_id');
            $table->string('tolerancia',1);
            $table->string('premedicacion');
            $table->string('patologo');
            $table->string('anestesiologo');
            $table->string('indicacion',1000);
            $table->string('hallazgos',1000);
            $table->string('diagnostico',1000);
            $table->string('recomendaciones',1000);
            $table->timestamps();
            $table->string('created_by',50);
            $table->string('updated_by',50);
            $table->foreign('admision_id')->references('id')->on('admisiones');
            $table->foreign('paciente_id')->references('id')->on('pacientes');
            $table->foreign('procedimiento_id')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admision_procedimientos');
    }
}
