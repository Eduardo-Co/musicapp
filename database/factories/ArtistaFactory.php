<?php

namespace Database\Factories;

use App\Models\Artista;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArtistaFactory extends Factory
{
    protected $model = Artista::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->name(), 
            'genero' => $this->faker->randomElement(['Rock', 'Pop', 'Hip Hop', 'R&B', 'Country', 'Jazz', 'Reggae', 'Electronic', 'Classical']),
            'foto_url' => $this->faker->imageUrl(640, 480, 'people'), 
        ];
    }
}
