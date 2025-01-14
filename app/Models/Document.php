<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'member_id',
        'home_photo_id',
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
        return $this->belongsTo(HomePhoto::class);
    }
}
