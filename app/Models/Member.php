<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'id', 'user_id', 'no_member', 'angkatan', 'fullname', 'phone_number',
        'province_id', 'regency_id', 'district_id', 'full_address', 'agama',
        'nisn', 'tempat', 'tanggal_lahir', 'gender', 'kode_pos', 'member_type',
        'profile_img_path', 'scholl_origin', 'tahun_lulus', 'is_studyng'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
