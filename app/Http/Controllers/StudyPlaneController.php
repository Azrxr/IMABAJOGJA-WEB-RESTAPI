<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemberStudyResource;
use App\Models\User;
use App\Models\Member;
use App\Models\StudyPlane;
use App\Models\University;
use App\Models\StudyMember;
use App\Models\ProgramStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Faculty;
use App\Models\HomePhoto;

class StudyPlaneController extends Controller
{

    public function studyMember()
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

        $studyPlans = StudyMember::where('member_id', $memberId)
            ->with('university', 'programStudy', 'faculty')
            ->latest()->first();

        return response()->json([
            'error' => false,
            'message' => 'Get study current success!',
            'data' => $studyPlans
        ]);
    }

    public function updateStudyMember(Request $request)
    {
        // Validasi input
        $validate = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'program_study_id' => 'required|exists:program_studies,id',
            'faculty_id' => 'required|exists:faculties,id',
        ]);

        // Ambil member yang sedang login
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

        // Ambil study member terbaru
        $studyMember = StudyMember::where('member_id', $memberId)
            ->latest()
            ->first();

        if ($studyMember) {
            // Jika sudah ada, lakukan update
            $studyMember->update([
                'university_id' => $validate['university_id'],
                'program_study_id' => $validate['program_study_id'],
                'faculty_id' => $validate['faculty_id']
            ]);
        } else {
            // Jika belum ada, buat baru
            $studyMember = StudyMember::create([
                'member_id' => $memberId,
                'university_id' => $validate['university_id'],
                'program_study_id' => $validate['program_study_id'],
                'faculty_id' => $validate['faculty_id']
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Study member updated successfully!',
            'data' => $studyMember
        ]);
    }

    public function deleteStudyMember()
    {
        // Ambil member yang sedang login
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

        // Ambil study member terbaru
        $studyMember = StudyMember::where('member_id', $memberId)
            ->latest()
            ->first();

        if (!$studyMember) {
            return response()->json([
                'error' => true,
                'message' => 'Study member tidak ditemukan!'
            ], 404);
        }

        // Hapus study member dari database
        $studyMember->delete();

        return response()->json([
            'error' => false,
            'message' => 'Study member deleted successfully!',
            'data' => null
        ]);
    }



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

    public function adminStudyPlaneDelete ($memberId, $studyPlanId)
    {
        $member = Member::findOrFail($memberId);
        $studyPlan = $member->studyPlans()->where('id', $studyPlanId)->firstOrFail();
        if (!$studyPlan) {
            return response()->json([
                'error' => true,
                'message' => 'Data study plan tidak ditemukan untuk member ini.'
            ], 404);
        }

        // Hapus dari database
        $studyPlan->delete();

        return response()->json([
            'error' => false,
            'message' => 'Study plan deleted successfully!'
        ], 200);
    }
    public function adminStudyPlaneAdd(Request $request, $memberId)
    {
        // Validasi input
        $validate = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'program_study_id' => 'required|exists:program_studies,id',
        ]);

        // Simpan study plan baru (tanpa batasan universitas & program studi)
        $studyPlan = StudyPlane::create([
            'member_id' => $memberId,
            'university_id' => $validate['university_id'],
            'program_study_id' => $validate['program_study_id'],
            'status' => 'pending',
        ]);

        return response()->json([
            'error' => false,
            'message' => 'Study plan added successfully by admin!',
            'data' => $studyPlan
        ], 201);
    }

    public function adminStudyPlaneUpdate(Request $request, $memberId, $studyPlanId)
    {
        $member = Member::findOrFail($memberId);
        $studyPlan = $member->studyPlans()->where('id', $studyPlanId)->firstOrFail();
        if (!$studyPlan) {
            return response()->json([
                'error' => true,
                'message' => 'Data study plan tidak ditemukan untuk member ini.'
            ], 404);
        }

        // Validasi input
        $validate = $request->validate([
            'university_id' => 'sometimes|exists:universities,id',
            'program_study_id' => 'sometimes|exists:program_studies,id',
            'status' => 'sometimes|in:pending,accepted,rejected,active',
        ]);


        // Jika status yang dikirim adalah 'active', ubah semua yang lain jadi 'accepted' terlebih dahulu
        if (($validate['status'] ?? '') === 'active') {
            $member->studyPlans()
                ->where('id', '!=', $studyPlan->id)
                ->where('status', 'active')
                ->update(['status' => 'pending']);
        }
        $studyPlan->update($validate);

        if (($validate['status'] ?? '') === 'active') {
            // Pastikan university dan program_study disediakan
            if (isset($validate['university_id']) && isset($validate['program_study_id'])) {
                StudyMember::updateOrCreate(
                    ['member_id' => $memberId], // Condition (unique key)
                    [
                        'university_id' => $validate['university_id'],
                        'program_study_id' => $validate['program_study_id'],
                    ]
                );

                $member->update(['is_studyng' => true]);
            }
        }

        return response()->json([
            'error' => false,
            'message' => 'Study plan updated successfully by admin!',
            'data' => $studyPlan
        ], 200);
    }

    public function adminUpdateStudyMember(Request $request, $memberId)
    {
        // Validasi input
        $validate = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'program_study_id' => 'required|exists:program_studies,id',
            'faculty_id' => 'sometimes|exists:faculties,id',
        ]);

        // Ambil member berdasarkan ID
        $member = Member::findOrFail($memberId);

        // Update atau buat study member
        $studyMember = StudyMember::updateOrCreate(
            ['member_id' => $member->id],
            [
                'university_id' => $validate['university_id'],
                'program_study_id' => $validate['program_study_id'],
                'faculty_id' => $validate['faculty_id']
            ]
        );

        return response()->json([
            'error' => false,
            'message' => 'Study member updated successfully!',
            'data' => $studyMember
        ]);
    }

    private function addExtraData($member, $filled, $total, $hasHomePhotos = false, $homePhotos = null)
    {
        $member->berkas_progress = "$filled / $total";
        $member->berkas_lengkap = $filled === $total && $hasHomePhotos;
        $member->status_kuliah = $member->studyMember ? 'sudah' : ($member->studyPlane ? 'rencana' : 'belum');
        $member->university = $member->studyMember->university->name ?? $member->studyPlane->university->name ?? null;
        $member->has_home_photos = $hasHomePhotos;
        $member->home_photos = $homePhotos; // ✅ Include home_photos full data
        return $member;
    }

    public function getStudyMembers(Request $request)
    {
        $query = Member::with([
            'user',
            'studyPlans.university:id,name',
            'studyPlans.programStudy:id,name',
            'studyMembers.university:id,name',
            'studyMembers.programStudy:id,name',
            'documents.homePhoto'
        ]);

        // FILTER: search by fullname
        if ($request->filled('search')) {
            $query->where('fullname', 'like', '%' . $request->search . '%');
        }

        // FILTER: angkatan
        if ($request->filled('angkatan')) {
            $angkatan = $request->angkatan;
            $query->whereIn('angkatan', is_array($angkatan) ? $angkatan : [$angkatan]);
        }

        // FILTER: status kuliah
        if ($request->filled('status_kuliah')) {
            $status = $request->status_kuliah;

            $query->when(
                $status === 'sudah',
                fn($q) =>
                $q->whereHas('studyMembers')
            )->when(
                $status === 'rencana',
                fn($q) =>
                $q->whereHas('studyPlans', fn($q) => $q->where('status', 'pending'))
                    ->whereDoesntHave('studyMembers')
            )->when(
                $status === 'belum',
                fn($q) =>
                $q->whereDoesntHave('studyPlans')->whereDoesntHave('studyMembers')
            );
        }

        // FILTER: university_id
        if ($request->filled('university_id')) {
            $univId = $request->university_id;
            $query->where(function ($q) use ($univId) {
                $q->whereHas('studyPlans', fn($q) => $q->where('university_id', $univId))
                    ->orWhereHas('studyMembers', fn($q) => $q->where('university_id', $univId));
            });
        }

        // Get paginated result
        $members = $query->paginate(10);

        return response()->json([
            'error' => false,
            'message' => 'List study members success!',
            'data' => [
                'current_page' => $members->currentPage(),
                'data' => MemberStudyResource::collection($members),
                'first_page_url' => $members->url(1),
                'from' => $members->firstItem(),
                'last_page' => $members->lastPage(),
                'last_page_url' => $members->url($members->lastPage()),
                'next_page_url' => $members->nextPageUrl(),
                'path' => $members->path(),
                'per_page' => $members->perPage(),
                'prev_page_url' => $members->previousPageUrl(),
                'to' => $members->lastItem(),
                'total' => $members->total(),
            ]
        ]);
    }
}
