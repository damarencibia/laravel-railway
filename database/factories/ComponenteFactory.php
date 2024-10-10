<?php

namespace Database\Factories;

use App\Models\Componente;
use Illuminate\Database\Eloquent\Factories\Factory;


class ComponenteFactory extends Factory
{
    protected $model = Componente::class;

    public function definition()
    {
        return [
            // 'nro_serie' => $this->faker->unique()->numerify('COMPONENTE-#####'),
            'nro_serie' => $this->faker->unique()->numerify('#####'),
            'marca' => $this->faker->company,
            'tipo_componente' => $this->faker->randomElement(['placa_base', 'memoria_ram', 'lector_cd', 'disco_duro']),
            'user_id' => '1',
            // 'disponible'=> $this->faker->boolean(),
        ];
    }
}
