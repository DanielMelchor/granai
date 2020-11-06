<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MejoraVwCorteCajaRecibos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE OR REPLACE VIEW `vw_corte_caja_recibos` AS select `mp`.`empresa_id` AS `empresa_id`,`mp`.`caja_id` AS `caja_id`,`mp`.`fecha_emision` AS `fecha_emision`,`mp`.`serie` AS `serie`,`mp`.`correlativo` AS `correlativo`,`p`.`nombre_completo` AS `nombre_completo`,sum(ifnull(`dp`.`monto`,0)) AS `total_recibo`,sum((case `dp`.`forma_pago` when '1' then `dp`.`monto` else 0 end)) AS `Efectivo`,sum((case `dp`.`forma_pago` when '2' then `dp`.`monto` else 0 end)) AS `Cheque`,sum((case `dp`.`forma_pago` when '3' then `dp`.`monto` else 0 end)) AS `Tarjeta`,sum((case `dp`.`forma_pago` when '4' then `dp`.`monto` else 0 end)) AS `Transferencia`,`vvd`.`tipo_admision` AS `tipo_admision`,`vvd`.`admision` AS `admision`,`mp`.`corte_id` AS `corte_id` from ((((`maestro_pagos` `mp` join `pacientes` `p` on((`mp`.`paciente_id` = `p`.`id`))) join `detalle_pagos` `dp` on((`mp`.`id` = `dp`.`maestro_pago_id`))) join `pago_documentos` `pd` on((`mp`.`id` = `pd`.`maestro_pago_id`))) join `vw_venta_documentos` `vvd` on((`pd`.`maestro_documento_id` = `vvd`.`id`))) group by `mp`.`empresa_id`,`mp`.`caja_id`,`mp`.`fecha_emision`,`mp`.`serie`,`mp`.`correlativo`,`p`.`nombre_completo`,`vvd`.`tipo_admision`,`vvd`.`admision`,`mp`.`corte_id`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("drop view vw_corte_caja_recibos");
    }
}
