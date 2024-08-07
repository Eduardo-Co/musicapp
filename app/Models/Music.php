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
    
}
