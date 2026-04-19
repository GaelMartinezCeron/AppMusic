<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
   protected $fillable = [
    'title',
    'artist',
    'file_path',
    'image',
    'genre_id'
];
}