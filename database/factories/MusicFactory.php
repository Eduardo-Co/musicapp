<?php

namespace Database\Factories;

use App\Models\Music;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MusicFactory extends Factory
{
    protected $model = Music::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3), 
            'genre' => $this->faker->randomElement(['Rock', 'Pop', 'Hip Hop', 'R&B', 'Country', 'Jazz', 'Reggae', 'Electronic', 'Classical']),
            'release_date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(120, 600), 
            'status' => 'actived',
            'file_url' => $this->faker->url(), 
            'album_id' => \App\Models\Album::factory(), 
        ];
    }
}
