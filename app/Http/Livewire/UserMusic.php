<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Music;

class UserMusic extends Component
{
    use WithPagination;

    public $currentMusicId = null;
    public $isPlaying = false;
    public $currentMusic = null;
    public $searchTerm = '';

    protected $paginationTheme = 'tailwind';

    public function play($musicId)
    {
        $this->currentMusicId = $musicId;
        $this->isPlaying = true;

        $music = Music::with('album')->find($musicId);
        if ($music) {
            $this->currentMusic = $music;
            $this->emit('playMusic', $this->formatMusicUrl($music->file_url), $music->title, $this->formatDuration($music->duration), $music->album_image);
        }
    }

    public function pause()
    {
        $this->isPlaying = false;
        $this->emit('pauseMusic');
    }

    public function togglePlayPause()
    {
        if ($this->isPlaying) {
            $this->pause();
        } else {
            $this->play($this->currentMusicId);
        }
    }

    public function next()
    {
        if ($this->currentMusicId === null) {
            return;
        }

        $currentIndex = $this->musics->search(function ($music) {
            return $music->id === $this->currentMusicId;
        });

        $nextIndex = ($currentIndex + 1) % $this->musics->count();
        $this->currentMusicId = $this->musics[$nextIndex]->id;
        $this->currentMusic = $this->musics[$nextIndex];
        $this->isPlaying = true;
        $this->emit('playMusic', $this->formatMusicUrl($this->musics[$nextIndex]->file_url), $this->musics[$nextIndex]->title, $this->formatDuration($this->musics[$nextIndex]->duration), $this->musics[$nextIndex]->album_image);
    }

    public function previous()
    {
        if ($this->currentMusicId === null) {
            return;
        }

        $currentIndex = $this->musics->search(function ($music) {
            return $music->id === $this->currentMusicId;
        });

        $previousIndex = ($currentIndex - 1 + $this->musics->count()) % $this->musics->count();
        $this->currentMusicId = $this->musics[$previousIndex]->id;
        $this->currentMusic = $this->musics[$previousIndex];
        $this->isPlaying = true;
        $this->emit('playMusic', $this->formatMusicUrl($this->musics[$previousIndex]->file_url), $this->musics[$previousIndex]->title, $this->formatDuration($this->musics[$previousIndex]->duration), $this->musics[$previousIndex]->album_image);
    }

    public function updatedSearchTerm()
    {
        $this->resetPage(); 
    }

    protected function formatMusicUrl($fileUrl)
    {
        return asset('storage/' . $fileUrl);
    }

    protected function formatDuration($seconds)
    {
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function render()
    {
        $musics = Music::with('album')
            ->where('title', 'like', '%' . $this->searchTerm . '%')
            ->paginate(10);

        return view('livewire.user-music', [
            'musics' => $musics,
        ]);
    }
}
