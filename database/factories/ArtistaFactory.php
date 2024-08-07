<?php

namespace Database\Factories;

use App\Models\Artista;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArtistaFactory extends Factory
{
    protected $model = Artista::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome' => $this->faker->name,
            'genero' => $this->faker->randomElement(['Rock', 'Pop', 'Hip Hop', 'R&B', 'Country', 'Jazz', 'Reggae', 'Electronic', 'Classical']),
        ];
    }
}
