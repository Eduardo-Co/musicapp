<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Music;
use App\Models\Album;
use App\Models\Artista;

class MusicFactory extends Factory
{
    protected $model = Music::class;


    public function definition()
    {
        $genres = [
            'Rock', 'Pop', 'Hip Hop', 'R&B', 'Country',
            'Jazz', 'Reggae', 'Electronic', 'Classical'
        ];

        return [
            'title' => $this->faker->sentence(),
            'genre' => $this->faker->randomElement($genres),
            'release_date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(180, 300), 
            'status' => $this->faker->randomElement(['actived', 'inactived']),
            'file_url' => $this->faker->url(),
            'album_id' => Album::inRandomOrder()->first()->id,
            'artist_id' => Artista::inRandomOrder()->first()->id,
        ];
    }
}
