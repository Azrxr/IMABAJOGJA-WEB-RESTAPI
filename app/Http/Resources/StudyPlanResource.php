<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'member_id' => $this->member_id,
            'perguruan_tinggi' => $this->university->name,[
                'id' => $this->program_study_id,
                'name' => $this->programStudy->name,
                'jenjang' => $this->programStudy->jenjang
            ]
        ];
    }
}
