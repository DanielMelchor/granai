<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVwAdmisionesFacturadas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE OR REPLACE VIEW `vw_admisiones_facturadas` AS select `dd`.`maestro_documento_id` AS `maestro_documento_id`,`ac`.`admision_id` AS `admision_id`,       sum(ifnull(`dd`.`precio_neto`,0)) AS `total_facturado` from (((`maestro_documentos` `md` join `detalle_documentos` `dd` on((`md`.`id` = `dd`.`maestro_documento_id`))) join `admision_cargo_detalles` `acd` on((`dd`.`admision_cargo_detalle_id` = `acd`.`id`))) join `admision_cargos` `ac` on((`acd`.`admision_cargo_id` = `ac`.`id`))) where ((`md`.`estado` = 'A') and (`dd`.`admision_cargo_detalle_id` is not null)) group by `dd`.`maestro_documento_id`,`ac`.`id`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("drop view vw_admisiones_facturadas");
    }
}
