<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'fullname',
        'phone_number',
        'province',
        'regency',
        'district',
        'full_address',
        'profile_img',
        'member_status',
        'approval',
        'member_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
