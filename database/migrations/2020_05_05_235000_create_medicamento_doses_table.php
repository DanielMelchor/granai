<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicamentoDosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('medicamento_dosis');
        Schema::create('medicamento_dosis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('medicamento_id');
            $table->unsignedBigInteger('dosis_id');
            $table->string('descripcion_receta');
            $table->char('estado',1);
            $table->string('created_by',50);
            $table->string('updated_by',50);
            $table->timestamps();
            $table->foreign('medicamento_id')->references('id')->on('medicamentos');
            $table->foreign('dosis_id')->references('id')->on('dosis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicamento_dosis');
    }
}
