<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlbumFactory extends Factory
{
    protected $model = Album::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(), 
            'release_date' => $this->faker->dateTimeBetween('-10 years', 'now'), 
            'foto_url' => $this->faker->imageUrl(640, 480, 'album'),
        ];
    }
}
