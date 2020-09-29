<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVwVentaDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE ALGORITHM=UNDEFINED DEFINER=`` SQL SECURITY DEFINER VIEW `vw_venta_documentos` AS select `md`.`empresa_id` AS `empresa_id`,`md`.`caja_id` AS `caja_id`,`md`.`paciente_id` AS `paciente_id`,`md`.`tipodocumento_id` AS `tipodocumento_id`,`md`.`id` AS `id`,`td`.`descripcion` AS `tipodocumento_descripcion`,`md`.`serie` AS `serie`,`md`.`correlativo` AS `correlativo`,`md`.`fecha_emision` AS `fecha_emision`,`md`.`nit` AS `nit`,`md`.`nombre` AS `nombre`,`md`.`direccion` AS `direccion`,`md`.`corte_id` AS `corte_id`,ifnull(`a`.`id`,0) AS `admision_id`,ifnull(`a`.`admision`,0) AS `admision`,`a`.`tipo_admision` AS `tipo_admision`,(sum(ifnull((`dd`.`signo` * `dd`.`precio_neto`),0)) + ifnull(`nc`.`total_nota`,0)) AS `total_documento` from ((((((`maestro_documentos` `md` join `detalle_documentos` `dd` on((`md`.`id` = `dd`.`maestro_documento_id`))) join `tipo_documentos` `td` on((`md`.`tipodocumento_id` = `td`.`id`))) left join `admision_cargo_detalles` `acd` on((`dd`.`admision_cargo_detalle_id` = `acd`.`id`))) left join `admision_cargos` `ac` on((`acd`.`admision_cargo_id` = `ac`.`id`))) left join `admisiones` `a` on((`ac`.`admision_id` = `a`.`id`))) left join `vw_nc_vw_venta_documentos` `nc` on(((`md`.`empresa_id` = `nc`.`empresa_id`) and (`md`.`tipodocumento_id` = `nc`.`tipodocumento_id`) and (`md`.`serie` = `nc`.`serie`) and (`md`.`correlativo` = `nc`.`correlativo`)))) where (`md`.`tipodocumento_id` in (1,3)) group by `md`.`empresa_id`,`md`.`caja_id`,`md`.`paciente_id`,`md`.`tipodocumento_id`,`md`.`id`,`td`.`descripcion`,`md`.`serie`,`md`.`correlativo`,`md`.`fecha_emision`,`md`.`nit`,`md`.`nombre`,`md`.`direccion`,`md`.`corte_id`,ifnull(`a`.`id`,0),ifnull(`a`.`admision`,0),`a`.`tipo_admision`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("drop view vw_venta_documentos");
    }
}
