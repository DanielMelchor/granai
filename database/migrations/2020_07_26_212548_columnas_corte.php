<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ColumnasCorte extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maestro_documentos', function (Blueprint $table) {
            $table->unsignedBigInteger('corte_id')->nullable();
            $table->foreign('corte_id')->references('id')->on('cortes');
        });
        Schema::table('maestro_pagos', function (Blueprint $table) {
            $table->unsignedBigInteger('corte_id')->nullable();
            $table->unsignedBigInteger('paciente_id')->nullable();
            $table->foreign('corte_id')->references('id')->on('cortes');
            $table->foreign('paciente_id')->references('id')->on('pacientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maestro_documentos', function (Blueprint $table) {
            $table->dropForeign(['corte_id']);
            $table->dropColumn('corte_id');
        });
        Schema::table('maestro_pagos', function (Blueprint $table) {
            $table->dropForeign(['corte_id']);
            $table->dropColumn('corte_id');
            $table->dropForeign(['paciente_id']);
            $table->dropColumn('paciente_id');
        });
    }
}
