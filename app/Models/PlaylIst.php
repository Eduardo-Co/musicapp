<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Playlist extends Model
{
    use HasFactory;

    protected $table = 'playlists';

    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];
    
    public function musics()
    {
        return $this->belongsToMany(Music::class, 'playlist_music');
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}