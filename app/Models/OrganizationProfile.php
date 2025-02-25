<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationProfile extends Model
{
    use HasFactory;
    protected $table = 'organization_profiles';
    protected $fillable = [
        'title',
        'description',
        'vision',
        'mission',
        'contact_email',
        'contact_phone',
        'contact_phone2',
        'address',
    ];

    public function file(){
        return $this->hasMany(OrganizationFile::class, 'fileable');
    }
}
