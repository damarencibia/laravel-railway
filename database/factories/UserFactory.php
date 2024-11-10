<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @param array $attributes
     * @return array
     */
    public function definition(array $attributes = [])
    {
        return [
            'name' => 'admin',
            'status' => 'comprador',
            'provider' => 1,
            'password' => bcrypt('12345678'),
        ];
    }

    // Si necesitas métodos adicionales, puedes agregarlos aquí
}
