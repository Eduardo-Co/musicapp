<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Artista;

class ArtistaFactory extends Factory
{
 
    protected $model = Artista::class;

 
    public function definition()
    {
        $genres = [
            'Rock', 'Pop', 'Hip Hop', 'R&B', 'Country',
            'Jazz', 'Reggae', 'Electronic', 'Classical'
        ];

        return [
            'nome' => $this->faker->name(),
            'genero' => $this->faker->randomElement($genres),
            'foto_url' => $this->faker->imageUrl(640, 480, 'artists', true, 'artist'),
        ];
    }
}
