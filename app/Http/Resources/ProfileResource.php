<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            'banned' => $this->banned,
            'ban_reason' => $this->ban_reason,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->member->id ?? null,
            'user_id' => $this->member->user_id ?? null,
            'no_member' => $this->member->no_member ?? null,
            'angkatan' => $this->member->angkatan ?? null,
            'fullname' => $this->member->fullname ?? null,
            'phone_number' => $this->member->phone_number ?? null,
            'province' => $this->member->province->name ?? null,
            'province_id' => $this->member->province_id ?? null,
            'regency' => $this->member->regency->name ?? null,
            'regency_id' => $this->member->regency_id ?? null,
            'district' => $this->member->district->name ?? null,
            'district_id' => $this->member->district_id ?? null,
            'full_address' => $this->member->full_address ?? null,
            'agama' => $this->member->agama ?? null,
            'nisn' => $this->member->nisn ?? null,
            'tempat' => $this->member->tempat ?? null,
            'tanggal_lahir' => $this->member->tanggal_lahir ?? null,
            'gender' => $this->member->gender ?? null,
            'kode_pos' => $this->member->kode_pos ?? null,
            'member_type' => $this->member->member_type ?? null,
            'scholl_origin' => $this->member->scholl_origin ?? null,
            'tahun_lulus' => $this->member->tahun_lulus ?? null,
            'is_studyng' => $this->member->is_studyng ?? null,
            'profile_img_url' => $this->member->profile_img_url ?? null,
        ];
    }
}
