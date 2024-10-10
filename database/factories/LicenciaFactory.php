<?php

namespace Database\Factories;

use App\Models\Licencia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Licencia>
 */
class LicenciaFactory extends Factory
{
    protected $model = Licencia::class;

    public function definition()
    {
        return [
            'id_licencia' => $this->faker->unique()->numerify('############'),
            'programa' => $this->faker->company,
            'fecha_compra' => Carbon::now()->startOfDay(),
            'estado' => $this->faker->randomElement([15, 30, 91, 182]),
            'detalles' => $this->faker->text,
        ];
    }
}
