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
    protected $appends = ['file_url'];
    public function getFileUrlAttribute()
    {
        return $this->file_path ? url('storage/' . $this->file_path) : null;
    }

    public function organizationProfile(){
        return $this->belongsTo(OrganizationProfile::class, 'organization_profile_id');
    }
}
