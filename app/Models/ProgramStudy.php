<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudy extends Model
{
    protected $table = 'program_studies';

    protected $fillable = [
        'univerty_id', 'faculty_id', 'name', 'jenjang' 
    ];

    public function university()
    {
        return $this->belongsTo(university::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
