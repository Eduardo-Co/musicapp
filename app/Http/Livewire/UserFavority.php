<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Music;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Playlist;


class UserFavority extends Component
{
    use WithPagination;

    public $currentMusicId = null;
    public $isPlaying = false;
    public $currentMusic = null;
    public $searchTerm = '';
    protected $paginationTheme = 'tailwind';
    public $showPlaylists = false;
    public $playlists = [];
    public $currentPlaylistPage = 1;
    public $searchPlaylist = '';

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
    public function togglePlaylist()
    {
        $this->showPlaylists = true;
        $this->currentPlaylistPage = 1;
        $this->loadPlaylists();
    }

    public function nextPlaylist()
    {
        $this->currentPlaylistPage++;
        $this->loadPlaylists();
    }

    public function previousPlaylist()
    {
        if ($this->currentPlaylistPage > 1) {
            $this->currentPlaylistPage--;
            $this->loadPlaylists();
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
                Log::warning('No current music ID set.');
                return;
            }

            $user = auth()->user();

            $query = $user ? $user->favoriteMusics()->with('album') : Music::query();

            if ($this->searchTerm) {
                $query->where(function ($query) {
                    $query->where('musics.title', 'like', '%' . $this->searchTerm . '%')
                        ->orWhereHas('album', function ($q) {
                            $q->where('albums.name', 'like', '%' . $this->searchTerm . '%');
                        });
                });
            }

            $currentMusicIndex = $query->pluck('musics.id')->search($this->currentMusicId);
            
            if ($currentMusicIndex === false) {
                Log::warning('Current music not found in filtered favorites.');
                return;
            }

            $nextMusicId = $query->pluck('musics.id')->slice($currentMusicIndex + 1, 1)->first();

            if ($nextMusicId) {
                $nextMusic = $query->find($nextMusicId);
                $this->currentMusicId = $nextMusic->id;
                $this->isPlaying = true;
                $this->emit('playMusic', $this->formatMusicUrl($nextMusic->file_url), $nextMusic->title, $this->formatDuration($nextMusic->duration), $nextMusic->album_image);
                Log::info('Playing next music: ' . $nextMusic->id);
            } else {
                Log::info('No next music found in filtered favorites.');
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
                Log::warning('No current music ID set.');
                return;
            }

            $user = auth()->user();

            $query = $user ? $user->favoriteMusics()->with('album') : Music::query();

            if ($this->searchTerm) {
                $query->where(function ($query) {
                    $query->where('musics.title', 'like', '%' . $this->searchTerm . '%')
                        ->orWhereHas('album', function ($q) {
                            $q->where('albums.name', 'like', '%' . $this->searchTerm . '%');
                        });
                });
            }

            $currentMusicIndex = $query->pluck('musics.id')->search($this->currentMusicId);

            if ($currentMusicIndex === false) {
                Log::warning('Current music not found in filtered favorites.');
                return;
            }

            $previousMusicId = $query->pluck('musics.id')->slice($currentMusicIndex - 1, 1)->first();

            if ($previousMusicId) {
                $previousMusic = $query->find($previousMusicId);
                $this->currentMusicId = $previousMusic->id;
                $this->isPlaying = true;
                $this->emit('playMusic', $this->formatMusicUrl($previousMusic->file_url), $previousMusic->title, $this->formatDuration($previousMusic->duration), $previousMusic->album_image);
                Log::info('Playing previous music: ' . $previousMusic->id);
            } else {
                Log::info('No previous music found in filtered favorites.');
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
    public function updatedSearchPlaylist()
    {
        $this->loadPlaylists();
    }

    protected function loadPlaylists()
    {
        $query = Auth::user()->playlists();
    
        if ($this->searchPlaylist) {
            $query->where('name', 'like', '%' . $this->searchPlaylist . '%');
        }
    
        $this->playlists = $query
            ->skip(($this->currentPlaylistPage - 1) * 6)
            ->take(6)
            ->get();
    }
    public function addToPlaylist($playlistId)
    {
        try {
            $user = auth()->user();
            $music = Music::find($this->currentMusicId);

            if (!$music) {
                Log::warning('Music not found with ID: ' . $this->currentMusicId);
                $this->emit('errorOccurred', ['message' => 'Music not found.']);
                return;
            }

            $playlist = Playlist::find($playlistId);

            if (!$playlist) {
                Log::warning('Playlist not found with ID: ' . $playlistId);
                $this->emit('errorOccurred', ['message' => 'Playlist not found.']);
                return;
            }

            $playlist->musics()->attach($music->id);

            session()->flash('message', 'Adicionado a playlist com sucesso');
            
        } catch (\Exception $e) {
            Log::error('Error in addToPlaylist method: ' . $e->getMessage(), ['exception' => $e]);
            $this->emit('errorOccurred', ['message' => 'Could not add music to playlist.']);
        }
    }

    public function render()
    {
        try {
            $user = auth()->user();
            $query = $user ? $user->favoriteMusics()->with('album') : Music::query();

            if ($this->searchTerm) {
                $query->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->searchTerm . '%')
                        ->orWhereHas('album', function ($q) {
                            $q->where('name', 'like', '%' . $this->searchTerm . '%');
                        });
                });
            }

            $playlists = $this->showPlaylists ? $this->playlists : [];

            $favoriteMusics = $query->paginate(6);

            return view('livewire.user-favority', [
                'favoriteMusics' => $favoriteMusics,
                'playlists' => $playlists,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in render method: ' . $e->getMessage(), ['exception' => $e]);
            return view('livewire.user-favority', [
                'favoriteMusics' => collect(),
                'playlists' => [],
            ]);
        }
    }
}
