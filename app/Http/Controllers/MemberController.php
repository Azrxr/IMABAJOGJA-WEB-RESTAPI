<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\MembersResource;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\MemberCollection;

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
            'province_id' => 'sometimes|integer|exists:provinces,id',
            'regency_id' => 'sometimes|integer|exists:regencies,id',
            'district_id' => 'sometimes|integer|exists:districts,id',
            'full_address' => 'sometimes|string|max:255',
            'kode_pos' => 'sometimes|string|max:255',
            'agama' => 'sometimes|string|max:255',
            'nisn' => 'sometimes|string|max:255',
            'tempat' => 'sometimes|string|max:255',
            'tanggal_lahir' => 'sometimes|date',
            'gender' => 'sometimes|string|in:male,female|max:255',
            'scholl_origin' => 'sometimes|string|max:255',
            'tahun_lulus' => 'sometimes|integer',
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
            // if ($member->profile_img) {
            //     Storage::disk('public')->delete($member->profile_img);
            // }

            // Simpan foto baru
            $img = $req->file('profile_img_path');
            $filename = date('Y-m-d') . '_' . $user->id . '_' . $user->username . '.' . $img->getClientOriginalExtension();
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

        $members = Member::with([
            'province:id,name',
            'regency:id,name',
            'district:id,name',
            'studyPlans.university:id,name',
    'studyPlans.programStudy:id,name',
    'studyMembers.university:id,name',
    'studyMembers.faculty:id,name',
    'studyMembers.programStudy:id,name'
        ])
            ->when(isset($filters['search']), function ($query) use ($filters) {
                $query->where('fullname', 'like', '%' . $filters['search'] . '%');
            })
            ->when(isset($filters['generation']), function ($query) use ($filters) {
                $query->where('generation', $filters['generation']);
            })
            ->when(isset($filters['member_type']), function ($query) use ($filters) {
                $query->where('member_type', $filters['member_type']);
            })
            ->paginate(10);

        if ($request->wantsJson()) {
            return response()->json([
                'error' => false,
                'message' => 'List members success!',
                'data' => MembersResource::collection($members)
            ]);
        }
    }

    public function member(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        if ($request->wantsJson()) {
            return response()->json([
                'error' => false,
                'message' => 'detail member success!',
                'data' => $member
            ]);
        }
    }
}
