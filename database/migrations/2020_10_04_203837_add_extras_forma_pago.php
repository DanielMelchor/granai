<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtrasFormaPago extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('formas_pago', function (Blueprint $table) {
            $table->char('banco',1)->default('N');
            $table->char('casa',1)->default('N');
            $table->char('cuenta',1)->default('N');
            $table->char('documento',1)->default('N');
            $table->char('autorizacion',1)->default('N');
            $table->char('recibos',1)->default('N');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formas_pago', function (Blueprint $table) {
            $table->dropColumn('banco');
            $table->dropColumn('casa');
            $table->dropColumn('cuenta');
            $table->dropColumn('documento');
            $table->dropColumn('autorizacion');
            $table->dropColumn('recibos');
        });
    }
}
