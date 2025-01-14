<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudy extends Model
{
    protected $table = 'program_studies';

    protected $fillable = [
        'campuse_id', 'program_study_name'
    ];

    public function campuse()
    {
        return $this->belongsTo(Campuse::class);
    }
}
