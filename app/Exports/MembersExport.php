<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MembersExport implements FromCollection, WithMapping, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Member::with(['studyPlans.university', 'studyPlans.programStudy']);

        if (!empty($this->filters['angkatan'])) {
            $query->where('angkatan', $this->filters['angkatan']);
        }

        if (!empty($this->filters['member_type'])) {
            $query->where('member_type', $this->filters['member_type']);
        }

        return $query->get();
    }

    public function map($member): array
    {
        return [
            $member->no_member,
            $member->fullname,
            $member->angkatan,
            $member->member_type,
            $member->phone_number,
            $member->studyPlans->map(fn($plan) =>
                $plan->university->name . ' - ' . $plan->programStudy->name
            )->implode('; ')
        ];
    }

    public function headings(): array
    {
        return [
            'No Member',
            'Fullname',
            'Angkatan',
            'Member Type',
            'Phone Number',
            'Study Plans',
        ];
    }
}
