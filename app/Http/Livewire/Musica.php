<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Music as MusicModel;
use App\Models\Album;
use Illuminate\Support\Facades\Storage;
use App\Models\Artista;

class Musica extends Component
{
    use WithPagination, WithFileUploads;

    public $artist_id;
    public $title;
    public $genre;
    public $release_date;
    public $duration;
    public $status = 'actived';
    public $file_url;
    public $selectedMusicId;
    public $searchTerm = '';
    public $searchAlbum = '';
    public $searchArtist = '';
    public $album_id;
    public $isEditing = false;
    public $isCreating = false;
    public $viewingMusic = null;
    public $musicToDelete;
    public $showDeleteModal = false;	

    protected $rules = [
        'title' => 'required|string|max:255',
        'genre' => 'nullable|string|max:50',
        'release_date' => 'nullable|date',
        'duration' => 'required|integer',
        'status' => 'required|in:actived,inactived',
        'file_url' => 'required|file|mimes:mp3,wav,flac|max:20240',
        'album_id' => 'required|exists:albums,id',
        'artist_id' => 'required|exists:artistas,id',
    ];

    public function view($musicId)
    {
        $this->viewingMusic = MusicModel::findOrFail($musicId);
    }

    public function closeView()
    {
        $this->viewingMusic = null;
    }

    public function render()
    {
        $musics = MusicModel::where('title', 'like', '%' . $this->searchTerm . '%')
            ->paginate(10);

        $albuns = Album::where('name', 'like', '%' . $this->searchAlbum . '%')
            ->get();

        $artistas = Artista::where('nome', 'like', '%' . $this->searchArtist . '%')
            ->get();

        $start = max($musics->currentPage() - 2, 1);
        $end = min($musics->currentPage() + 2, $musics->lastPage());

        return view('livewire.musica', [
            'musics' => $musics,
            'albuns' => $albuns,
            'artists' => $artistas,
            'start' => $start,
            'end' => $end,
        ]);
    }

    public function edit($musicId)
    {
        $music = MusicModel::findOrFail($musicId);
        $this->selectedMusicId = $music->id;
        $this->title = $music->title;
        $this->genre = $music->genre;
        $this->release_date = $music->release_date;
        $this->duration = $music->duration;
        $this->status = $music->status;
        $this->file_url = $music->file_url;
        $this->album_id = $music->album_id;
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
        $this->validate($this->rules);

        $fileUrl = null;

        if ($this->file_url) {
            $fileUrl = $this->file_url->store('music_files', 'public');
        }

        if ($this->isEditing) {
            $music = MusicModel::find($this->selectedMusicId);

            if ($music->file_url && $fileUrl && $music->file_url !== $fileUrl) {
                Storage::disk('public')->delete($music->file_url);
            }

            $music->update([
                'title' => $this->title,
                'genre' => $this->genre,
                'release_date' => $this->release_date,
                'duration' => $this->duration,
                'status' => $this->status,
                'file_url' => $fileUrl ?? $music->file_url,
                'album_id' => $this->album_id,
                'artist_id' => $this->artist_id,
            ]);

            session()->flash('message', 'Música atualizada com sucesso.');

        } elseif ($this->isCreating) {
            MusicModel::create([
                'title' => $this->title,
                'genre' => $this->genre,
                'release_date' => $this->release_date,
                'duration' => $this->duration,
                'status' => $this->status,
                'file_url' => $fileUrl,
                'album_id' => $this->album_id,
                'artist_id' => $this->artist_id,
            ]);

            session()->flash('message', 'Música criada com sucesso.');
        }

        $this->resetInputFields();
    }

    public function resetInputFields()
    {
        $this->title = '';
        $this->genre = '';
        $this->release_date = '';
        $this->duration = '';
        $this->status = 'actived';
        $this->file_url = null;
        $this->selectedMusicId = null;
        $this->album_id = null;
        $this->searchAlbum = '';
        $this->searchArtist = '';
        $this->isEditing = false;
        $this->isCreating = false;
    }

    public function delete($musicId)
    {
        try {
            $this->dispatchBrowserEvent('delete-start');
    
            $music = MusicModel::findOrFail($musicId);
    
            if ($music->playlists()->exists()) {
                session()->flash('message-deleted', 'Não é possível deletar a música, pois ela está em uma playlist.');
                $this->dispatchBrowserEvent('delete-error');
                return;
            }
            if ($music->favoritedBy()->exists()) {
                session()->flash('message-deleted', 'Não é possível deletar a música, pois ela está marcada como favorita por algum usuário.');
                $this->dispatchBrowserEvent('delete-error');
                return;
            }

    
            if ($music->artista()->exists()) { 
                session()->flash('message-deleted', 'Não é possível deletar a música, pois ela está associada a um artista.');
                $this->dispatchBrowserEvent('delete-error');
                return;
            }
    
            if ($music->file_url) {
                Storage::disk('public')->delete($music->file_url);
            }
    
            $music->delete();
            $this->dispatchBrowserEvent('delete-finish');
            session()->flash('message-deleted', 'Música deletada com sucesso.');
    
        } catch (\Exception $e) {
            $this->dispatchBrowserEvent('delete-error');
            session()->flash('message-error', 'Erro ao deletar a música.');
        } finally {
            $this->musicToDelete = null;
            $this->showDeleteModal = false;
        }
    }
    
    
    public function confirmDelete($musicId)
    {
        $this->musicToDelete = $musicId;
        $this->showDeleteModal = true;
    }
    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->musicToDelete = null;
    }
}
