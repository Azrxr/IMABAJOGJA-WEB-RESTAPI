<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Member;
use App\Models\StudyMember;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class MembersImport implements ToCollection
{
    /**
     * @param Collection $collection
     */

    public $results = [
        'success' => [],
        'errors' => []
    ];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Skip header

            try {
                $no_member = $row[0];
                $memberData = [
                    'no_member' => $no_member,
                    'fullname' => $row[1],
                    'angkatan' => $row[2],
                    'phone_number' => $row[3],
                    'province_id' => $row[4],
                    'regency_id' => $row[5],
                    'district_id' => $row[6],
                    'full_address' => $row[7],
                    'agama' => $row[8],
                    'member_type' => $row[9],
                    'nisn' => $row[10],
                    'tempat' => $row[11],
                    'tanggal_lahir' => is_numeric($row[12])
                        ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[12])->format('Y-m-d')
                        : \Carbon\Carbon::parse($row[12])->format('Y-m-d'),
                    'gender' => $row[13],
                    'kode_pos' => $row[14],
                    'scholl_origin' => $row[15],
                    'tahun_lulus' => $row[16],
                    'is_studyng' => isset($row[17]) ? $row[17] : false,

                ];

                $studyData = [
                    'university_id' => $row[18] ?? null,
                    'faculty_id' => $row[19] ?? null,
                    'program_study_id' => $row[20] ?? null,
                ];

                $userData = [
                    'username' => $row[21] ?? null,
                    'email' => $row[22] ?? null,
                    'password' => isset($row[23]) ? Hash::make($row[23]) : null,
                ];

                $existingMember = Member::where('no_member', $no_member)->first();

                if ($existingMember) {
                    $existingMember->update($memberData);

                    if ($existingMember->user) {
                        $existingMember->user->update(array_filter($userData));
                    }

                    if ($studyData['university_id'] && $studyData['program_study_id']) {
                        StudyMember::updateOrCreate(
                            ['member_id' => $existingMember->id],
                            $studyData
                        );
                    }

                    $this->results['success'][] = [
                        'no_member' => $no_member,
                        'action' => 'updated'
                    ];
                } else {
                    $user = User::create([
                        'username' => $userData['username'] ?? $no_member,
                        'email' => $userData['email'] ?? "{$no_member}@example.com",
                        'password' => $userData['password'] ?? Hash::make("Pass{$no_member}"),
                        'role' => 'member'
                    ]);

                    $newMember = Member::create(array_merge($memberData, [
                        'user_id' => $user->id
                    ]));

                    if ($studyData['university_id'] && $studyData['program_study_id']) {
                        StudyMember::create(array_merge($studyData, [
                            'member_id' => $newMember->id
                        ]));
                    }

                    $this->results['success'][] = [
                        'no_member' => $no_member,
                        'action' => 'created'
                    ];
                }
            } catch (\Exception $e) {
                $this->results['errors'][] = [
                    'row' => $index + 1,
                    'no_member' => $row[0] ?? 'unknown',
                    'error' => $e->getMessage()
                ];
            }
        }
    }
}
