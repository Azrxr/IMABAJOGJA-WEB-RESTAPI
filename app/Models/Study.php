<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Study extends Model
{
    protected $table = 'studies';

    protected $fillable = [
        'member_id',
        'perguruan_tinggi_name',
        'fakultas',
        'program_study',
        'tahun_masuk',
        'rencana_masuk',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
