<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVwFacturasPorAdmision extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE OR REPLACE VIEW `vw_facturas_por_admision` AS select `ac`.`admision_id` AS `admision_id`,group_concat(distinct concat(`md`.`serie`,'-',`md`.`correlativo`) separator ', ') AS `facturas` from (((`maestro_documentos` `md` join `detalle_documentos` `dd` on((`md`.`id` = `dd`.`maestro_documento_id`))) join `admision_cargo_detalles` `acd` on((`dd`.`admision_cargo_detalle_id` = `acd`.`id`))) join `admision_cargos` `ac` on((`acd`.`admision_cargo_id` = `ac`.`id`))) group by `ac`.`admision_id`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("DROP VIEW vw_facturas_por_admision");
    }
}