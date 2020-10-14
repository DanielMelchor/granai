<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVwNcVwVentaDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE OR REPLACE VIEW `vw_nc_vw_venta_documentos` AS select `md`.`empresa_id` AS `empresa_id`,`md`.`tipodocumentoafecto_id` AS `tipodocumento_id`,`md`.`serie_afecta` AS `serie`,`md`.`correlativo_afecto` AS `correlativo`, sum(ifnull((`dd`.`signo` * `dd`.`precio_neto`),0)) AS `total_nota` from (`granai_db`.`maestro_documentos` `md` join `granai_db`.`detalle_documentos` `dd` on((`md`.`id` = `dd`.`maestro_documento_id`))) where (`md`.`tipodocumento_id` = 2) group by `md`.`empresa_id`,`md`.`tipodocumentoafecto_id`,`md`.`serie_afecta`,`md`.`correlativo_afecto`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("drop view vw_nc_vw_venta_documentos");
    }
}
