<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Models\StudyPlane;
use App\Models\University;
use App\Models\StudyMember;
use App\Models\ProgramStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Faculty;

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

    public function university(Request $request)
    {
        $query = University::select('id', 'name');
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $universty = $query->get();

        return response()->json([
            'error' => false,
            'message' => 'Get universities success!',
            'data' => $universty->isEmpty() ? [['id' => 0, 'name' => 'Tidak ada']] : $universty
        ]);
    }

    public function programStudy(Request $request, $id)
    {
        $query = ProgramStudy::select('id', 'name', 'jenjang')
            ->where('university_id', $id);
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $programStudies = $query->get();

        return response()->json([
            'error' => false,
            'message' => 'Get program studies success!',
            'data' => $programStudies->isEmpty() ? [['id' => 0, 'name' => 'Tidak ada']] : $programStudies
        ]);
    }

    public function faculty(Request $request, $id)
    {
        $query = Faculty::select('id', 'name')
            ->where('university_id', $id);
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $programStudies = $query->get();

        return response()->json([
            'error' => false,
            'message' => 'Get program studies success!',
            'data' => $programStudies->isEmpty() ? [['id' => 0, 'name' => 'Tidak ada']] : $programStudies
        ]);
    }

    public function index(Request $request)
    {
        $member = User::with('member')
            ->findOrFail(Auth::id())
            ->member;

        if (!$member) {
            return response()->json([
                'error' => true,
                'message' => 'Anda belum memiliki data member!'
            ], 400);
        }

        $memberId = $member->id;

        $studyPlans = StudyPlane::where('member_id', $memberId)
            ->with('university', 'programStudy')
            ->get();

        return response()->json([
            'error' => false,
            'message' => 'Get study plans success!',
            'data' => $studyPlans
        ]);
    }
    public function studyPlaneAdd(Request $request)
    {
        // Ambil member ID dari user yang sedang login
        $member = User::with('member')
            ->findOrFail(Auth::id())
            ->member; // Mengambil relasi member, bukan ID user

        if (!$member) {
            return response()->json([
                'error' => true,
                'message' => 'Anda belum memiliki data member!'
            ], 400);
        }

        $memberId = $member->id;

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

    // ADMIN

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
