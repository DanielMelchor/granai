<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVwVentaPagoDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE ALGORITHM=UNDEFINED DEFINER=`` SQL SECURITY DEFINER VIEW `vw_venta_pago_documentos` AS select `pd`.`maestro_documento_id` AS `maestro_documento_id`,sum(ifnull(`pd`.`total_aplicado`,0)) AS `total_pagado` from `pago_documentos` `pd` where (`pd`.`estado` = 'A') group by `pd`.`maestro_documento_id`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("drop view vw_venta_pago_documentos");
    }
}
