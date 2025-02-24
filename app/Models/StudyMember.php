<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyMember extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'member_id',
        'university_id',
        'faculty_id',
        'program_study_id'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    public function university()
    {
        return $this->belongsTo(University::class);
    }
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
    public function programStudy()
    {
        return $this->belongsTo(ProgramStudy::class);
    }
}
