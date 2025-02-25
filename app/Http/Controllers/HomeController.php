<?php

namespace App\Http\Controllers;

use App\Models\OrganizationFile;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\OrganizationProfile;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
        $organizationProfile = OrganizationProfile::with('file')->first();

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
        $fileName = "{$validate['title']}_" . time() . '.' . $file->getClientOriginalExtension();
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
}
