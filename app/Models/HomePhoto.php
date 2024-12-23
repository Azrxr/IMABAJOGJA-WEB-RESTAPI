<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePhoto extends Model
{
    protected $table = 'home_photos';

    protected $fillable = [
        'photo_title',
        'photo_img',
    ];
}
