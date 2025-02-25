<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationFile extends Model
{
    use HasFactory;
    protected $table = 'organization_files';
    protected $fillable = [
        'organization_profile_id',
        'title',
        'description',
        'file_path',
    ];

    public function organizationProfile(){
        return $this->belongsTo(OrganizationProfile::class, 'organization_profile_id');
    }
}
