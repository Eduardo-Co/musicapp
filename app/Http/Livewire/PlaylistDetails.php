<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Music;
use App\Models\Playlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PlaylistDetails extends Component
{
    use WithPagination;

    public $currentMusicId = null;
    public $isPlaying = false;
    public $currentMusic = null;
    public $searchTerm = '';
    protected $paginationTheme = 'tailwind';
    public $playlistId = null; 

    public function mount($playlistId)
    {
        $this->playlistId = $playlistId;
    }

    public function play($musicId)
    {
        if ($this->currentMusicId === $musicId && $this->isPlaying) {
            return;
        }

        try {
            $this->currentMusicId = $musicId;
            $this->isPlaying = true;

            $music = Music::with('album')->find($musicId);
            if ($music) {
                $this->currentMusic = $music;
                $this->emit('playMusic', $this->formatMusicUrl($music->file_url), $music->title, $this->formatDuration($music->duration), $music->album_image);
            } else {
                Log::warning('Music not found with ID: ' . $musicId);
            }
        } catch (\Exception $e) {
            Log::error('Error in play method: ' . $e->getMessage(), ['exception' => $e]);
            $this->emit('errorOccurred', ['message' => 'Could not play music.']);
        }
    }

    public function pause()
    {
        try {
            $this->isPlaying = false;
            $this->emit('pauseMusic');
        } catch (\Exception $e) {
            Log::error('Error in pause method: ' . $e->getMessage(), ['exception' => $e]);
            $this->emit('errorOccurred', ['message' => 'Could not pause music.']);
        }
    }

    public function togglePlayPause()
    {
        try {
            if ($this->isPlaying) {
                $this->pause();
            } else {
                $this->play($this->currentMusicId);
            }
        } catch (\Exception $e) {
            Log::error('Error in togglePlayPause method: ' . $e->getMessage(), ['exception' => $e]);
            $this->emit('errorOccurred', ['message' => 'Could not toggle play/pause.']);
        }
    }
    public function next()
    {
        try {
            if ($this->currentMusicId === null) {
                return;
            }

            $currentMusic = Music::find($this->currentMusicId);
            if (!$currentMusic) {
                Log::warning('Current music not found.');
                return;
            }

            $nextMusic = Music::where('id', '>', $this->currentMusicId)
                ->orderBy('id')
                ->first();

            if ($nextMusic) {
                $this->currentMusicId = $nextMusic->id;
                $this->isPlaying = true;
                $this->emit('playMusic', $this->formatMusicUrl($nextMusic->file_url), $nextMusic->title, $this->formatDuration($nextMusic->duration), $nextMusic->album_image);
            } else {
                Log::info('No next music found.');
            }
        } catch (\Exception $e) {
            Log::error('Error in next method: ' . $e->getMessage(), ['exception' => $e]);
            $this->emit('errorOccurred', ['message' => 'Could not play next music.']);
        }
    }

    public function previous()
    {
        try {
            if ($this->currentMusicId === null) {
                return;
            }

            $currentMusic = Music::find($this->currentMusicId);
            if (!$currentMusic) {
                Log::warning('Current music not found.');
                return;
            }

            $previousMusic = Music::where('id', '<', $this->currentMusicId)
                ->orderByDesc('id')
                ->first();

            if ($previousMusic) {
                $this->currentMusicId = $previousMusic->id;
                $this->isPlaying = true;
                $this->emit('playMusic', $this->formatMusicUrl($previousMusic->file_url), $previousMusic->title, $this->formatDuration($previousMusic->duration), $previousMusic->album_image);
            } else {
                Log::info('No previous music found.');
            }
        } catch (\Exception $e) {
            Log::error('Error in previous method: ' . $e->getMessage(), ['exception' => $e]);
            $this->emit('errorOccurred', ['message' => 'Could not play previous music.']);
        }
    }

    public function toggleFavorite($musicId)
    {
        try {
            $user = auth()->user();
            $music = Music::find($musicId);

            if ($music) {
                $isFavorite = $user->favoriteMusics()->where('music_id', $musicId)->exists();

                if ($isFavorite) {
                    $user->favoriteMusics()->detach($musicId);
                } else {
                    $user->favoriteMusics()->attach($musicId);
                }
                $this->emit('favoriteUpdated');
            } else {
                Log::warning('Music not found with ID: ' . $musicId);
            }
        } catch (\Exception $e) {
            Log::error('Error in toggleFavorite method: ' . $e->getMessage(), ['exception' => $e]);
            $this->emit('errorOccurred', ['message' => 'Could not update favorite status.']);
        }
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

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function removeFromPlaylist($musicId)
    {
        try {
            $playlist = Playlist::find($this->playlistId);
            if (!$playlist) {
                Log::warning('Playlist not found with ID: ' . $this->playlistId);
                $this->emit('errorOccurred', ['message' => 'Playlist not found.']);
                return;
            }

            $music = Music::find($musicId);
            if (!$music) {
                Log::warning('Music not found with ID: ' . $musicId);
                $this->emit('errorOccurred', ['message' => 'Music not found.']);
                return;
            }

            $playlist->musics()->detach($musicId);

            session()->flash('message', 'Removido da playlist com sucesso');

        } catch (\Exception $e) {
            Log::error('Error in removeFromPlaylist method: ' . $e->getMessage(), ['exception' => $e]);
            $this->emit('errorOccurred', ['message' => 'Could not remove music from playlist.']);
        }
    }

    public function render()
    {
        try {
            $query = Music::with('album')->where('title', 'like', '%' . $this->searchTerm . '%');

            if ($this->playlistId) {
                $query->whereHas('playlists', function ($q) {
                    $q->where('playlists.id', $this->playlistId);
                });
            }

            $musics = $query->paginate(10);


            return view('livewire.playlist-details', [
                'musics' => $musics,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in render method: ' . $e->getMessage(), ['exception' => $e]);
            return view('livewire.playlist-details', [
                'musics' => collect(),
            ]);
        }
    }
}
