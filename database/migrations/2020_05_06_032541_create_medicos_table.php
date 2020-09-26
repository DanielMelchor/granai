<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('nombre_completo');
            $table->string('direccion')->nullable();
            $table->string('titulo')->default('Dr.');
            $table->string('nit')->nullable();
            $table->string('telefono')->nullable();
            $table->string('celular')->nullable();
            $table->integer('lozalizador')->nullable();
            $table->string('firma')->nullable();
            $table->char('principal',1)->default('S');
            $table->char('estado',1)->default('A');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicos');
    }
}
