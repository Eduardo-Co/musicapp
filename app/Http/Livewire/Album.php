<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Album as AlbumModel;
use App\Models\Artista;
use Illuminate\Support\Facades\Storage;

class Album extends Component
{
    use WithPagination, WithFileUploads;

    public $name;
    public $release_date;
    public $foto_url;
    public $selectedAlbumId;
    public $searchTerm = '';
    public $searchArtist = '';
    public $artist_id; 
    public $selectedArtists = []; 
    public $isEditing = false;
    public $isCreating = false;
    public $viewingAlbum = null;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'release_date' => 'required|date',
        'foto_url' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'selectedArtists' => 'required|array',
        'selectedArtists.*.id' => 'exists:artistas,id',
    ];

    public function updatingSearchArtist()
    {
        $this->resetPage();
    }

    public function updatedArtistId()
    {
        $this->addArtist();
    }

    public function addArtist()
    {
        if ($this->artist_id && !in_array($this->artist_id, array_column($this->selectedArtists, 'id'))) {
            $artist = Artista::find($this->artist_id);
            if ($artist) {
                $this->selectedArtists[] = $artist->toArray();
            }
        }
        $this->artist_id = null; 
    }
    
    public function removeArtist($artistId)
    {
        $this->selectedArtists = array_filter($this->selectedArtists, function ($artist) use ($artistId) {
            return $artist['id'] != $artistId;
        });
    }

    public function view($albumId)
    {
        $this->viewingAlbum = AlbumModel::findOrFail($albumId);
    }

    public function closeView()
    {
        $this->viewingAlbum = null; // Fechar o modal
    }
    
    public function render()
    {
        $albuns = AlbumModel::where('name', 'like', '%' . $this->searchTerm . '%')
            ->paginate(10);
    
        $artists = Artista::where('nome', 'like', '%' . $this->searchArtist . '%')
            ->get();
    
        $start = max($albuns->currentPage() - 2, 1);
        $end = min($albuns->currentPage() + 2, $albuns->lastPage());
    
        return view('livewire.album', [
            'albuns' => $albuns, // Certifique-se de que esta linha está aqui
            'artists' => $artists,
            'selectedArtists' => $this->selectedArtists,
            'start' => $start,
            'end' => $end,
        ]);
    }
    
    public function edit($albumId)
    {
        $album = AlbumModel::findOrFail($albumId);
        $this->selectedAlbumId = $album->id;
        $this->name = $album->name;
        $this->release_date = $album->release_date;
        $this->foto_url = $album->foto_url;
        $this->selectedArtists = $album->artistas()->get()->toArray(); // Atualizar selectedArtists
        $this->isEditing = true;
        $this->isCreating = false;
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isEditing = false;
        $this->isCreating = true;
    }

    public function save()
    {
        $this->validate();
    
        $coverImageUrl = null;
    
        if ($this->foto_url) {
            $coverImageUrl = $this->foto_url->store('album_covers', 'public');
        }
    
        if ($this->isEditing) {
            $album = AlbumModel::find($this->selectedAlbumId);
    
            if ($album->foto_url && $coverImageUrl && $album->foto_url !== $coverImageUrl) {
                Storage::disk('public')->delete($album->foto_url);
            }
    
            $album->update([
                'name' => $this->name,
                'release_date' => $this->release_date,
                'foto_url' => $coverImageUrl ?? $album->foto_url,
            ]);
    
            $album->artistas()->sync(array_column($this->selectedArtists, 'id')); 
            session()->flash('message', 'Álbum atualizado com sucesso.');

        } elseif ($this->isCreating) {
            $album = AlbumModel::create([
                'name' => $this->name,
                'release_date' => $this->release_date,
                'foto_url' => $coverImageUrl,
            ]);
    
            $album->artistas()->attach(array_column($this->selectedArtists, 'id')); 
            session()->flash('message', 'Álbum criado com sucesso.');
        }
    
        $this->resetInputFields();
    }
    
    public function resetInputFields()
    {
        $this->name = '';
        $this->release_date = '';
        $this->foto_url = null;
        $this->selectedAlbumId = null;
        $this->selectedArtists = [];
        $this->searchArtist = '';
        $this->isEditing = false;
        $this->isCreating = false;
    }

    public function delete($albumId)
    {
        $album = AlbumModel::findOrFail($albumId);
        if ($album->foto_url) {
            Storage::disk('public')->delete($album->foto_url);
        }
        $album->artistas()->detach();
        $album->delete();
        session()->flash('message-deleted', 'Álbum deletado com sucesso.');
    }
}
