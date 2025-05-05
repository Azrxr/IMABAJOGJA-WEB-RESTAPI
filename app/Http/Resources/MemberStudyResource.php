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
            'documents' => $this->documents,
        ];
    }
}
