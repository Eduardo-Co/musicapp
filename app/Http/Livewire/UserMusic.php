<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Music;

class UserMusic extends Component
{
    public $musics;

    public function mount()
    {
        $this->musics = Music::all();
    }

    public function render()
    {
        return view('livewire.user-music', ['musics' => $this->musics]);
    }
}
