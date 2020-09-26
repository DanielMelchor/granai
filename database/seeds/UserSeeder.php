<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Fernando Granai',
            'username' => 'fgranai',
            'password' => Hash::make('amad'),
            'estado' => 'A',
            'empresa_id' => '1'
        ]);

        DB::table('users')->insert([
            'name' => 'Dina de Granai',
            'username' => 'ddgranai',
            'password' => Hash::make('amad'),
            'estado' => 'A',
            'empresa_id' => '1'
        ]);

        DB::table('users')->insert([
            'name' => 'Brenda Ramirez',
            'username' => 'bramirez',
            'password' => Hash::make('amad'),
            'estado' => 'A',
            'empresa_id' => '1'
        ]);
    }
}
