<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Playlist;

class PlaylistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Playlist::factory()
            ->count(30)
            ->create();
    }
}
