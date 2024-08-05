<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Music as MusicModel;
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
    public $isEditing = false;
    public $isCreating = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'genre' => 'nullable|string|max:50',
        'release_date' => 'nullable|date',
        'duration' => 'nullable|integer',
        'status' => 'in:actived,inactived',
        'file_url' => 'nullable|file|mimes:mp3,wav,flac|max:20240', 
    ];

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function render()
    {
        $musics = MusicModel::where('title', 'like', '%' . $this->searchTerm . '%')
                        ->paginate(10);

        $start = max($musics->currentPage() - 2, 1);
        $end = min($musics->currentPage() + 2, $musics->lastPage());

        return view('livewire.musica', [
            'musics' => $musics,
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
        $this->dispatchBrowserEvent('delaySubmit', ['delay' => 1000]);

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
        session()->flash('message', 'Música deletada com sucesso.');
    }
}
