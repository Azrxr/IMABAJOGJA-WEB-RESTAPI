<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyPlane extends Model
{
    use HasFactory;
    protected $fillable = [
        'id','university_id','program_study_id','member_id','status'
    ];

    public function university()
    {
        return $this->belongsTo(University::class);
    }
    public function programStudy()
    {
        return $this->belongsTo(ProgramStudy::class);
    }
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
