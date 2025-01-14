<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campuse extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'campuse_name', 'beasiswa'
        
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function studies()
    {
        return $this->hasMany(ProgramStudy::class);
    }
}
