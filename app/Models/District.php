<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $fillable = ['id','regency_id','name'];

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }
}
