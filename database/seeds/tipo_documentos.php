<?php

use Illuminate\Database\Seeder;
use App\Tipo_documento;

class tipo_documentos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tipo_documento::create(array('descripcion' => 'Factura', 'Signo' => 1, 'estado' => 'A'));
        Tipo_documento::create(array('descripcion' => 'Nota de Crédito', 'Signo' => -1, 'estado' => 'A'));
        Tipo_documento::create(array('descripcion' => 'Nota de Débito', 'Signo' => 1, 'estado' => 'A'));
        Tipo_documento::create(array('descripcion' => 'Recibo de Pago', 'Signo' => -1, 'estado' => 'A'));
    }
}
