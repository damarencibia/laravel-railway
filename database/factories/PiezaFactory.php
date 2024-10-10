<?php

namespace Database\Factories;

use App\Models\Pieza;
use Illuminate\Database\Eloquent\Factories\Factory;

class PiezaFactory extends Factory
{
    protected $model = Pieza::class;

    public function definition()
    {
        return [
            // 'nro_inventario' => $this->faker->unique()->numerify('PIEZA-#####'),
            'nro_inventario' => $this->faker->unique()->numerify('#####'),
            'marca' => $this->faker->company,
            'color' => $this->faker->safeColorName,
            'tipo_de_pieza' => $this->faker->randomElement(['cpu_torre', 'monitor', 'mouse', 'teclado', 'ups', 'bocinas']),
            // 'disponible'=> $this->faker->boolean()

        ];
    }
}
