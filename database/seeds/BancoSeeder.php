<?php

use Illuminate\Database\Seeder;

class BancoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bancos')->insert(['nombre' => 'Banco Agromercantil de Guatemala, S. A.', 'tipo_referencia' => 'B', 'estado' => 'A']);
        DB::table('bancos')->insert(['nombre' => 'Banco Industrial, S. A.', 'tipo_referencia' => 'B', 'estado' => 'A']);
        DB::table('bancos')->insert(['nombre' => 'Banco de América Central, S. A.', 'tipo_referencia' => 'B', 'estado' => 'A']);
        DB::table('bancos')->insert(['nombre' => 'Banco Promerica, S. A.', 'tipo_referencia' => 'B', 'estado' => 'A']);
        DB::table('bancos')->insert(['nombre' => 'Banco Internacional, S. A.', 'tipo_referencia' => 'B', 'estado' => 'A']);
        DB::table('bancos')->insert(['nombre' => 'Banco G&T Continental, S. A.', 'tipo_referencia' => 'B', 'estado' => 'A']);
        DB::table('bancos')->insert(['nombre' => 'Banco de Desarrollo Rural, S. A.', 'tipo_referencia' => 'B', 'estado' => 'A']);
        DB::table('bancos')->insert(['nombre' => 'Banco de los Trabajadores', 'tipo_referencia' => 'B', 'estado' => 'A']);
        DB::table('bancos')->insert(['nombre' => 'Vivibanco, S. A.', 'tipo_referencia' => 'B', 'estado' => 'A']);
        DB::table('bancos')->insert(['nombre' => 'Banco Ficohsa Guatemala, S. A.', 'tipo_referencia' => 'B', 'estado' => 'A']);
        DB::table('bancos')->insert(['nombre' => 'Credomatic', 'tipo_referencia' => 'T', 'estado' => 'A']);
        DB::table('bancos')->insert(['nombre' => 'Visa', 'tipo_referencia' => 'T', 'estado' => 'A']);
    }
}
