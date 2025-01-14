<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'id', 'user_id','fullname','phone_number', 'profile_img',
        'province', 'regency', 'address', 'kode_pos',
        'agama', 'nisn', 'tempat', 'tanggal_lahir', 'gender',
        'member_type', 
        'scholl_origin', 'tahun_lulus',

        'kampus', 'fakultas', 'prodi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
