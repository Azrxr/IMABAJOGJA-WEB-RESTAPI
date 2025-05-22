<?php

namespace App\Http\Controllers;

use App\Imports\PerguruanTinggiImport;
use App\Models\University;
use App\Models\ProgramStudy;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class StudyController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->input('search');
        $jenjang = $request->input('jenjang');

        $query = ProgramStudy::with([
            'university',
            'faculty'
        ]);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhereHas('university', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('faculty', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
        }
        if ($jenjang) {
            $query->where('jenjang', $jenjang);
        }
        $data = $query->get()->map(function ($prog) {
            return [
                'university' => $prog->university ? $prog->university->name : null,
                'universityId' => $prog->university ? $prog->university->id : null,
                'faculty' => $prog->faculty ? $prog->faculty->name : null,
                'facultyId' => $prog->faculty ? $prog->faculty->id : null,
                'program_study' => $prog->name,
                'program_studyId' => $prog->id,
                'jenjang' => $prog->jenjang,
            ];
        });
        // $data = $query->get();

        return response()->json([
            'error' => false,
            'message' => 'Data loaded successfully',
            'data' => $data
        ]);
    }


    public function importUniversityStructure(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            $import = new PerguruanTinggiImport;
            Excel::import($import, $request->file('file'));

            return response()->json([
                'error' => false,
                'message' => 'Proses impor selesai.',
                'report' => $import->report
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal mengimpor data',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
