<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVwRecibos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE VIEW `vw_recibos` AS select `mp`.`empresa_id` AS `empresa_id`,`td`.`id` AS `tipodocumento_id`,`td`.`descripcion` AS `tipodocumento_descripcion`,`pd`.`maestro_documento_id` AS `maestro_documento_id`,`mp`.`serie` AS `serie`,`mp`.`correlativo` AS `correlativo`,`mp`.`fecha_emision` AS `fecha_emision`,sum((case when (`dp`.`estado` = 'I') then 0 else ifnull(`dp`.`monto`,0) end)) AS `total_recibo`,`mp`.`estado` AS `estado`,(case when (`mp`.`estado` = 'A') then 'Activo' else 'Anulado' end) AS `estado_descripcion`,`mp`.`created_at` AS `created_at` from (((`maestro_pagos` `mp` join `detalle_pagos` `dp` on((`mp`.`id` = `dp`.`maestro_pago_id`))) join `tipo_documentos` `td` on((`mp`.`tipodocumento_id` = `td`.`id`))) left join `pago_documentos` `pd` on((`mp`.`id` = `pd`.`maestro_documento_id`))) group by `mp`.`empresa_id`,`td`.`id`,`td`.`descripcion`,`pd`.`maestro_documento_id`,`mp`.`serie`,`mp`.`correlativo`,`mp`.`fecha_emision`,`mp`.`estado`,`mp`.`created_at`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("DROP VIEW vw_recibos");
    }
}
