<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'member_id',
        'ijazah',
        'ktp',
        'kk',
        'ijazah_skl',
        'raport',
        'photo_3x4',
        //berkas sipil
        'kk_legalisir',
        'akte_legalisir',
        //berkas sekolah
        'skhu_legalisir',
        'raport_legalisir',
        'surat_baik',
        'surat_rekom_kades',
        'surat_keterangan_baik',
        'surat_penghasilan_ortu',
        'surat_tidak_mampu',
        'surat_pajak_bumi_bangunan',
        'surat_tidak_pdam',
        'token_listrik',
        //berkas kapolsek
        'skck',
        //berkas lain2
        'sertifikat_prestasi',
        'foto_keluarga',
        'kartu_kip',
        'kartu_pkh',
        'kartu_kks',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function homePhoto()
    {
        return $this->hasMany(HomePhoto::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($document) {
            // Loop untuk menghapus semua file terkait
            foreach ($document->getAttributes() as $key => $value) {
                if (in_array($key, [
                    'ktp', 'kk', 'ijazah', 'ijazah_skl', 'raport', 'photo_3x4',
                    'kk_legalisir', 'akte_legalisir', 'skhu_legalisir', 'raport_legalisir',
                    'surat_baik', 'surat_rekom_kades', 'surat_keterangan_baik',
                    'surat_penghasilan_ortu', 'surat_tidak_mampu', 'surat_pajak_bumi_bangunan',
                    'surat_tidak_pdam', 'token_listrik', 'skck', 'sertifikat_prestasi',
                    'foto_keluarga', 'kartu_kip', 'kartu_pkh', 'kartu_kks'
                ]) && $value) {
                    // Hapus file dari storage jika ada
                    Storage::disk('public')->delete($value);
                }
            }
        });
    }
}
