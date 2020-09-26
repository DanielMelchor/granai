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
        forma_pago::create(array('descripcion' => 'Efectivo', 'estado' => 'A'));
        forma_pago::create(array('descripcion' => 'Cheque', 'estado' => 'A'));
        forma_pago::create(array('descripcion' => 'Tarjeta', 'estado' => 'A'));
    }
}
