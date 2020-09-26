<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePacientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->Integer('expediente_no');
            $table->Integer('expediente_anterior_no')->nullable();
            $table->Integer('codigo_id');
            $table->String('nombres',60);
            $table->String('apellidos',60);
            $table->String('apellido_casada',60)->nullable();
            $table->String('nombre_completo',200);
            $table->char('genero',1)->default('F');
            $table->date('fecha_nacimiento');
            $table->String('direccion')->nullable();
            $table->Integer('ciudad')->nullable();
            $table->String('telefonos')->nullable();
            $table->String('fax')->nullable();
            $table->Integer('celular')->nullable();
            $table->String('correo_electronico')->nullable();
            $table->String('profesion')->nullable();
            $table->String('trabajo_nombre')->nullable();
            $table->String('trabajo_telefono')->nullable();
            $table->Char('estado_civil',1)->nullable();
            $table->String('conyugue_nombre')->nullable();
            $table->String('conyugue_ocupacion')->nullable();
            $table->char('emergencia_parentesco_id')->nullable();
            $table->String('emergencia_nombre')->nullable();
            $table->String('emergencia_telefonos')->nullable();
            $table->String('referido_por')->nullable();
            $table->char('religion',1)->nullable();
            $table->Integer('aseguradora_id')->nullable();
            $table->string('seguro_no')->nullable();
            $table->char('recordar_cita',1)->default('S');
            $table->string('antmedico_descripcion')->nullable();
            $table->string('antquirurgico_descripcion')->nullable();
            $table->string('antalergia_descripcion')->nullable();
            $table->string('antgineco_descripcion')->nullable();
            $table->string('antfamiliar_descripcion')->nullable();
            $table->string('antmedicamento_descripcion')->nullable();
            $table->unsignedInteger('tabaco_cnt')->nullable();
            $table->string('tabaco_tiempo')->nullable();
            $table->unsignedInteger('alcohol_cnt')->nullable();
            $table->string('alcohol_tiempo')->nullable();
            $table->string('antecedente_importante',1)->default('N');
            $table->string('factura_nit')->nullable();
            $table->string('factura_nombre')->nullable();
            $table->string('factura_direccion')->nullable();
            $table->string('cadena')->nullable();
            $table->String('created_by')->nullable();
            $table->String('updated_by')->nullable();
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
        Schema::dropIfExists('pacientes');
    }
}
