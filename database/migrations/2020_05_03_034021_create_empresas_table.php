<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('razon_social');
            $table->string('nombre_comercial');
            $table->string('direccion');
            $table->string('telefonos');
            $table->string('nit_empresa')->nullable();
            $table->string('igss_empresa')->nullable();
            $table->date('Fecha_constitucion')->nullable();
            $table->string('ruta_logo')->nullable();
            $table->decimal('porcentaje_impuesto', 12,2)->default('12');
            $table->char('estado',1)->default('A');
            $table->string('created_by');
            $table->string('updated_by');
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
        Schema::dropIfExists('empresas');
    }
}
