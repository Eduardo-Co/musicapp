<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Playlist;
use App\Models\User;

class PlaylistFactory extends Factory
{

    protected $model = Playlist::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(),
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
