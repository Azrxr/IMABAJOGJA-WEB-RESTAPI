<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePhoto extends Model
{
    protected $table = 'home_photos';

    protected $fillable = [
        'document_id',
        'photo_title',
        'photo_img',
    ];

    public function document()
    {
        return $this->hasMany(Document::class);
    }
}
