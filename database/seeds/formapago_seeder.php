<?php

use Illuminate\Database\Seeder;
use App\forma_pago;

class formapago_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        forma_pago::create(array('descripcion' => 'Efectivo', 'estado' => 'A', 'banco' => 'N', 'casa' => 'N', 'cuenta' => 'N', 'documento' => 'N', 'autorizacion' => 'N', 'recibos' => 'N'));
        forma_pago::create(array('descripcion' => 'Cheque', 'estado' => 'A', 'banco' => 'S', 'casa' => 'N', 'cuenta' => 'S', 'documento' => 'S', 'autorizacion' => 'S', 'recibos' => 'N'));
        forma_pago::create(array('descripcion' => 'Tarjeta', 'estado' => 'A', 'banco' => 'N', 'casa' => 'S', 'cuenta' => 'S', 'documento' => 'S', 'autorizacion' => 'S', 'recibos' => 'N'));
        forma_pago::create(array('descripcion' => 'Transferencia Bancaria', 'estado' => 'A', 'banco' => 'S', 'casa' => 'N', 'cuenta' => 'N', 'documento' => 'S', 'autorizacion' => 'N', 'recibos' => 'N'));
        forma_pago::create(array('descripcion' => 'Recibos de Pago', 'estado' => 'A', 'banco' => 'N', 'casa' => 'N', 'cuenta' => 'N', 'documento' => 'N', 'autorizacion' => 'N', 'recibos' => 'S'));
    }
}
