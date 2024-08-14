<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Album;
use App\Models\Artista; // Ensure this is imported

class AlbumFactory extends Factory
{

    protected $model = Album::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'release_date' => $this->faker->date(),
            'foto_url' => $this->faker->imageUrl(640, 480, 'albums', true, 'album'),
            'artist_id' => Artista::inRandomOrder()->first()->id, 
        ];
    }
}
