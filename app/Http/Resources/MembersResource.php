<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MembersResource extends JsonResource
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

            'berkas_progress' => "$filled / $total",
            'berkas_lengkap' => $filled === $total && $hasHomePhotos,
            'has_home_photos' => $hasHomePhotos,
            'status_kuliah' => $this->studyMembers->isNotEmpty() ? 'sudah' :
                               ($this->studyPlans->isNotEmpty() ? 'rencana' : 'belum'),

            'id' => $this->id,
            'user_id' => $this->user_id,
            'fullname' => $this->fullname,
            'phone_number' => $this->phone_number,
            'member_type' => $this->member_type,
            'profile_img_url' => $this->profile_img_url,
            'angkatan' => $this->angkatan,
            'no_member' => $this->no_member,

            'full_address' => $this->full_address,
            'province' => $this->province ? $this->province->name : null,
            'provinceId' => $this->province ? $this->province->id : null,
            'regency' => $this->regency ? $this->regency->name : null,
            'regencyId' => $this->regency ? $this->regency->id : null,
            'district' => $this->district ? $this->district->name : null,
            'districtId' => $this->district ? $this->district->id : null,
            'kode_pos' => $this->kode_pos,

            'agama' => $this->agama,
            'nisn' => $this->nisn,
            'tempat' => $this->tempat,
            'tanggal_lahir' => $this->tanggal_lahir,
            'gender' => $this->gender,

            'scholl_origin' => $this->scholl_origin,
            'tahun_lulus' => $this->tahun_lulus,
            'is_studyng' => $this->is_studyng,

            'study_plans' => $this->studyPlans->map(function ($plan) {
            return [
                'member_id' => $plan->member_id ? $plan->member_id : null,
                'study_plan_id' => $plan->id ? $plan->id : null,
                'university' => $plan->university ? $plan->university->name : null,
                'universityId' => $plan->university ? $plan->university->id : null,
                'program_study' => $plan->programStudy ? $plan->programStudy->name : null,
                'program_studyId' => $plan->programStudy ? $plan->programStudy->id : null,
                'status' => $plan->status,
            ];
            }),
            'study_members' => $this->studyMembers->map(function ($member) {
            return [
                'member_id' => $member->member_id ? $member->member_id : null,
                'study_member_id' => $member->id ? $member->id : null,
                'university' => $member->university ? $member->university->name : null,
                'universityId' => $member->university ? $member->university->id : null,
                'faculty' => $member->faculty ? $member->faculty->name : null,
                'facultyId' => $member->faculty ? $member->faculty->id : null,
                'program_study' => $member->programStudy ? $member->programStudy->name : null,
                'program_studyId' => $member->programStudy ? $member->programStudy->id : null,
            ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'documents' => $this->documents,
        ];
    }
}
