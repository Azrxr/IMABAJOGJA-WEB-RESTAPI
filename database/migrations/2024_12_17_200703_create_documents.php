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
            $table->string('ijazah')->nullable()->comment('path to IJAZAH');
            $table->string('ktp')->nullable()->comment('path to KTP document');
            $table->string('kk')->nullable()->comment('path to KK document');
            $table->string('ijazah_skl')->nullable()->comment('path to IJAZAH or SKL');
            $table->string('raport')->nullable()->comment('path to RAPORT');
            $table->string('photo_3x4')->nullable()->comment('path to 3x4 photo');
            //berkas sipil
            $table->string('kk_legalisir')->nullable()->comment('path to legalized KK');
            $table->string('akte_legalisir')->nullable()->comment('path to legalized Birth Certificate');
            //berkas sekolah
            $table->string('skhu_legalisir')->nullable()->comment('path to legalized SKHU');
            $table->string('raport_legalisir')->nullable()->comment('path to legalized RAPORT');
            $table->string('surat_baik')->nullable()->comment('path to Good Conduct Letter');
            $table->string('surat_rekom_kades')->nullable()->comment('path to Village Recommendation Letter');
            $table->string('surat_keterangan_baik')->nullable()->comment('path to Good Behavior Letter');
            $table->string('surat_penghasilan_ortu')->nullable()->comment('path to Parent Income Letter');
            $table->string('surat_tidak_mampu')->nullable()->comment('path to Certificate of Poverty');
            $table->string('surat_pajak_bumi_bangunan')->nullable()->comment('path to Land Tax Letter');
            $table->string('surat_tidak_pdam')->nullable()->comment('path to Non-PDAM Subscription Letter');
            $table->string('token_listrik')->nullable()->comment('path to Electricity Token');
            //berkas kapolsek
            $table->string('skck')->nullable()->comment('path to Police Clearance Certificate (SKCK)');
            //berkas lain2
            $table->string('sertifikat_prestasi')->nullable()->comment('path to Achievement Certificate');
            $table->string('foto_keluarga')->nullable()->comment('path to Family Photo');
            $table->string('kartu_kip')->nullable()->comment('path to KIP Card (if available)');
            $table->string('kartu_pkh')->nullable()->comment('path to PKH Card (if available)');
            $table->string('kartu_kks')->nullable()->comment('path to KKS Card (if available)');

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
