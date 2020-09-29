<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVwAdmisionesConCargos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE ALGORITHM=UNDEFINED DEFINER=`` SQL SECURITY DEFINER VIEW `vw_admisiones_con_cargos` AS select `a`.`empresa_id` AS `empresa_id`,`a`.`id` AS `admision_id`,`a`.`admision` AS `admision`,`p`.`nombre_completo` AS `paciente_nombre`,`a`.`fecha` AS `fecha`,sum(`ac`.`precio_total`) AS `total_cargos` from ((`admisiones` `a` join `pacientes` `p` on((`a`.`paciente_id` = `p`.`id`)))                         join `admision_cargos` `ac` on((`a`.`id` = `ac`.`admision_id`))) group by `a`.`empresa_id`,`a`.`id`,`a`.`admision`,`p`.`nombre_completo`,`a`.`fecha`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("drop view vw_admisionsiones_con_cargos");
    }
}
