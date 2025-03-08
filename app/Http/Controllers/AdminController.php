<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProfileResourceAdmin;
use Symfony\Component\HttpKernel\Profiler\Profile;

class AdminController extends Controller
{
    //
    public function profile_admin(Request $request)
    {
        $admin = User::with([
            'admin.provincy:id,name',
            'admin.regency:id,name',
            'admin.district:id,name',
        ])
            ->findOrFail(Auth::id());

        if ($admin->role !== 'admin') {
            return response()->json([
                'error' => true,
                'message' => 'ini bukan admin',
                'data' => $admin
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'error' => false,
                'message' => 'profile success!',
                'data' => $admin
            ]);
        }
    }

    public function updateProfile(Request $req)
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
        $admin = $user->admin;

        if (!$admin) {
            $admin = new Admin();
            $admin->user_id = $user->id;
        }

        // Perbarui data admin atau buat admin baru jika belum ada
        $admin->fill($validatedData);

        // Perbarui foto profil jika ada
        if ($req->hasFile('profile_img_path')) {
            // Hapus foto lama jika ada
            if ($admin->profile_img_path && Storage::disk('public')->exists($admin->profile_img_path)) {
                Storage::disk('public')->delete($admin->profile_img_path);
            }
            // if ($admin->profile_img) {
            //     Storage::disk('public')->delete($admin->profile_img);
            // }

            // Simpan foto baru
            $img = $req->file('profile_img_path');
            $filename = date('Y-m-d') . '_' . $user->id . '_' . $user->username . '.' . $img->getClientOriginalExtension();
            $path = 'photo_admin/' . $filename;

            Storage::disk('public')->put($path, file_get_contents($img));
            $admin->profile_img_path = $path;
        }

        $admin->save();


        return response()->json([
            'error' => false,
            'message' => $admin->wasRecentlyCreated
                ? 'Profile created successfully for user ' . $admin
                : 'Profile updated successfully',
            'data' => [
                'user' => $user,
            ],
        ]);
    }
}
