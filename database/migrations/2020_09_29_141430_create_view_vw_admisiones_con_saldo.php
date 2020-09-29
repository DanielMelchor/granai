<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVwAdmisionesConSaldo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE ALGORITHM=UNDEFINED DEFINER=`` SQL SECURITY DEFINER VIEW `vw_admisiones_con_saldo` AS select `vacc`.`empresa_id` AS `empresa_id`,`vacc`.`admision_id` AS `admision_id`,`vacc`.`admision` AS `admision`,       `vacc`.`paciente_nombre` AS `paciente_nombre`,`vacc`.`fecha` AS `fecha`,`vacc`.`total_cargos` AS `total_cargos`,      sum(ifnull(`vaf`.`total_facturado`,0)) AS `total_facturado`,sum(ifnull(`vvpd`.`total_pagado`,0)) AS `total_pagado`,     (`vacc`.`total_cargos` - sum(ifnull(`vvpd`.`total_pagado`,0))) AS `saldo` from ((`vw_admisiones_con_cargos` `vacc` left join `vw_admisiones_facturadas` `vaf` on((`vacc`.`admision_id` = `vaf`.`admision_id`))) left join `vw_venta_pago_documentos` `vvpd` on((`vaf`.`maestro_documento_id` = `vvpd`.`maestro_documento_id`))) where (`vacc`.`total_cargos` <> ifnull(`vvpd`.`total_pagado`,0)) group by `vacc`.`empresa_id`,`vacc`.`admision_id`,`vacc`.`admision`,`vacc`.`paciente_nombre`,`vacc`.`fecha`,`vacc`.`total_cargos`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("drop view vw_admisiones_con_saldo");
    }
}
