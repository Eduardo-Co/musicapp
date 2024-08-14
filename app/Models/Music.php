<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    protected $table = 'musics';

    protected $fillable = [
        'title',
        'genre',
        'release_date',
        'duration',
        'status',
        'file_url',
        'album_id',
        'artist_id'

    ];
    

    protected $casts = [
        'release_date' => 'date',
        'duration' => 'integer',
    ];

    protected $attributes = [
        'status' => 'actived',
    ];




    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorite_music_user', 'music_id', 'user_id');
    }
    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_music');
    }

    public function artista()
    {
        return $this->belongsTo(Artista::class);
    }
}
