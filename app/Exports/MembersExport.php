<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembersExport implements FromCollection, WithMapping, WithHeadings, WithStyles, WithColumnWidths
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
            $member->phone_number,
            $member->province_id,
            $member->regency_id,
            $member->district_id,
            $member->full_address,
            $member->agama,
            $member->member_type,
            $member->nisn,
            $member->tempat,
            $member->tanggal_lahir,
            $member->gender,
            $member->kode_pos,
            $member->scholl_origin,
            $member->tahun_lulus,
            $member->is_studyng,

            // study plan
            optional($member->studyMember)->university_id,
            optional($member->studyMember)->faculty_id,
            optional($member->studyMember)->program_study_id,

            // user
            optional($member->user)->username,
            optional($member->user)->email,
            '', // password tidak diexport demi keamanan (biarkan kosong)
        ];
    }


    public function headings(): array
    {
        return [
            'No Member',
            'Nama Lengkap',
            'Angkatan',
            'Nomor HP',
            'ID Provinsi',
            'ID Kabupaten',
            'ID Kecamatan',
            'Alamat Lengkap',
            'Agama',
            'Tipe Member',
            'NISN',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Kode Pos',
            'Asal Sekolah',
            'Tahun Lulus',
            'Sedang Kuliah',
            'ID Universitas',
            'ID Fakultas',
            'ID Program Studi',
            'Username',
            'Email',
            'Password',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header styling
        $sheet->getStyle('A1:X1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => 'center'],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => 'D0E3FA']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => 'thin']
            ]
        ]);

        // Style data rows
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $color = $row % 2 === 0 ? 'FFFFFF' : 'F2F2F2'; // white or light gray
            $sheet->getStyle("A{$row}:X{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => $color]
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => 'thin']
                ]
            ]);
        }

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // No Member
            'B' => 25, // Nama Lengkap
            'C' => 10, // Angkatan
            'D' => 15, // Nomor HP
            'E' => 10,
            'F' => 10,
            'G' => 10,
            'H' => 40, // Alamat
            'I' => 12, // Agama
            'J' => 15, // Tipe Member
            'K' => 20,
            'L' => 15,
            'M' => 15,
            'N' => 15,
            'O' => 12,
            'P' => 25,
            'Q' => 15,
            'R' => 12,
            'S' => 15,
            'T' => 15,
            'U' => 18,
            'V' => 20,
            'W' => 30,
            'X' => 15,
        ];
    }
}
