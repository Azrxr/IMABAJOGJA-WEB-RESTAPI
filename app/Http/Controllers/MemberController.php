<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\MembersResource;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    //
    public function profile(Request $request)
    {
        $member = User::with([
            'member',
            'member.province:id,name',
            'member.regency:id,name',
            'member.district:id,name',
            'member.studyPlans',
            'member.studyMembers'
        ])->findOrFail(Auth::id());

        if ($member->role !== 'member') {
            return response()->json([
                'error' => true,
                'message' => 'ini bukan member',
                'data' => $member
            ]);
        }


        if ($request->wantsJson()) {
            return response()->json([
                'error' => false,
                'message' => 'profile success!',
                'data' => new ProfileResource($member)
            ]);
        }
    }

    //edit profile
    public function profileUpdate(Request $req)
    {
        // Validasi data dari request
        $validatedData = $req->validate([
            // Validasi untuk tabel users
            'username' => 'sometimes|string|max:255|unique:users,username,' . Auth::id(),
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . Auth::id(),
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',

            // Validasi untuk tabel members
            'fullname' => 'sometimes|string|max:255',
            'phone_number' => 'sometimes|string|max:255',
            'profile_img_path' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'province_id' => 'sometimes|integer|exists:provincies,id',
            'regency_id' => 'sometimes|integer|exists:regencies,id',
            'district_id' => 'sometimes|integer|exists:districts,id',
            'full_address' => 'sometimes|string|max:255',
            'kode_pos' => 'sometimes|string|max:255',
            'agama' => 'sometimes|string|in:Islam,Kristen,Katolik,Hindu,Budha,Konghucu,Lainnya|max:255',
            'nisn' => 'sometimes|string|max:255',
            'tempat' => 'sometimes|string|max:255',
            'tanggal_lahir' => 'sometimes|date',
            'gender' => 'sometimes|string|in:Laki-laki,Perempuan|max:255',
            'scholl_origin' => 'sometimes|string|max:255',
            'tahun_lulus' => 'sometimes|integer',
            'angkatan' => 'sometimes|integer',
            'is_studyng' => 'sometimes|boolean',

        ]);
        // Ambil data user dan members
        $user = User::findOrFail(Auth::id());

        // Perbarui data pengguna di tabel users
        if (isset($validatedData['username'])) {
            $user->username = $validatedData['username'];
        }
        if (isset($validatedData['email'])) {
            $user->email = $validatedData['email'];
        }

        // Perbarui password jika ada
        if (isset($validatedData['current_password']) && isset($validatedData['new_password'])) {
            if (!Hash::check($validatedData['current_password'], $user->password)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Password saat ini salah'
                ], 400);
            }
            $user->password = Hash::make($validatedData['new_password']);
        }

        $user->save();
        $member = $user->member;

        if (!$member) {
            $member = new Member();
            $member->user_id = $user->id;
            $member->member_type = 'camaba';
        }

        // Perbarui data member atau buat member baru jika belum ada
        $member->fill($validatedData);

        // Perbarui foto profil jika ada
        if ($req->hasFile('profile_img_path')) {
            // Hapus foto lama jika ada
            if ($member->profile_img_path && Storage::disk('public')->exists($member->profile_img_path)) {
                Storage::disk('public')->delete($member->profile_img_path);
            }
            // Simpan foto baru
            $img = $req->file('profile_img_path');
            $filename = now()->format('Ymd_His') . '_' . $user->id . '_' . $user->username . '.' . $img->getClientOriginalExtension();
            $path = 'photo_user/' . $filename;

            Storage::disk('public')->put($path, file_get_contents($img));
            $member->profile_img_path = $path;
        }

        $member->save();


        return response()->json([
            'error' => false,
            'message' => $member->wasRecentlyCreated
                ? 'Profile created successfully for user ' . $member
                : 'Profile updated successfully',
            'data' => [
                'user' => $user,
            ],
        ]);
    }

    public function members(Request $request)
    {
        $filters = $request->only(['search', 'generation', 'member_type']);

        $query = Member::with([
            'province:id,name',
            'regency:id,name',
            'district:id,name',
            'studyPlans.university:id,name',
            'studyPlans.programStudy:id,name',
            'studyMembers.university:id,name',
            'studyMembers.faculty:id,name',
            'studyMembers.programStudy:id,name'
        ]);

        // Filter berdasarkan nama (search)
        if ($request->filled('search')) {
            $query->where('fullname', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('generation')) {
            $generations = $request->input('generation');
            if (is_array($generations)) {
                $query->whereIn('angkatan', $generations);
            } else {
                $query->where('angkatan', $generations);
            }
        }


        if ($request->filled('member_type')) {
            $memberTypes = $request->input('member_type');
            if (is_array($memberTypes)) {
                $query->whereIn('member_type', $memberTypes);
            } else {
                $query->where('member_type', $memberTypes);
            }
        }

        $totalQuery = clone $query;

        $totalMember = $totalQuery->count();
        $totalDemissioner = (clone $query)->where('member_type', 'demissioner')->count();
        $totalProspective = (clone $query)->where('member_type', 'camaba')->count();
        $totalManagement = (clone $query)->where('member_type', 'pengurus')->count();
        $totalRegular = (clone $query)->where('member_type', 'anggota')->count();
        $totalSpecial = (clone $query)->where('member_type', 'istimewa')->count();

        $members = $query->paginate(10);

        if ($request->wantsJson()) {
            return response()->json([
                'error' => false,
                'message' => 'List members success!',
                'total_member' => $totalMember,
                'total_member_demissioner' => $totalDemissioner,
                'total_member_prospective' => $totalProspective,
                'total_member_management' => $totalManagement,
                'total_member_regular' => $totalRegular,
                'total_member_special' => $totalSpecial,
                'data' =>
                [
                    'current_page' => $members->currentPage(),
                    'data' => MembersResource::collection($members), // Menggunakan Resource
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

    public function member(Request $request, $id)
    {
        // $member = Member::findOrFail($id);
        $member = Member::with([
            'province:id,name',
            'regency:id,name',
            'district:id,name',
            'studyPlans.university:id,name',
            'studyPlans.programStudy:id,name',
            'studyMembers.university:id,name',
            'studyMembers.faculty:id,name',
            'studyMembers.programStudy:id,name'
        ])->findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json([
                'error' => false,
                'message' => 'detail member success!',
                'data' => new MembersResource($member)
            ]);
        }
    }

    public function createMember(Request $request)
    {
        $validatedData = $request->validate([
            'members' => 'required|array|min:1', // Harus array dan minimal ada 1 data
            'members.*.no_member' => 'required|string|max:255',
            'members.*.angkatan' => 'required|integer',
            'members.*.fullname' => 'required|string|max:255',
            'members.*.phone_number' => 'required|string|max:255',
            'members.*.province_id' => 'required|integer|exists:provincies,id',
            'members.*.regency_id' => 'required|integer|exists:regencies,id',
            'members.*.district_id' => 'required|integer|exists:districts,id',
            'members.*.full_address' => 'required|string|max:255',
            'members.*.agama' => 'required|string|in:islam,kristen,katolik,hindu,budha,konghucu,lainnya|max:255',
            'members.*.member_type' => 'required|string|in:camaba,pengurus,anggota,demissioner,istimewa|max:255',
            'members.*.nisn' => 'required|string|max:255',
            'members.*.tempat' => 'required|string|max:255',
            'members.*.tanggal_lahir' => 'required|date',
            'members.*.gender' => 'required|string|in:male,female|max:255',
            'members.*.kode_pos' => 'required|string|max:255',
            'members.*.scholl_origin' => 'required|string|max:255',
            'members.*.tahun_lulus' => 'required|integer',
            'members.*.is_studyng' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            $createdMembers = [];

            foreach ($validatedData['members'] as $memberData) {
                // Buat user baru
                $existingMember = Member::where('no_member', $memberData['no_member'])->first();

                if ($existingMember) {
                    // Jika member sudah ada, update data
                    $existingMember->update($memberData);
                    $user = $existingMember->user;
                    $message = "updated";
                } else {
                    $user = User::create([
                        'email' => $memberData['no_member'] . '@example.com',
                        'username' => $memberData['no_member'],
                        'password' => Hash::make('Pass' . $memberData['no_member']),
                        'role' => 'member',
                    ]);

                    // Buat member baru
                    $existingMember = Member::create(array_merge($memberData, [
                        'user_id' => $user->id,
                    ]));
                    $message = "created";
                }
                // Tambahkan ke daftar member yang berhasil dibuat
                $createdMembers[] = [
                    'message' => $message,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'username' => $user->username,
                    ],
                    'member' => [
                        'id' => $existingMember->id,
                        'no_member' => $existingMember->no_member,
                        'fullname' => $existingMember->fullname,
                    ]
                ];
            }

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => count($createdMembers) . ' members successfully created!',
                'data' => $createdMembers
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => true,
                'message' => 'Failed to create members',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function updateMember(Request $req, $id)
    {
        $member = Member::findOrFail($id);
        $validatedData = $req->validate([
            // Validasi untuk tabel users
            'username' => 'sometimes|string|max:255|unique:users,username,' . $member->user_id,
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $member->user_id,
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|confirmed',

            // Validasi untuk tabel members
            'fullname' => 'sometimes|string|max:255',
            'phone_number' => 'sometimes|string|max:255',
            'profile_img_path' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'province_id' => 'sometimes|integer|exists:provincies,id',
            'regency_id' => 'sometimes|integer|exists:regencies,id',
            'district_id' => 'sometimes|integer|exists:districts,id',
            'full_address' => 'sometimes|string|max:255',
            'kode_pos' => 'sometimes|string|max:255',
            'agama' => 'sometimes|string|in:Islam,Kristen,Katolik,Hindu,Budha,Konghucu,Lainnya|max:255',
            'nisn' => 'sometimes|string|max:255',
            'tempat' => 'sometimes|string|max:255',
            'tanggal_lahir' => 'sometimes|date',
            'gender' => 'sometimes|string|in:Laki-laki,Perempuan|max:255',
            'scholl_origin' => 'sometimes|string|max:255',
            'tahun_lulus' => 'sometimes|integer',
            'angkatan' => 'sometimes|string|max:255',
            'member_type' => 'sometimes|string|in:camaba,pengurus,anggota,demissioner,istimewa|max:255',
            'is_studyng' => 'sometimes|boolean',

        ]);
        // Ambil data user dan members
        
        $user = User::findOrFail($member->user_id);

        // Perbarui data pengguna di tabel users
        if (isset($validatedData['username'])) {
            $user->username = $validatedData['username'];
        }
        if (isset($validatedData['email'])) {
            $user->email = $validatedData['email'];
        }

        // Perbarui password jika ada
        if (isset($validatedData['current_password']) && isset($validatedData['new_password'])) {
            if (!Hash::check($validatedData['current_password'], $user->password)) {
                return response()->json([
                    'error' => true,
                    'message' => 'Password saat ini salah'
                ], 400);
            }
            $user->password = Hash::make($validatedData['new_password']);
        }

        $user->save();
        $member = $user->member;

        if (!$member) {
            $member = new Member();
            $member->user_id = $user->id;
            $member->member_type = 'camaba';
        }

        // Perbarui data member atau buat member baru jika belum ada
        $member->fill($validatedData);

        // Perbarui foto profil jika ada
        if ($req->hasFile('profile_img_path')) {
            // Hapus foto lama jika ada
            if ($member->profile_img_path && Storage::disk('public')->exists($member->profile_img_path)) {
                Storage::disk('public')->delete($member->profile_img_path);
            }
            // Simpan foto baru
            $img = $req->file('profile_img_path');
            $filename = now()->format('Ymd_His') . '_' . $user->id . '_' . $user->username . '.' . $img->getClientOriginalExtension();
            $path = 'photo_user/' . $filename;

            Storage::disk('public')->put($path, file_get_contents($img));
            $member->profile_img_path = $path;
        }

        $member->save();


        return response()->json([
            'error' => false,
            'message' => $member->wasRecentlyCreated
                ? 'Profile created successfully for user ' . $member
                : 'Profile updated successfully',
            'data' => [
                'user' => $user,
            ],
        ]);
    }
    
    public function deleteMember($id)
    {
        $member = Member::findOrFail($id);
        $user = User::findOrFail($member->user_id);

        // Hapus member
        $member->delete();
        // Hapus user
        $user->delete();

        return response()->json([
            'error' => false,
            'message' => 'Member deleted successfully',
            'data' => null
        ]);
    }
}
