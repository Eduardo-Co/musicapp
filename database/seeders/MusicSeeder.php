<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Music;

class MusicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Music::factory()
            ->count(30)
            ->create();
    }
}
