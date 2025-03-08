<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'fullname',
        'phone_number',
        'provincy_id',
        'regency_id',
        'district_id',
        'full_address',
        'profile_img_path',
    ];

    protected $appends = ['profile_img_url'];
    public function getProfileImgUrlAttribute()
    {
        return $this->profile_img_path ? url('storage/' . $this->profile_img_path) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function provincy()
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
