<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Music;

class UserFavority extends Component
{
    public function render()
    {
        $user = auth()->user();
        
        $favoriteMusics = $user ? $user->favoriteMusics : collect();

        return view('livewire.user-favority', [
            'favoriteMusics' => $favoriteMusics,
        ]);
    }
}
