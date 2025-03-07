<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomePhoto extends Model
{
    protected $table = 'home_photos';

    protected $fillable = [
        'document_id',
        'photo_title',
        'photo_img_path',
    ];

    protected $appends = ['photo_img_url'];
    public function getPhotoImgUrlAttribute()
    {
        return $this->photo_img_path ? url('storage/' . $this->photo_img_path) : null;
    }

    public function document()
    {
        return $this->hasMany(Document::class);
    }
}
