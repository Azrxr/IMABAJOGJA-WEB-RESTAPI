<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\OrganizationFile;
use App\Models\OrganizationProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
        $organizationProfile = OrganizationProfile::with('files')->first() ?? null;
        // if ($organizationProfile && $organizationProfile->files) {
        //     foreach ($organizationProfile->files as $file) {
        //         $file->file_url = url('storage/' . $file->file_path);
        //     }
        // }
        if ($request->wantsJson()) {
            return response()->json([
                'error' => false,
                'message' => 'profile success!',
                'data' => $organizationProfile
            ]);
        }
    }

    public function editProfile(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'vision' => 'required',
            'mission' => 'required',
            'contact_email' => 'required',
            'contact_phone' => 'required',
            'contact_phone2' => 'required',
            'address' => 'required',
        ]);

        $organization = OrganizationProfile::updateOrCreate(
            ['id' => 1],
            $request->only([
                'title',
                'description',
                'vision',
                'mission',
                'contact_email',
                'contact_phone',
                'contact_phone2',
                'address'
            ])
        );


        if ($request->wantsJson()) {
            return response()->json([
                'error' => false,
                'message' => 'profile updated!',
                'data' => $organization
            ], 200);
        }
    }

    public function addFile(Request $request)
    {

        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $folderPath = "imaba";
        $file = $request->file('file_path'); // Ambil file dari request
        $safeTitle = Str::slug($validate['title']);
        $fileName = "{$safeTitle}_" . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($folderPath, $fileName, 'public');

        $organizationFile = OrganizationFile::create([
            'title' => $validate['title'],
            'description' => $validate['description'] ?? null,
            'organization_profile_id' => 1,
            'file_path' => $filePath, // Simpan path file di database
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'error' => false,
                'message' => 'file uploaded!',
                'data' => $organizationFile
            ], 200);
        }
    }

    public function updateFile(Request $request, $id)
    {
        // Validasi input
        $validate = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048', // File opsional saat update
        ]);

        // Cari file berdasarkan ID
        $organizationFile = OrganizationFile::findOrFail($id);

        // Jika ada file baru, hapus file lama & simpan file baru
        if ($request->hasFile('file_path')) {
            // Hapus file lama dari storage
            Storage::disk('public')->delete($organizationFile->file_path);

            // Simpan file baru
            $folderPath = "imaba";
            $file = $request->file('file_path');
            $safeTitle = Str::slug($validate['title']);
            $fileName = "{$safeTitle}_" . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs($folderPath, $fileName, 'public');

            // Update path file di database
            $organizationFile->file_path = $filePath;
        }

        // Update data lain
        $organizationFile->title = $validate['title'];
        $organizationFile->description = $validate['description'] ?? null;
        $organizationFile->save();

        if ($request->wantsJson()) {
        return response()->json([
            'error' => false,
            'message' => 'File updated successfully!',
            'data' => $organizationFile
        ], 200);
    }
    }

    public function deleteFile(Request $request, $id)
    {
        // Cari file berdasarkan ID
        $organizationFile = OrganizationFile::findOrFail($id);

        // Hapus file dari storage
        Storage::disk('public')->delete($organizationFile->file_path);

        // Hapus record dari database
        $organizationFile->delete();
        if ($request->wantsJson()) {
        return response()->json([
            'error' => false,
            'message' => 'File deleted successfully!'
        ], 200);
    }
        
    }
}
