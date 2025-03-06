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
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'fullname' => $this->fullname,
            'phone_number' => $this->phone_number,
            'member_type' => $this->member_type,
            'profile_img_url' => $this->profile_img_url,

            'full_address' => $this->full_address,
            'province' => $this->province ? $this->province->name : null,
            'regency' => $this->regency ? $this->regency->name : null,
            'district' => $this->district ? $this->district->name : null,
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
                'university' => $plan->university ? $plan->university->name : null,
                'program_study' => $plan->programStudy ? $plan->programStudy->name : null,
                'status' => $plan->status,
            ];
            }),
            'study_members' => $this->studyMembers->map(function ($member) {
            return [
                'university' => $member->university ? $member->university->name : null,
                'faculty' => $member->faculty ? $member->faculty->name : null,
                'program_study' => $member->programStudy ? $member->programStudy->name : null,
            ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
