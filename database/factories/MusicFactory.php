<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Music;
use App\Models\Artista; // Importar o modelo Artista se precisar criar relacionamentos

class MusicFactory extends Factory
{
    protected $model = Music::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
           'genre' => $this->faker->randomElement(['Rock', 'Pop', 'Hip Hop', 'R&B', 'Country', 'Jazz', 'Reggae', 'Electronic', 'Classical']),
            'release_date' => $this->faker->date(), 
            'duration' => $this->faker->numberBetween(120, 300), 
            'status' => $this->faker->randomElement(['actived', 'inactived']), 
            'file_url' => $this->faker->url, 
        ];

    }

    public function withArtistas($count = 1)
    {
        return $this->afterCreating(function (Music $music) use ($count) {
            $artistas = Artista::factory()->count($count)->create();
            $music->artistas()->attach($artistas);
        });
    }
}
