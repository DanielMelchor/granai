<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVwAntiguedadSaldos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE OR REPLACE VIEW `vw_antiguedad_saldos` AS select `vvd`.`empresa_id` AS `empresa_id`,`vvd`.`admision` AS `admision`,`vvd`.`nombre` AS `cliente_nombre`,       `vvd`.`serie` AS `serie`,`vvd`.`correlativo` AS `correlativo`,`vvd`.`fecha_emision` AS `fecha_emision`,       `vvd`.`total_documento` AS `total_documento`,ifnull(`vvpd`.`total_pagado`,0) AS `total_pagado`,     (`vvd`.`total_documento` - ifnull(`vvpd`.`total_pagado`,0)) AS `saldo`,(case when ((curdate() - `vvd`.`fecha_emision`) <= 90) then (`vvd`.`total_documento` - ifnull(`vvpd`.`total_pagado`,0)) else 0 end) AS `dias_30`, (case when ((curdate() - `vvd`.`fecha_emision`) > 30) then (`vvd`.`total_documento` - ifnull(`vvpd`.`total_pagado`,0)) else 0 end) AS `dias_mayor_30`,(case when ((curdate() - `vvd`.`fecha_emision`) > 60) then (`vvd`.`total_documento` - ifnull(`vvpd`.`total_pagado`,0)) else 0 end) AS `dias_mayor_60`,(case when ((curdate() - `vvd`.`fecha_emision`) > 90) then (`vvd`.`total_documento` - ifnull(`vvpd`.`total_pagado`,0)) else 0 end) AS `dias_mayor_90`,(case when ((curdate() - `vvd`.`fecha_emision`) > 120) then (`vvd`.`total_documento` - ifnull(`vvpd`.`total_pagado`,0)) else 0 end) AS `dias_mayor_120` from (`vw_venta_documentos` `vvd` left join `vw_venta_pago_documentos` `vvpd` on((`vvd`.`id` = `vvpd`.`maestro_documento_id`))) where (`vvd`.`total_documento` <> ifnull(`vvpd`.`total_pagado`,0))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("drop view vw_antiguedad_saldos");
    }
}
