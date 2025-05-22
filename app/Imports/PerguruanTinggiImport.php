<?php

namespace App\Imports;

use App\Models\Faculty;
use App\Models\University;
use Illuminate\Support\Str;
use App\Models\ProgramStudy;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PerguruanTinggiImport implements ToCollection
{
    /**
     * @param Collection $collection
     */

    public $report = [
        'success' => 0,
        'fail' => 0,
        'activities' => [] // log aktivitas per baris
    ];
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // skip header

            try {
                $activity = [];

                $univName = trim(Str::lower($row[1]));
                $univCode = $row[0] ?? null;

                $university = University::whereRaw('LOWER(name) = ?', [$univName])->first();

                if (!$university) {
                    $university = University::create([
                        'id' => $univCode,
                        'kd_university' => $univCode,
                        'name' => $row[1]
                    ]);
                    $activity[] = "create:university";
                } else {
                    $university->update(['name' => $row[1]]);
                    $activity[] = "update:university";
                }

                // Faculty
                $faculty = null;
                if (!empty($row[6])) { // Nama Fakultas
                    $facultyName = trim(Str::lower($row[6]));
                    $facultyCode = $row[5] ?? null; // Kode Fakultas

                    $faculty = Faculty::where('university_id', $university->id)
                        ->whereRaw('LOWER(name) = ?', [$facultyName])
                        ->first();

                    if (!$faculty) {
                        $faculty = Faculty::create([
                            'kd_faculty' => $facultyCode,
                            'university_id' => $university->id,
                            'name' => $row[6]
                        ]);
                        $activity[] = "create:faculty";
                    } else {
                        $faculty->update(['name' => $row[6]]);
                        $activity[] = "update:faculty";
                    }
                }

                // Program Study
                if (!empty($row[4])) {
                    $progName = trim(Str::lower($row[3]));
                    $progCode = $row[2] ?? null;
                    $jenjang = $row[4] ?? null;

                    $program = ProgramStudy::where('university_id', $university->id)
                        ->whereRaw('LOWER(name) = ?', [$progName])
                        ->first();

                    if (!$program) {
                        ProgramStudy::create([
                            'kd_program_study' => $progCode,
                            'university_id' => $university->id ?? $univCode,  // pastikan ini diisi
                            'faculty_id' => $faculty->id ?? null,
                            'name' => $row[3],
                            'jenjang' => $jenjang ?? null,
                        ]);

                        $activity[] = "create:program_study";
                    } else {
                        $program->update([
                            'kd_program_study' => $progCode,
                            'university_id' => $university->id ?? $univCode,
                            'faculty_id' => $faculty->id ?? $program->faculty_id,
                            'name' => $row[3],
                            'jenjang' => $jenjang ?? $program->jenjang,
                        ]);
                        $activity[] = "update:program_study";
                    }
                }

                $this->report['success']++;
                $this->report['activities'][] = [
                    'row' => $index + 1,
                    'actions' => $activity
                ];
            } catch (\Throwable $e) {
                $this->report['fail']++;
                $this->report['activities'][] = [
                    'row' => $index + 1,
                    'error' => $e->getMessage()
                ];
            }
        }
    }
}
