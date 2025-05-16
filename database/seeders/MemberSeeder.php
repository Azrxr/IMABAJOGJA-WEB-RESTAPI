<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Member;
use App\Models\Document;
use App\Models\HomePhoto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $member = User::create([
            'username' => 'member',
            'email' => 'member@example.com',
            'role' => 'member',
            'password' => Hash::make('password'),
        ]);
        $member_data = Member::create([
            'id' => 1,
            'user_id' => $member->id,
            'fullname' => 'Member User',
            'phone_number' => 1234567890,
            'profile_img_path' => 'path/to/image.jpg',
            'province_id' => 10,
            'regency_id' => 3,
            'district_id' => 3,
            'full_address' => 'Jl. Malioboro No. 1',
            'kode_pos' => '55213',
            'agama' => 'Islam',
            'nisn' => '1234567890',
            'tempat' => 'Yogyakarta',
            'tanggal_lahir' => '2000-01-01',
            'gender' => 'laki-laki',
            'member_type' => 'istimewa',
            'scholl_origin' => 'SMA Negeri 1 Yogyakarta',
            'tahun_lulus' => 2018,
        ]);

        $doc = Document::create([
            'member_id' => $member_data->id,
            'ijazah_path' => 'path/to/ijazah.jpg',
            'ktp_path' => 'path/to/ktp.jpg',
            'kk_path' => 'path/to/kk.jpg',
            'ijazah_skl_path' => 'path/to/ijazah_skl.jpg',
            'raport_path' => 'path/to/raport.jpg',
            'photo_3x4_path' => 'path/to/photo_3x4.jpg',
            'kk_legalisir_path' => 'path/to/kk_legalisir.jpg',
            'akte_legalisir_path' => 'path/to/akte_legalisir.jpg',
            'skhu_legalisir_path' => 'path/to/skhu_legalisir.jpg',
            'raport_legalisir_path' => 'path/to/raport_legalisir.jpg',
            'surat_baik_path' => 'path/to/surat_baik.jpg',
            'surat_rekom_kades_path' => 'path/to/surat_rekom_kades.jpg',
            'surat_keterangan_baik_path' => 'path/to/surat_keterangan_baik.jpg',
            'surat_penghasilan_ortu_path' => 'path/to/surat_penghasilan_ortu.jpg',
            'surat_tidak_mampu_path' => 'path/to/surat_tidak_mampu.jpg',
            'surat_pajak_bumi_bangunan_path' => 'path/to/surat_pajak_bumi_bangunan.jpg',
            'surat_tidak_pdam_path' => 'path/to/surat_tidak_pdam.jpg',
            'token_listrik_path' => 'path/to/token_listrik.jpg',
            'skck_path' => 'path/to/skck.jpg',
            'sertifikat_prestasi_path' => 'path/to/sertifikat_prestasi.jpg',
            'foto_keluarga_path' => 'path/to/foto_keluarga.jpg',
            'kartu_kip_path' => 'path/to/kartu_kip.jpg',
            'kartu_pkh_path' => 'path/to/kartu_pkh.jpg',
            'kartu_kks_path' => 'path/to/kartu_kks.jpg',
        ]);

        HomePhoto::create([
            'document_id' => $doc->id,
            'photo_title' => 'Home Photo',
            'photo_img_path' => 'path/to/photo.jpg',
        ]);

        $banned = User::create([
            'username' => 'banned',
            'email' => 'banned@example.com',
            'role' => 'member',
            'banned' => true,
            'ban_reason' => 'spam',
            'password' => Hash::make('password'),
        ]);

        Member::create([
            'id' => 2,
            'user_id' => $banned->id,
            'fullname' => 'banned',
            'phone_number' => 98765432,
            'profile_img_path' => 'path/to/image2.jpg',
            'province_id' => 10,
            'regency_id' => 3,
            'district_id' => 4,
            'full_address' => 'Jl. Asia Afrika No. 2',
            'kode_pos' => '40111',
            'agama' => 'Kristen',
            'nisn' => '0987654321',
            'tempat' => 'Bandung',
            'tanggal_lahir' => '1999-02-02',
            'gender' => 'perempuan',
            'member_type' => 'camaba',
            'scholl_origin' => 'SMA Negeri 3 Bandung',
            'tahun_lulus' => 2017,
        ]);

    }
}
