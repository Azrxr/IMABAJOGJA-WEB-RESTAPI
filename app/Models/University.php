<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;
    protected $fillable = ['id','name'];

    public function faculty()
    {
        return $this->hasMany(Faculty::class);
    }

    public function programStudy()
    {
        return $this->belongsTo(ProgramStudy::class);
    }
}
