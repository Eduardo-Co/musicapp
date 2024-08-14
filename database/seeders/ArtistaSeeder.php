<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artista;

class ArtistaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artista::factory()
            ->count(30)
            ->create();
    }
}
