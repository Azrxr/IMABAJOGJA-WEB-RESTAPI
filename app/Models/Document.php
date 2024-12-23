<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'member_id',
        'ijazah',
        'home_photo_id',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function homePhoto()
    {
        return $this->belongsTo(HomePhoto::class);
    }
}
