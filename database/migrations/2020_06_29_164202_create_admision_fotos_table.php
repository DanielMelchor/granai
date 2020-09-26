<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmisionFotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admision_fotos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admision_id');
            $table->string('nombre_imagen');
            $table->string('nombre_imagen_mini');
            $table->string('informe', 1);
            $table->timestamps();
            $table->string('created_by',50);
            $table->string('updated_by',50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admision_fotos');
    }
}
