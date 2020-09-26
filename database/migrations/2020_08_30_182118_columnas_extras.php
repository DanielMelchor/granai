<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ColumnasExtras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pago_documentos', function (Blueprint $table) {
            $table->unsignedBigInteger('admision_id')->nullable();
            $table->foreign('admision_id')->references('id')->on('admisiones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pago_documentos', function (Blueprint $table) {
            $table->dropForeign(['admision_id']);
            $table->dropColumn('admision_id');
        });
    }
}
