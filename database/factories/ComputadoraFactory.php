<?php

namespace Database\Factories;

use App\Models\Computadora;
use App\Models\Pieza;
use App\Models\Componente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComputadoraFactory extends Factory
{
    protected $model = Computadora::class;

    public function definition()
    {
        return [
            'nro_expediente' => $this->faker->unique()->numerify('PC-#####'),
            'departamento' => $this->faker->word(),
            'usuario' => $this->faker->name(),
            'cpu_torre' => $this->faker->randomElement(Pieza::pluck('nro_inventario')),
            'monitor' => $this->faker->randomElement(Pieza::pluck('nro_inventario')),
            'mouse' => $this->faker->randomElement(Pieza::pluck('nro_inventario')),
            'teclado' => $this->faker->randomElement(Pieza::pluck('nro_inventario')),
            'ups' => $this->faker->randomElement(Pieza::pluck('nro_inventario')),
            'bocinas' => $this->faker->randomElement(Pieza::pluck('nro_inventario')),
            'placa_base' => $this->faker->randomElement(Componente::pluck('nro_serie')),
            'ram' => $this->faker->randomElement(Componente::pluck('nro_serie')),
            'lector_cd' => $this->faker->randomElement(Componente::pluck('nro_serie')),
            'disco_duro' => $this->faker->randomElement(Componente::pluck('nro_serie')),
            'local_climatizado' => $this->faker->boolean(),
            'local_sd_mcmpt' => $this->faker->boolean(),
            'so' => $this->faker->word(),
            'responsable' => $this->faker->name(),
            'jefe_seg_inf' => $this->faker->name(),
        ];
    }
}
