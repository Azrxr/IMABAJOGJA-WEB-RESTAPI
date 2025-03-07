<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id()->unique();
            $table->unsignedBigInteger('member_id')->unique();
            $table->string('ijazah_path')->nullable()->comment('path to IJAZAH');
            $table->string('ktp_path')->nullable()->comment('path to KTP document');
            $table->string('kk_path')->nullable()->comment('path to KK document');
            $table->string('ijazah_skl_path')->nullable()->comment('path to IJAZAH or SKL');
            $table->string('raport_path')->nullable()->comment('path to RAPORT');
            $table->string('photo_3x4_path')->nullable()->comment('path to 3x4 photo');
            //berkas sipil
            $table->string('kk_legalisir_path')->nullable()->comment('path to legalized KK');
            $table->string('akte_legalisir_path')->nullable()->comment('path to legalized Birth Certificate');
            //berkas sekolah
            $table->string('skhu_legalisir_path')->nullable()->comment('path to legalized SKHU');
            $table->string('raport_legalisir_path')->nullable()->comment('path to legalized RAPORT');
            $table->string('surat_baik_path')->nullable()->comment('path to Good Conduct Letter');
            $table->string('surat_rekom_kades_path')->nullable()->comment('path to Village Recommendation Letter');
            $table->string('surat_keterangan_baik_path')->nullable()->comment('path to Good Behavior Letter');
            $table->string('surat_penghasilan_ortu_path')->nullable()->comment('path to Parent Income Letter');
            $table->string('surat_tidak_mampu_path')->nullable()->comment('path to Certificate of Poverty');
            $table->string('surat_pajak_bumi_bangunan_path')->nullable()->comment('path to Land Tax Letter');
            $table->string('surat_tidak_pdam_path')->nullable()->comment('path to Non-PDAM Subscription Letter');
            $table->string('token_listrik_path')->nullable()->comment('path to Electricity Token');
            //berkas kapolsek
            $table->string('skck_path')->nullable()->comment('path to Police Clearance Certificate (SKCK)');
            //berkas lain2
            $table->string('sertifikat_prestasi_path')->nullable()->comment('path to Achievement Certificate');
            $table->string('foto_keluarga_path')->nullable()->comment('path to Family Photo');
            $table->string('kartu_kip_path')->nullable()->comment('path to KIP Card (if available)');
            $table->string('kartu_pkh_path')->nullable()->comment('path to PKH Card (if available)');
            $table->string('kartu_kks_path')->nullable()->comment('path to KKS Card (if available)');

            $table->foreign('member_id')->references('id')->on('members');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
