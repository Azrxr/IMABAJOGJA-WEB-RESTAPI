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
            'gender' => 'male',
            'member_type' => 'istimewa',
            'scholl_origin' => 'SMA Negeri 1 Yogyakarta',
            'tahun_lulus' => 2018,
        ]);

        $doc = Document::create([
            'member_id' => $member_data->id,
            'ijazah' => 'path/to/ijazah.jpg',
            'ktp' => 'path/to/ktp.jpg',
            'kk' => 'path/to/kk.jpg',
            'ijazah_skl' => 'path/to/ijazah_skl.jpg',
            'raport' => 'path/to/raport.jpg',
            'photo_3x4' => 'path/to/photo_3x4.jpg',
            'kk_legalisir' => 'path/to/kk_legalisir.jpg',
            'akte_legalisir' => 'path/to/akte_legalisir.jpg',
            'skhu_legalisir' => 'path/to/skhu_legalisir.jpg',
            'raport_legalisir' => 'path/to/raport_legalisir.jpg',
            'surat_baik' => 'path/to/surat_baik.jpg',
            'surat_rekom_kades' => 'path/to/surat_rekom_kades.jpg',
            'surat_keterangan_baik' => 'path/to/surat_keterangan_baik.jpg',
            'surat_penghasilan_ortu' => 'path/to/surat_penghasilan_ortu.jpg',
            'surat_tidak_mampu' => 'path/to/surat_tidak_mampu.jpg',
            'surat_pajak_bumi_bangunan' => 'path/to/surat_pajak_bumi_bangunan.jpg',
            'surat_tidak_pdam' => 'path/to/surat_tidak_pdam.jpg',
            'token_listrik' => 'path/to/token_listrik.jpg',
            'skck' => 'path/to/skck.jpg',
            'sertifikat_prestasi' => 'path/to/sertifikat_prestasi.jpg',
            'foto_keluarga' => 'path/to/foto_keluarga.jpg',
            'kartu_kip' => 'path/to/kartu_kip.jpg',
            'kartu_pkh' => 'path/to/kartu_pkh.jpg',
            'kartu_kks' => 'path/to/kartu_kks.jpg',
        ]);

        HomePhoto::create([
            'document_id' => $doc->id,
            'photo_title' => 'Home Photo',
            'photo_img' => 'path/to/photo.jpg',
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
            'gender' => 'female',
            'member_type' => 'camaba',
            'scholl_origin' => 'SMA Negeri 3 Bandung',
            'tahun_lulus' => 2017,
        ]);

    }
}
