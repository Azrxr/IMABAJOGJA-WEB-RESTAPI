<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudy extends Model
{
    protected $table = 'program_studies';

    protected $fillable = [
        'university_id', 'faculty_id', 'name', 'jenjang', 'kd_program_study'
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
