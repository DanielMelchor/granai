<?php

use Illuminate\Database\Seeder;
use App\Producto;

class productoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Producto::create(array('id' => 0, 'empresa_id' => 1, 'clasificacion' => 'PROD', 'descripcion' => 'Recargo por cheque rechazado', 'descripcion_a_mostrar' => 'Recargo por cheque rechazado', 'medida_id' => 1, 'estado' => 'A'));
    }
}
