<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVwNcPorFactura extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE VIEW `vw_nc_por_factura` AS select `acd`.`admision_id` AS `admision_id`,`md`.`tipodocumentoafecto_id` AS `tipodocumentoafecto_id`,`md`.`serie_afecta` AS `serie_afecta`,`md`.`correlativo_afecto` AS `correlativo_afecto`,sum((ifnull(`dd`.`precio_neto`,0) * `dd`.`signo`)) AS `total_documento` from ((`maestro_documentos` `md` join `detalle_documentos` `dd` on((`md`.`id` = `dd`.`maestro_documento_id`))) join `admision_cargo_detalles` `acd` on((`dd`.`admision_cargo_detalle_id` = `acd`.`id`))) where ((`md`.`tipodocumento_id` = 2) and (`md`.`estado` = 'A')) group by `acd`.`admision_id`,`md`.`tipodocumentoafecto_id`,`md`.`serie_afecta`,`md`.`correlativo_afecto`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP VIEW vw_nc_por_factura');
    }
}
