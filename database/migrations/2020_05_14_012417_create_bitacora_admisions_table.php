<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBitacoraAdmisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bitacora_admisiones');
        Schema::create('bitacora_admisiones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('admision_id');
            $table->string('proceso');
            $table->unsignedBigInteger('observacion_id')->nullable();
            $table->string('observaciones', 15000)->nullable();
            $table->string('created_by',50)->nullable();
            $table->string('updated_by',50)->nullable();
            $table->timestamps();
            $table->foreign('observacion_id')->references('id')->on('observacion_admisiones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bitacora_admisions');
    }
}
