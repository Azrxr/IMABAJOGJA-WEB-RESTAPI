<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;
    protected $fillable = ['id','name'];
    
    public function regency()
    {
        return $this->hasMany(Regency::class);
    }
}
