<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Models\StudyMember;
use App\Models\StudyPlane;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyPlaneController extends Controller
{
    public function getAllStudyPlans()
{
    // Ambil semua member yang memiliki study plans
    $members = Member::whereHas('studyPlans')
        ->with([
            'studyPlans.university',
            'studyPlans.programStudy'
        ])
        ->get();

    return response()->json([
        'error' => false,
        'message' => 'Get all study plans success!',
        'data' => $members
    ], 200);
}
    public function index(Request $request)
    {
        $memberId = User::with('member')
            ->findOrFail(Auth::id());


        $studyPlans = StudyPlane::where('member_id', $memberId->id)
            ->with('university', 'programStudy')
            ->get();

        $groupedData = [];

        foreach ($studyPlans as $plan) {
            $universityName = $plan->university->name;

            if (!isset($groupedData[$universityName])) {
                $groupedData[$universityName] = [
                    'university_id' => $plan->university_id,
                    'programs' => []
                ];
            }

            $groupedData[$universityName]['programs'][] = [
                'id' => $plan->program_study_id,
                'name' => $plan->programStudy->name,
                'jenjang' => $plan->programStudy->jenjang
            ];
        }


        return response()->json([
            'error' => false,
            'message' => 'Get study plans success!',
            'data' => $studyPlans
        ]);
    }
    public function studyPlaneAdd(Request $request)
    {
        $memberId = User::with('member')
            ->findOrFail(Auth::id())
            ->id;

        $validate = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'program_study_id' => 'required|exists:program_studies,id',
        ]);

        // Cek jumlah universitas yang sudah dimasukkan oleh member
        $universityCount = StudyPlane::where('member_id', $memberId)
            ->distinct()
            ->count('university_id');

        if ($universityCount >= 2 && !StudyPlane::where([
            ['member_id', $memberId],
            ['university_id', $validate['university_id']]
        ])->exists()) {
            return response()->json([
                'error' => true,
                'message' => 'Anda hanya bisa memilih maksimal 2 universitas.'
            ], 400);
        }

        // Cek jumlah program studi dalam universitas yang sama
        $programStudyCount = StudyPlane::where([
            ['member_id', $memberId],
            ['university_id', $validate['university_id']]
        ])->count();

        if ($programStudyCount >= 2) {
            return response()->json([
                'error' => true,
                'message' => 'Anda hanya bisa memilih maksimal 2 program studi per universitas.'
            ], 400);
        }

        // Simpan study plan baru
        $studyPlan = StudyPlane::create([
            'member_id' => $memberId,
            'university_id' => $validate['university_id'],
            'program_study_id' => $validate['program_study_id'],
            'status' => 'pending',
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Study plan added successfully!',
            'data' => $studyPlan
        ], 201);
    }

    public function studyPlaneUpdate(Request $request, $id)
    {
        // Validasi input
        $validate = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'program_study_id' => 'required|exists:program_studies,id',
        ]);

        // Ambil study plan berdasarkan ID
        $studyPlan = StudyPlane::findOrFail($id);

        // Cek jumlah universitas yang sudah dimasukkan oleh member
        $universityCount = StudyPlane::where('member_id', $studyPlan->member_id)
            ->distinct()
            ->count('university_id');

        if ($universityCount >= 2 && !StudyPlane::where([
            ['member_id', $studyPlan->member_id],
            ['university_id', $validate['university_id']]
        ])->exists()) {
            return response()->json([
                'error' => true,
                'message' => 'Anda hanya bisa memilih maksimal 2 universitas.'
            ], 400);
        }

        // Cek jumlah program studi dalam universitas yang sama
        $programStudyCount = StudyPlane::where([
            ['member_id', $studyPlan->member_id],
            ['university_id', $validate['university_id']]
        ])->count();

        if ($programStudyCount >= 2 && $studyPlan->university_id !== $validate['university_id']) {
            return response()->json([
                'error' => true,
                'message' => 'Anda hanya bisa memilih maksimal 2 program studi per universitas.'
            ], 400);
        }

        // Update study plan
        $studyPlan->update([
            'university_id' => $validate['university_id'],
            'program_study_id' => $validate['program_study_id'],
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Study plan updated successfully!',
            'data' => $studyPlan
        ], 200);
    }

    public function studyPlaneDelete($id)
    {
        // Cari study plan berdasarkan ID
        $studyPlan = StudyPlane::findOrFail($id);

        // Hapus dari database
        $studyPlan->delete();

        return response()->json([
            'error' => false,
            'message' => 'Study plan deleted successfully!'
        ], 200);
    }

    public function adminStudyPlaneAdd(Request $request)
{
    // Validasi input
    $validate = $request->validate([
        'member_id' => 'required|exists:members,id',
        'university_id' => 'required|exists:universities,id',
        'program_study_id' => 'required|exists:program_studies,id',
        'status' => 'nullable|in:pending,accepted,rejected'
    ]);

    // Simpan study plan baru (tanpa batasan universitas & program studi)
    $studyPlan = StudyPlane::create([
        'member_id' => $validate['member_id'],
        'university_id' => $validate['university_id'],
        'program_study_id' => $validate['program_study_id'],
        'status' => $validate['status'],
    ]);

    return response()->json([
        'error' => false,
        'message' => 'Study plan added successfully by admin!',
        'data' => $studyPlan
    ], 201);
}

public function adminStudyPlaneUpdate(Request $request, $id)
{
    // Validasi input
    $validate = $request->validate([
        'university_id' => 'required|exists:universities,id',
        'program_study_id' => 'required|exists:program_studies,id',
        'status' => 'nullable|in:pending,accepted,rejected'
    ]);

    // Cari study plan berdasarkan ID
    $studyPlan = StudyPlane::findOrFail($id);

    // Update data
    $studyPlan->update([
        'university_id' => $validate['university_id'],
        'program_study_id' => $validate['program_study_id'],
        'status' => $validate['status'],
    ]);
    if ($validate['status'] == 'accepted') {
        StudyMember::create([
            'member_id' => $studyPlan->member_id,
            'university_id' => $validate['university_id'],
            'program_study_id' => $validate['program_study_id'],
        ]);
        Member::where('id', $studyPlan->member_id)->update([
            'is_studyng' => true
        ]);
    }

    return response()->json([
        'error' => false,
        'message' => 'Study plan updated successfully by admin!',
        'data' => $studyPlan
    ], 200);
}

public function adminStudyPlaneDelete($id)
{
    // Cari study plan berdasarkan ID
    $studyPlan = StudyPlane::findOrFail($id);

    // Hapus study plan dari database
    $studyPlan->delete();

    return response()->json([
        'error' => false,
        'message' => 'Study plan deleted successfully by admin!'
    ], 200);
}

}
