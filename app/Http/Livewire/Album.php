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
    public $isEditing = false;
    public $isCreating = false;
    public $viewingAlbum = null;
    public $albumToDelete;
    public $showDeleteModal = false;	

    
    protected $rules = [
        'name' => 'required|string|max:255',
        'release_date' => 'nullable|date',
        'foto_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'artist_id' => 'required|exists:artistas,id',
    ];

    public function updatingSearchArtist()
    {
        $this->resetPage();
    }
    
    public function view($albumId)
    {
        $this->viewingAlbum = AlbumModel::findOrFail($albumId);
    }

    public function closeView()
    {
        $this->viewingAlbum = null; 
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
            'albuns' => $albuns, 
            'artists' => $artists,
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
        $this->artist_id = $album->artist_id;
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
                'artist_id' => $this->artist_id,
            ]);
    
            session()->flash('message', 'Álbum atualizado com sucesso.');

        } elseif ($this->isCreating) {
            $album = AlbumModel::create([
                'name' => $this->name,
                'release_date' => $this->release_date,
                'foto_url' => $coverImageUrl,
                'artist_id' => $this->artist_id,
            ]);
    
        }
    
        $this->resetInputFields();
    }
    
    public function resetInputFields()
    {
        $this->name = '';
        $this->release_date = '';
        $this->foto_url = null;
        $this->selectedAlbumId = null;
        $this->artist_id = '';
        $this->searchArtist = '';
        $this->isEditing = false;
        $this->isCreating = false;
    }

    public function delete()
    {
        $album = AlbumModel::findOrFail($this->albumToDelete);

        if ($album->musics()->exists()) {
            session()->flash('message-deleted', 'O álbum não pode ser deletado porque possui músicas associadas.');    
        }else {
            if ($album->foto_url) {
                Storage::disk('public')->delete($album->foto_url);
            }
            $album->delete();
            session()->flash('message-deleted', 'Álbum deletado com sucesso.');
        }
        $this->albumToDelete = null;
        $this->showDeleteModal = false;
    }
    public function confirmDelete($albumId)
    {
        $this->albumToDelete = $albumId;
        $this->showDeleteModal = true;
    }
    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->albumToDelete = null;
    }
}
