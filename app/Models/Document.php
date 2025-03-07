<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'member_id',
        'ijazah_path',
        'ktp_path',
        'kk_path',
        'ijazah_skl_path',
        'raport_path',
        'photo_3x4_path',
        //berkas sipil
        'kk_legalisir_path',
        'akte_legalisir_path',
        //berkas sekolah
        'skhu_legalisir_path',
        'raport_legalisir_path',
        'surat_baik_path',
        'surat_rekom_kades_path',
        'surat_keterangan_baik_path',
        'surat_penghasilan_ortu_path',
        'surat_tidak_mampu_path',
        'surat_pajak_bumi_bangunan_path',
        'surat_tidak_pdam_path',
        'token_listrik_path',
        //berkas kapolsek
        'skck_path',
        //berkas lain2
        'sertifikat_prestasi_path',
        'foto_keluarga_path',
        'kartu_kip_path',
        'kartu_pkh_path',
        'kartu_kks_path',
    ];

    protected $appends = ['documents_url'];
    public function getDocumentsUrlAttribute()
    {
        $filePaths = [
            'ijazah_path',
            'ktp_path',
            'kk_path',
            'ijazah_skl_path',
            'raport_path',
            'photo_3x4_path',
            'kk_legalisir_path',
            'akte_legalisir_path',
            'skhu_legalisir_path',
            'raport_legalisir_path',
            'surat_baik_path',
            'surat_rekom_kades_path',
            'surat_keterangan_baik_path',
            'surat_penghasilan_ortu_path',
            'surat_tidak_mampu_path',
            'surat_pajak_bumi_bangunan_path',
            'surat_tidak_pdam_path',
            'token_listrik_path',
            'skck_path',
            'sertifikat_prestasi_path',
            'foto_keluarga_path',
            'kartu_kip_path',
            'kartu_pkh_path',
            'kartu_kks_path',
        ];

        $documentUrls = [];

        foreach ($filePaths as $path) {
            $documentUrls[$path] = $this->$path ? url('storage/' . $this->$path) : null;
        }

        return $documentUrls;
    }

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
                    'ktp_path',
                    'kk_path',
                    'ijazah_path',
                    'ijazah_skl_path',
                    'raport_path',
                    'photo_3x4_path',
                    'kk_legalisir_path',
                    'akte_legalisir_path',
                    'skhu_legalisir_path',
                    'raport_legalisir_path',
                    'surat_baik_path',
                    'surat_rekom_kades_path',
                    'surat_keterangan_baik_path',
                    'surat_penghasilan_ortu_path',
                    'surat_tidak_mampu_path',
                    'surat_pajak_bumi_bangunan_path',
                    'surat_tidak_pdam_path',
                    'token_listrik_path',
                    'skck_path',
                    'sertifikat_prestasi_path',
                    'foto_keluarga_path',
                    'kartu_kip_path',
                    'kartu_pkh_path',
                    'kartu_kks_path'
                ]) && $value) {
                    // Hapus file dari storage jika ada
                    Storage::disk('public')->delete($value);
                }
            }
        });
    }
}
