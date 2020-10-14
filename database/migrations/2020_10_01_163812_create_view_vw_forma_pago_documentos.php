<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVwFormaPagoDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE OR REPLACE VIEW `vw_forma_pago_documentos` AS select `mp`.`empresa_id` AS `empresa_id`,`mp`.`caja_id` AS `caja_id`,`mp`.`tipodocumento_id` AS `tipodocumento_id`,`td`.`descripcion` AS `tipodocumento_descripcion`,`mp`.`id` AS `id`,`mp`.`fecha_emision` AS `fecha_emision`,`mp`.`serie` AS `serie`,`mp`.`correlativo` AS `correlativo`,`dp`.`forma_pago` AS `forma_pago`,`fp`.`descripcion` AS `formapago_descripcion`,ifnull(`dp`.`monto`,0) AS `total_forma_pago`,`mp`.`corte_id` AS `corte_id` from (((`maestro_pagos` `mp` join `tipo_documentos` `td` on((`mp`.`tipodocumento_id` = `td`.`id`))) join `detalle_pagos` `dp` on((`mp`.`id` = `dp`.`maestro_pago_id`))) join `formas_pago` `fp` on((`dp`.`forma_pago` = `fp`.`id`))) group by `mp`.`empresa_id`,`mp`.`caja_id`,`mp`.`tipodocumento_id`,`td`.`descripcion`,`mp`.`id`,`mp`.`fecha_emision`,`mp`.`serie`,`mp`.`correlativo`,`dp`.`forma_pago`,`fp`.`descripcion`,`mp`.`corte_id`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("DROP VIEW vw_forma_pago_documentos");
    }
}