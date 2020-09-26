<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAseguradorasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aseguradoras', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('direccion')->nullable();
            $table->string('telefonos')->nullable();
            $table->string('contacto')->nullable();
            $table->string('facturacion_nit')->nullable();
            $table->string('facturacion_nombre')->nullable();
            $table->string('facturacion_direccion')->nullable();
            $table->decimal('copago',12,2)->default(0);
            $table->decimal('coaseguro',12,2)->default(0);
            $table->string('estado', 1)->default('A');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('aseguradoras');
    }
}
