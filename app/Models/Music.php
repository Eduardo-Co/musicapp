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

    ];

    protected $casts = [
        'release_date' => 'date',
        'duration' => 'integer',
    ];

    protected $attributes = [
        'status' => 'actived',
    ];
}
