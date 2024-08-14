<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
   
     protected $fillable = [
        'name', 'release_date','foto_url', 'artist_id',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'release_date' => 'datetime',
    ];

    /**
     * The artistas that belong to the album.
     */
    public function artista()
    {
        return $this->belongsTo(Artista::class, 'artist_id');
    }
    public function musics()
    {
        return $this->hasMany(Music::class);
    }
    
}
