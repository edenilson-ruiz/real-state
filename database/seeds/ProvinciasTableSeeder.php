<?php

use App\Provincia;
use Illuminate\Database\Seeder;

class ProvinciasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provincias = [
            'San José',
            'Alajuela',
            'Cartago',
            'Heredia',
            'Guanacaste',
            'Puntarenas',
            'Limón'
        ];

        foreach ($provincias as $provinciaName) {
            Provincia::create([
               'name' => $provinciaName
            ]);
        }
    }
}
