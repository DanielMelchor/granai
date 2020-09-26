<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmisionCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('admision_cargos');
        Schema::create('admision_cargos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admision_id');
            $table->unsignedBigInteger('producto_id');
            $table->string('descripcion',500);
            $table->Integer('cantidad');
            $table->decimal('precio_unitario', 12,2);
            $table->decimal('precio_total', 12,2);
            $table->decimal('total_cliente', 12,2);
            $table->decimal('total_aseguradora', 12,2);
            $table->timestamps();
            $table->string('created_by',50);
            $table->string('updated_by',50);
            $table->foreign('admision_id')->references('id')->on('admisiones');
            $table->foreign('producto_id')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admision_cargos');
    }
}
