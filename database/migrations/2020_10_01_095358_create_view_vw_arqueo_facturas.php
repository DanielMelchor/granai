<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewVwArqueoFacturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE VIEW `vw_arqueo_facturas` AS select `md`.`id` AS `id`,`md`.`empresa_id` AS `empresa_id`,`md`.`caja_id` AS `caja_id`,`c`.`nombre_maquina` AS `nombre_maquina`,`td`.`descripcion` AS `tipodocumento_descripcion`,`md`.`serie` AS `serie`,`md`.`correlativo` AS `correlativo`,`md`.`fecha_emision` AS `fecha_emision`,`md`.`nombre` AS `nombre`,`md`.`estado` AS `estado`,(case when (`md`.`estado` = 'A') then 'Activa' else 'Anulada' end) AS `estado_descripcion`,(case when (`md`.`estado` = 'A') then sum(ifnull(`dd`.`precio_bruto`,0)) else 0 end) AS `subtotal`,(case when (`md`.`estado` = 'A') then sum(ifnull(`dd`.`descuento`,0)) else 0 end) AS `descuento`,(case when (`md`.`estado` = 'A') then sum(ifnull(`dd`.`recargo`,0)) else 0 end) AS `recargo`,(case when (`md`.`estado` = 'A') then sum(ifnull(`dd`.`precio_neto`,0)) else 0 end) AS `precio_neto`,ifnull(`vvpd`.`total_pagado`,0) AS `total_pagado`,sum(((ifnull(`dd`.`precio_neto`,0) - ifnull(`vvpd`.`total_pagado`,0)) + ifnull(`vnpf`.`total_documento`,0))) AS `saldo` from (((((`maestro_documentos` `md` join `tipo_documentos` `td` on((`md`.`tipodocumento_id` = `td`.`id`))) join `cajas` `c` on((`md`.`caja_id` = `c`.`id`))) left join `detalle_documentos` `dd` on((`md`.`id` = `dd`.`maestro_documento_id`))) left join `vw_venta_pago_documentos` `vvpd` on((`md`.`id` = `vvpd`.`maestro_documento_id`))) left join `vw_nc_por_factura` `vnpf` on(((`md`.`tipodocumento_id` = `vnpf`.`tipodocumentoafecto_id`) and (`md`.`serie` = `vnpf`.`serie_afecta`) and (`md`.`correlativo` = `vnpf`.`correlativo_afecto`)))) where (`md`.`tipodocumento_id` in (1,3)) group by `md`.`empresa_id`,`md`.`caja_id`,`c`.`nombre_maquina`,`td`.`descripcion`,`md`.`serie`,`md`.`correlativo`,`md`.`fecha_emision`,`md`.`nombre`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("DROP VIEW vw_arqueo_facturas");
    }
}
