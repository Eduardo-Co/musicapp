<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Playlist;
use Illuminate\Support\Facades\Auth;

class Playlists extends Component
{
    use WithPagination;

    public $showCreatePlaylistModal = false;
    public $playlistName = ''; // Usado para criar e editar
    public $editPlaylistId = null;
    public $searchTerm = '';
    public $showDeleteModal = false;
    public $playlistToDeleteId = null;

    protected $paginationTheme = 'tailwind'; 

    protected $rules = [
        'playlistName' => 'required|string|max:255',
    ];

    public function editPlaylist($id)
    {
        $playlist = Playlist::find($id);
        if ($playlist && $playlist->user_id === Auth::id()) {
            $this->editPlaylistId = $playlist->id;
            $this->playlistName = $playlist->name;
            $this->showCreatePlaylistModal = true;
        }
    }

    public function updatePlaylist()
    {
        $this->validate();

        $playlist = Playlist::find($this->editPlaylistId);
        if ($playlist && $playlist->user_id === Auth::id()) {
            $playlist->name = $this->playlistName;
            $playlist->save();

            session()->flash('message', 'Playlist updated successfully.');
        } else {
            session()->flash('message-deleted', 'Playlist update failed.');
        }

        $this->resetVariables();
        $this->showCreatePlaylistModal = false;
        $this->emit('playlistUpdated'); 
    }

    public function deletePlaylist($id)
    {
        $playlist = Playlist::find($id);
        if ($playlist && $playlist->user_id === Auth::id()) {
            $playlist->delete();

            session()->flash('message-deleted', 'Playlist deleted successfully.');
        } else {
            session()->flash('message', 'Playlist deletion failed.');
        }

        $this->resetVariables();
        $this->showDeleteModal = false;
        $this->emit('playlistDeleted'); 
    }

    public function confirmDelete($id)
    {
        $this->playlistToDeleteId = $id;
        $this->showDeleteModal = true;
    }

    public function showCreatePlaylistModal()
    {
        $this->reset('playlistName');
        $this->showCreatePlaylistModal = true;
    }

    public function hideCreatePlaylistModal()
    {
        $this->showCreatePlaylistModal = false;
    }

    public function hideDeleteModal()
    {
        $this->showDeleteModal = false;
    }

    public function createPlaylist()
    {
        $this->validate();

        $user = Auth::user();

        $playlist = new Playlist();
        $playlist->name = $this->playlistName;
        $playlist->user_id = $user->id;
        $playlist->save();

        session()->flash('message', 'Playlist created successfully.');

        $this->resetVariables();
        $this->showCreatePlaylistModal = false;
        $this->emit('playlistCreated'); 
    }

    public function resetVariables()
    {
        $this->playlistName = '';
        $this->editPlaylistId = null;
        $this->playlistToDeleteId = null;
    }

    public function viewPlaylist($id)
    {
        return redirect()->route('playlist.show', ['id' => $id]);
    }

    public function render()
    {
        $playlists = Playlist::where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->searchTerm}%");
            })
            ->paginate(9);

        return view('livewire.playlists', ['playlists' => $playlists]);
    }
}
