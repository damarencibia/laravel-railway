<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pieza;
use App\Models\Componente;
use App\Models\Licencia;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Pieza::factory(12)->create();
        Componente::factory(8)->create();
        Licencia::factory(8)->create();
        User::factory(1)->create();

    }
}
