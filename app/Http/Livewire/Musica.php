<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Music as MusicModel;
use App\Models\Album;
use Illuminate\Support\Facades\Storage;

class Musica extends Component
{
    use WithPagination, WithFileUploads;

    public $title;
    public $genre;
    public $release_date;
    public $duration;
    public $status = 'actived';
    public $file_url;
    public $selectedMusicId;
    public $searchTerm = '';
    public $searchAlbum = '';
    public $album_id;
    public $isEditing = false;
    public $isCreating = false;
    public $viewingMusic = null;

    protected $rules = [
        'title' => 'required|string|max:255',
        'genre' => 'required|string|max:50',
        'release_date' => 'required|date',
        'duration' => 'required|integer',
        'status' => 'required|in:actived,inactived',
        'file_url' => 'required|file|mimes:mp3,wav,flac|max:20240',
        'album_id' => 'required|exists:albums,id',
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

        $start = max($musics->currentPage() - 2, 1);
        $end = min($musics->currentPage() + 2, $musics->lastPage());

        return view('livewire.musica', [
            'musics' => $musics,
            'albuns' => $albuns,
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
        $this->validate();

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
        $this->isEditing = false;
        $this->isCreating = false;
    }

    public function delete($musicId)
    {
        $music = MusicModel::findOrFail($musicId);
        if ($music->file_url) {
            Storage::disk('public')->delete($music->file_url);
        }
        $music->delete();
        session()->flash('message-deleted', 'Música deletada com sucesso.');
    }
}
