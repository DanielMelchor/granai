<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecetaMedicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receta_medicos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medico_id');
            $table->decimal('pagina_alto',12,2)->nullable();
            $table->decimal('pagina_ancho',12,2)->nullable();
            $table->string('orientacion',1)->nullable();
            $table->string('unidad_medida',2)->nullable();
            $table->decimal('dia_x',12,2)->nullable();
            $table->decimal('dia_y',12,2)->nullable();
            $table->decimal('mes_x',12,2)->nullable();
            $table->decimal('mes_y',12,2)->nullable();
            $table->decimal('anio_x',12,2)->nullable();
            $table->decimal('anio_y',12,2)->nullable();
            $table->decimal('paciente_x',12,2)->nullable();
            $table->decimal('paciente_y',12,2)->nullable();
            $table->decimal('tratamiento_x',12,2)->nullable();
            $table->decimal('tratamiento_y',12,2)->nullable();
            $table->decimal('proxima_cita_x',12,2)->nullable();
            $table->decimal('proxima_cita_y',12,2)->nullable();
            $table->timestamps();
            $table->string('created_by',50);
            $table->string('updated_by',50);
            $table->foreign('medico_id')->references('id')->on('medicos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receta_medicos');
    }
}
