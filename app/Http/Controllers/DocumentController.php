<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\User;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HomePhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Upload or update documents for a member.
     */
    public function showDocument(Request $request)
    {
        $user = User::with('member')->findOrFail(Auth::id());
        $memberId = $user->member->id;

        $document = Document::where('member_id', $memberId)
            ->with('homePhoto')
            ->first();

        $message = $document ? 'Document found!' : 'Document not found!';
        if ($request->wantsJson()) {
            return ApiResponse::jsonResponse(
                false,
                $message,
                $document
            );
        }
    }

    public function uploadDocument(Request $request)
    {
        $user = User::with('member')->findOrFail(Auth::id());
        $memberId = $user->member->id;

        $validateDocument = $request->validate([
            'ktp' => 'nullable|file|mimes:pdf|max:2048',
            'kk' => 'nullable|file|mimes:pdf|max:2048',
            'ijazah' => 'nullable|file|mimes:pdf|max:2048',
            'ijazah_skl' => 'nullable|file|mimes:pdf|max:2048',
            'raport' => 'nullable|file|mimes:pdf|max:2048',
            'photo_3x4' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kk_legalisir' => 'nullable|file|mimes:pdf|max:2048',
            'akte_legalisir' => 'nullable|file|mimes:pdf|max:2048',
            'skhu_legalisir' => 'nullable|file|mimes:pdf|max:2048',
            'raport_legalisir' => 'nullable|file|mimes:pdf|max:2048',
            'surat_baik' => 'nullable|file|mimes:pdf|max:2048',
            'surat_rekom_kades' => 'nullable|file|mimes:pdf|max:2048',
            'surat_keterangan_baik' => 'nullable|file|mimes:pdf|max:2048',
            'surat_penghasilan_ortu' => 'nullable|file|mimes:pdf|max:2048',
            'surat_tidak_mampu' => 'nullable|file|mimes:pdf|max:2048',
            'surat_pajak_bumi_bangunan' => 'nullable|file|mimes:pdf|max:2048',
            'surat_tidak_pdam' => 'nullable|file|mimes:pdf|max:2048',
            'token_listrik' => 'nullable|file|mimes:pdf|max:2048',
            'skck' => 'nullable|file|mimes:pdf|max:2048',
            'sertifikat_prestasi' => 'nullable|file|mimes:pdf|max:2048',
            'foto_keluarga' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kartu_kip' => 'nullable|file|mimes:pdf|max:2048',
            'kartu_pkh' => 'nullable|file|mimes:pdf|max:2048',
            'kartu_kks' => 'nullable|file|mimes:pdf|max:2048',
            // 'home_photo_id' => 'nullable|integer|exists:home_photos,id',
        ]);

        $document = Document::firstOrNew(['member_id' => $memberId]);

        // Loop semua input yang difilter untuk diunggah
        foreach ($validateDocument as $key => $file) {
            if ($file) {

                $oldFile = $document->{$key};
                if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                    // Hapus file lama
                    Storage::disk('public')->delete($oldFile);
                }

                $folderPath = "documents/{$memberId}";
                $fileName = "{$key}_" . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($folderPath, $fileName, 'public');

                // Simpan path file di field terkait
                $document->{$key} = $path;
            }
        }

        $document->save();

        if ($request->wantsJson()) {
            return response()->json([
                'error' => false,
                'message' => 'Document uploaded successfully!',
                'data' => $document
            ]);
        }
    }

    public function uploadHomePhoto(Request $request)
    {
        $user = User::with('member')->findOrFail(Auth::id());
        $memberId = $user->member->id;
        $document = Document::where('member_id', $memberId)->first();
        if (!$document) {
            return ApiResponse::jsonResponse(
                true,
                'Document record not found. Please create the document first.',
                null,
                404
            );
        }
        $validatePhoto = $request->validate([
            'photo_title' => 'required|string',
            'photo_img' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $folderPath = "documents/{$memberId}";
        $fileName = "{$validatePhoto['photo_title']}_" . time() . '.' . $validatePhoto['photo_img']->getClientOriginalExtension();
        $path = $validatePhoto['photo_img']->storeAs($folderPath, $fileName, 'public');
        $homePhoto = HomePhoto::create([
            'document_id' => $document->id,
            'photo_title' => $validatePhoto['photo_title'],
            'photo_img' => $path,
        ]);

        if ($request->wantsJson()) {
            return ApiResponse::jsonResponse(
                false,
                'Home photo uploaded successfully!',
                $homePhoto
            );
        }
    }

    public function deleteHomePhoto(Request $request, $id)
{
    // Cari foto berdasarkan ID
    $homePhoto = HomePhoto::find($id);

    if (!$homePhoto) {
        // Jika foto tidak ditemukan
        return ApiResponse::jsonResponse(
            true,
            'Photo not found!',
            null
        );
    }

    // Hapus file foto dari storage
    Storage::disk('public')->delete($homePhoto->photo_img);

    // Hapus data foto dari database
    $homePhoto->delete();

    // Respon jika berhasil
    return ApiResponse::jsonResponse(
        false,
        'Photo deleted successfully!',
        null
    );
}

}
