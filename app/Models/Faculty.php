<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;
    protected $fillable = ['id','university_id','name', 'kd_faculty'];

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function programStudy()
    {
        return $this->hasMany(ProgramStudy::class);
    }
}
