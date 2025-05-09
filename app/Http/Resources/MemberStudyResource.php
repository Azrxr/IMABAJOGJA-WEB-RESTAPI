<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberStudyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $document = $this->documents->first(); // 1 dokumen per member

        // Hitung kelengkapan dokumen
        $exclude = ['id', 'member_id', 'created_at', 'updated_at'];
        $attributes = $document ? collect($document->getAttributes())->except($exclude) : collect([]);
        $filled = $attributes->filter(fn($val) => !is_null($val))->count();
        $total = $attributes->count();
        $hasHomePhotos = $document && $document->homePhotos && $document->homePhotos->isNotEmpty();

        return [
            'id' => $this->id,
            'fullname' => $this->fullname,
            'angkatan' => $this->angkatan,
            'no_member' => $this->no_member,
            'member_type' => $this->member_type,
            'is_studyng' => $this->is_studyng,
            'berkas_progress' => "$filled / $total",
            'berkas_lengkap' => $filled === $total && $hasHomePhotos,
            'has_home_photos' => $hasHomePhotos,
            'status_kuliah' => $this->studyMembers->isNotEmpty() ? 'sudah' :
                               ($this->studyPlans->isNotEmpty() ? 'rencana' : 'belum'),
           
            'study_plans' => collect($this->studyPlans)->map(function ($plan) {
            return [
                'member_id' => $plan->member_id,
                'study_plan_id' => $plan->id,
                'university' => $plan->university ? $plan->university->name : null,
                'universityId' => $plan->university ? $plan->university->id : null,
                'program_study' => $plan->programStudy ? $plan->programStudy->name : null,
                'program_studyId' => $plan->programStudy ? $plan->programStudy->id : null,
                'status' => $plan->status,
            ];
            }),
            'study_members' => collect($this->studyMembers)->map(function ($member) {
            return [
                'member_id' => $member->member_id,
                'study_member_id' => $member->id,
                'university' => $member->university ? $member->university->name : null,
                'universityId' => $member->university ? $member->university->id : null,
                'faculty' => $member->faculty ? $member->faculty->name : null,
                'facultyId' => $member->faculty ? $member->faculty->id : null,
                'program_study' => $member->programStudy ? $member->programStudy->name : null,
                'program_studyId' => $member->programStudy ? $member->programStudy->id : null,
            ];
            }),
            'documents' => collect($this->documents)->map(function ($document) {
                return [
                    'id' => $document->id ?? null,
                    'member_id' => $document->member_id ?? null,
                    'ijazah_path' => $document->ijazah_path ?? null,
                    'ktp_path' => $document->ktp_path ?? null,
                    'kk_path' => $document->kk_path ?? null,
                    'ijazah_skl_path' => $document->ijazah_skl_path ?? null,
                    'raport_path' => $document->raport_path ?? null,
                    'photo_3x4_path' => $document->photo_3x4_path ?? null,
                    'kk_legalisir_path' => $document->kk_legalisir_path ?? null,
                    'akte_legalisir_path' => $document->akte_legalisir_path ?? null,
                    'skhu_legalisir_path' => $document->skhu_legalisir_path ?? null,
                    'raport_legalisir_path' => $document->raport_legalisir_path ?? null,
                    'surat_baik_path' => $document->surat_baik_path ?? null,
                    'surat_rekom_kades_path' => $document->surat_rekom_kades_path ?? null,
                    'surat_keterangan_baik_path' => $document->surat_keterangan_baik_path ?? null,
                    'surat_penghasilan_ortu_path' => $document->surat_penghasilan_ortu_path ?? null,
                    'surat_tidak_mampu_path' => $document->surat_tidak_mampu_path ?? null,
                    'surat_pajak_bumi_bangunan_path' => $document->surat_pajak_bumi_bangunan_path ?? null,
                    'surat_tidak_pdam_path' => $document->surat_tidak_pdam_path ?? null,
                    'token_listrik_path' => $document->token_listrik_path ?? null,
                    'skck_path' => $document->skck_path ?? null,
                    'sertifikat_prestasi_path' => $document->sertifikat_prestasi_path ?? null,
                    'foto_keluarga_path' => $document->foto_keluarga_path ?? null,
                    'kartu_kip_path' => $document->kartu_kip_path ?? null,
                    'kartu_pkh_path' => $document->kartu_pkh_path ?? null,
                    'kartu_kks_path' => $document->kartu_kks_path ?? null,
                    'created_at' => $document->created_at ?? null,
                    'updated_at' => $document->updated_at ?? null,
                    'documents_url' => [
                        'ijazah_path' => $document->ijazah_url ?? null,
                        'ktp_path' => $document->ktp_url ?? null,
                        'kk_path' => $document->kk_url ?? null,
                        'ijazah_skl_path' => $document->ijazah_skl_url ?? null,
                        'raport_path' => $document->raport_url ?? null,
                        'photo_3x4_path' => $document->photo_3x4_url ?? null,
                        'kk_legalisir_path' => $document->kk_legalisir_url ?? null,
                        'akte_legalisir_path' => $document->akte_legalisir_url ?? null,
                        'skhu_legalisir_path' => $document->skhu_legalisir_url ?? null,
                        'raport_legalisir_path' => $document->raport_legalisir_url ?? null,
                        'surat_baik_path' => $document->surat_baik_url ?? null,
                        'surat_rekom_kades_path' => $document->surat_rekom_kades_url ?? null,
                        'surat_keterangan_baik_path' => $document->surat_keterangan_baik_url ?? null,
                        'surat_penghasilan_ortu_path' => $document->surat_penghasilan_ortu_url ?? null,
                        'surat_tidak_mampu_path' => $document->surat_tidak_mampu_url ?? null,
                        'surat_pajak_bumi_bangunan_path' => $document->surat_pajak_bumi_bangunan_url ?? null,
                        'surat_tidak_pdam_path' => $document->surat_tidak_pdam_url ?? null,
                        'token_listrik_path' => $document->token_listrik_url ?? null,
                        'skck_path' => $document->skck_url ?? null,
                        'sertifikat_prestasi_path' => $document->sertifikat_prestasi_url ?? null,
                        'foto_keluarga_path' => $document->foto_keluarga_url ?? null,
                        'kartu_kip_path' => $document->kartu_kip_url ?? null,
                        'kartu_pkh_path' => $document->kartu_pkh_url ?? null,
                        'kartu_kks_path' => $document->kartu_kks_url ?? null,
                    ],
                    'home_photo' => collect($document->homePhotos)->map(function ($photo) {
                        return [
                            'id' => $photo->id ?? null,
                            'document_id' => $photo->document_id ?? null,
                            'photo_title' => $photo->photo_title ?? null,
                            'photo_img_path' => $photo->photo_img_path ?? null,
                            'created_at' => $photo->created_at ?? null,
                            'updated_at' => $photo->updated_at ?? null,
                            'photo_img_url' => $photo->photo_img_url ?? null,
                        ];
                    }),
                ];
            }),
        ];
    }
}
