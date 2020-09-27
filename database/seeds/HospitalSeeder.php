<?php

use Illuminate\Database\Seeder;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hospitales')->insert(['nombre' => 'Clinica Granai', 'direccion' => '9 Calle 4-52 Zona 10 Integra Medical Center 1 Nivel 8, Clínica 802', 'contacto' => 'pendiente', 'principal_agenda' => 'S', 'referencia' => 'N', 'estado' => 'A']);

        DB::table('hospitales')->insert(['nombre' => 'Hospital Herrera Llerandi', 'direccion' => '6a. Avenida, 8-71 Zona 10', 'contacto' => 'pendiente', 'principal_agenda' => 'N', 'referencia' => 'N', 'estado' => 'A']);
        DB::table('hospitales')->insert(['nombre' => 'Centro Médico', 'direccion' => '6ª. Avenida 3-47, Zona 10', 'contacto' => 'pendiente', 'principal_agenda' => 'N', 'referencia' => 'N', 'estado' => 'A']);
        DB::table('hospitales')->insert(['nombre' => 'Digastro, S. A.', 'direccion' => '3 C A 6-15 Z-10', 'contacto' => 'pendiente', 'principal_agenda' => 'N', 'referencia' => 'N', 'estado' => 'A']);
    }
}
