<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Models\Document;
use App\Models\HomePhoto;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            'ktp_path' => 'sometimes|file|mimes:pdf|max:2048',
            'kk_path' => 'sometimes|file|mimes:pdf|max:2048',
            'ijazah_path' => 'sometimes|file|mimes:pdf|max:2048',
            'ijazah_skl_path' => 'sometimes|file|mimes:pdf|max:2048',
            'raport_path' => 'sometimes|file|mimes:pdf|max:2048',
            'kk_legalisir_path' => 'sometimes|file|mimes:pdf|max:2048',
            'akte_legalisir_path' => 'sometimes|file|mimes:pdf|max:2048',
            'skhu_legalisir_path' => 'sometimes|file|mimes:pdf|max:2048',
            'ijazah_legalisir_path' => 'sometimes|file|mimes:pdf|max:2048',
            'raport_legalisir_path' => 'sometimes|file|mimes:pdf|max:2048',
            'surat_baik_path' => 'sometimes|file|mimes:pdf|max:2048',
            'surat_rekom_kades_path' => 'sometimes|file|mimes:pdf|max:2048',
            'surat_keterangan_baik_path' => 'sometimes|file|mimes:pdf|max:2048',
            'surat_penghasilan_ortu_path' => 'sometimes|file|mimes:pdf|max:2048',
            'surat_tidak_mampu_path' => 'sometimes|file|mimes:pdf|max:2048',
            'surat_pajak_bumi_bangunan_path' => 'sometimes|file|mimes:pdf|max:2048',
            'surat_tidak_pdam_path' => 'sometimes|file|mimes:pdf|max:2048',
            'token_listrik_path' => 'sometimes|file|mimes:pdf|max:2048',
            'skck_path' => 'sometimes|file|mimes:pdf|max:2048',
            'sertifikat_prestasi_path' => 'sometimes|file|mimes:pdf|max:2048',

            'foto_keluarga_path' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'photo_3x4_path' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',

            'kartu_kip_path' => 'sometimes|file|mimes:pdf|max:2048',
            'kartu_pkh_path' => 'sometimes|file|mimes:pdf|max:2048',
            'kartu_kks_path' => 'sometimes|file|mimes:pdf|max:2048',
        ]);

        $document = Document::firstOrNew(['member_id' => $memberId]);

        // Loop semua input yang difilter untuk diunggah
        foreach ($validateDocument as $key => $doc) {
            if ($doc) {
                $fieldName = str_replace('_path', '', $key);
                $oldDoc = $document->{$key};
                if ($oldDoc && Storage::disk('public')->exists($oldDoc)) {
                    // Hapus file lama
                    Storage::disk('public')->delete($oldDoc);
                }

                $folderPath = "documents/{$memberId}";
                $fileName = "{$fieldName}_" . now()->format('Ymd_His') . '.' . $doc->getClientOriginalExtension();
                $path = $doc->storeAs($folderPath, $fileName, 'public');

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

    public function deleteDocument(Request $request, $field)
    {
        $user = User::with('member')->findOrFail(Auth::id());
        $memberId = $user->member->id;

        $allowedFields = [
            'ijazah_path',
            'ktp_path',
            'kk_path',
            'ijazah_skl_path',
            'raport_path',
            'photo_3x4_path',
            'kk_legalisir_path',
            'akte_legalisir_path',
            'skhu_legalisir_path',
            'ijazah_legalisir_path',
            'raport_legalisir_path',
            'surat_baik_path',
            'surat_rekom_kades_path',
            'surat_keterangan_baik_path',
            'surat_penghasilan_ortu_path',
            'surat_tidak_mampu_path',
            'surat_pajak_bumi_bangunan_path',
            'surat_tidak_pdam_path',
            'token_listrik_path',
            'skck_path',
            'sertifikat_prestasi_path',
            'foto_keluarga_path',
            'kartu_kip_path',
            'kartu_pkh_path',
            'kartu_kks_path'
        ];
        if (!in_array($field, $allowedFields)) {
            return response()->json([
                'error' => true,
                'message' => 'Field yang diminta tidak valid!'
            ], 400);
        }
        $document = Document::where('member_id', $memberId)->first();

        if (!$document || !$document->{$field}) {
            return response()->json([
                'error' => true,
                'message' => 'Dokumen tidak ditemukan atau belum diunggah.'
            ], 404);
        }

        // Ambil path lengkap dari database
        $filePath = $document->{$field};

        // Hapus file dari storage hanya jika path ada
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        // Set field di database menjadi null
        $document->{$field} = null;
        $document->save();
        return response()->json([
            'error' => false,
            'message' => 'Dokumen berhasil dihapus!',
            'data' => $document
        ]);
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
            'photo_img_path' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $folderPath = "documents/{$memberId}";
        $fileName = "{$validatePhoto['photo_title']}_" . now()->format('Ymd_His') . '.' . $validatePhoto['photo_img_path']->getClientOriginalExtension();
        $path = $validatePhoto['photo_img_path']->storeAs($folderPath, $fileName, 'public');
        $homePhoto = HomePhoto::create([
            'document_id' => $document->id,
            'photo_title' => $validatePhoto['photo_title'],
            'photo_img_path' => $path,
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
        $homePhoto = HomePhoto::findOrFail($id);

        if (!$homePhoto) {
            // Jika foto tidak ditemukan
            return ApiResponse::jsonResponse(
                true,
                'Photo not found!',
                null
            );
        }

        // Hapus file foto dari storage
        // Pastikan file ada sebelum menghapus
        if ($homePhoto->photo_img_path && Storage::disk('public')->exists($homePhoto->photo_img_path)) {
            Storage::disk('public')->delete($homePhoto->photo_img_path);
        }
        // Hapus data foto dari database
        $homePhoto->delete();

        // Respon jika berhasil
        return ApiResponse::jsonResponse(
            false,
            'Photo deleted successfully!',
            null
        );
    }

    // ADMIN
    public function adminUploadDocument(Request $request, $memberId, $docId)
    {
        $member = Member::findOrFail($memberId);
        $document = $member->documents()->where('id', $docId)->firstOrNew();
        
        $validateDocument = $request->validate([
            'ktp_path' => 'sometimes|file|mimes:pdf|max:2048',
            'kk_path' => 'sometimes|file|mimes:pdf|max:2048',
            'ijazah_path' => 'sometimes|file|mimes:pdf|max:2048',
            'ijazah_skl_path' => 'sometimes|file|mimes:pdf|max:2048',
            'raport_path' => 'sometimes|file|mimes:pdf|max:2048',
            'kk_legalisir_path' => 'sometimes|file|mimes:pdf|max:2048',
            'akte_legalisir_path' => 'sometimes|file|mimes:pdf|max:2048',
            'skhu_legalisir_path' => 'sometimes|file|mimes:pdf|max:2048',
            'ijazah_legalisir_path' => 'sometimes|file|mimes:pdf|max:2048',
            'raport_legalisir_path' => 'sometimes|file|mimes:pdf|max:2048',
            'surat_baik_path' => 'sometimes|file|mimes:pdf|max:2048',
            'surat_rekom_kades_path' => 'sometimes|file|mimes:pdf|max:2048',
            'surat_keterangan_baik_path' => 'sometimes|file|mimes:pdf|max:2048',
            'surat_penghasilan_ortu_path' => 'sometimes|file|mimes:pdf|max:2048',
            'surat_tidak_mampu_path' => 'sometimes|file|mimes:pdf|max:2048',
            'surat_pajak_bumi_bangunan_path' => 'sometimes|file|mimes:pdf|max:2048',
            'surat_tidak_pdam_path' => 'sometimes|file|mimes:pdf|max:2048',
            'token_listrik_path' => 'sometimes|file|mimes:pdf|max:2048',
            'skck_path' => 'sometimes|file|mimes:pdf|max:2048',
            'sertifikat_prestasi_path' => 'sometimes|file|mimes:pdf|max:2048',

            'foto_keluarga_path' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'photo_3x4_path' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',

            'kartu_kip_path' => 'sometimes|file|mimes:pdf|max:2048',
            'kartu_pkh_path' => 'sometimes|file|mimes:pdf|max:2048',
            'kartu_kks_path' => 'sometimes|file|mimes:pdf|max:2048',
        ]);
        // Loop semua input yang difilter untuk diunggah
        foreach ($validateDocument as $key => $doc) {
            if ($doc) {
                $fieldName = str_replace('_path', '', $key);
                $oldDoc = $document->{$key};
                if ($oldDoc && Storage::disk('public')->exists($oldDoc)) {
                    // Hapus file lama
                    Storage::disk('public')->delete($oldDoc);
                }

                $folderPath = "documents/{$document->member_id}";
                $fileName = "{$fieldName}_" . now()->format('Ymd_His') . '.' . $doc->getClientOriginalExtension();
                $path = $doc->storeAs($folderPath, $fileName, 'public');

                // Simpan path file di field terkait
                $document->{$key} = $path;
            }
        }
        $document->save();
        if ($request->wantsJson()) {
            return response()->json([
                'error' => false,
                'message' => 'Document updated successfully!',
                'data' => $document
            ]);
        }
    }

    public function adminDeleteDocument($memberId, $field)
    {
        // $memberId = Member::findOrFail($memberId);

        $allowedFields = [
            'ijazah_path',
            'ktp_path',
            'kk_path',
            'ijazah_skl_path',
            'raport_path',
            'photo_3x4_path',
            'kk_legalisir_path',
            'akte_legalisir_path',
            'skhu_legalisir_path',
            'ijazah_legalisir_path',
            'raport_legalisir_path',
            'surat_baik_path',
            'surat_rekom_kades_path',
            'surat_keterangan_baik_path',
            'surat_penghasilan_ortu_path',
            'surat_tidak_mampu_path',
            'surat_pajak_bumi_bangunan_path',
            'surat_tidak_pdam_path',
            'token_listrik_path',
            'skck_path',
            'sertifikat_prestasi_path',
            'foto_keluarga_path',
            'kartu_kip_path',
            'kartu_pkh_path',
            'kartu_kks_path'
        ];
        if (!in_array($field, $allowedFields)) {
            return response()->json([
                'error' => true,
                'message' => 'Field yang diminta tidak valid!'
            ], 400);
        }
        $document = Document::where('member_id', $memberId)->first();

        if (!$document || !$document->{$field}) {
            return response()->json([
                'error' => true,
                'message' => 'Dokumen tidak ditemukan atau belum diunggah.'
            ], 404);
        }

        // Ambil path lengkap dari database
        $filePath = $document->{$field};

        // Hapus file dari storage hanya jika path ada
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        // Set field di database menjadi null
        $document->{$field} = null;
        $document->save();
        return response()->json([
            'error' => false,
            'message' => 'Dokumen berhasil dihapus!',
            'data' => $document
        ]);
    }

    public function adminUploadHomePhoto(Request $request, $memberId)
    {
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
            'photo_img_path' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $folderPath = "documents/{$memberId}";
        $fileName = "{$validatePhoto['photo_title']}_" . now()->format('Ymd_His') . '.' . $validatePhoto['photo_img_path']->getClientOriginalExtension();
        $path = $validatePhoto['photo_img_path']->storeAs($folderPath, $fileName, 'public');
        $homePhoto = HomePhoto::create([
            'document_id' => $document->id,
            'photo_title' => $validatePhoto['photo_title'],
            'photo_img_path' => $path,
        ]);

        if ($request->wantsJson()) {
            return ApiResponse::jsonResponse(
                false,
                'Home photo uploaded successfully!',
                $homePhoto
            );
        }
    }

    public function adminDeleteHomePhoto(Request $request, $id)
    {
        // Cari foto berdasarkan ID
        $homePhoto = HomePhoto::findOrFail($id);

        if (!$homePhoto) {
            // Jika foto tidak ditemukan
            return ApiResponse::jsonResponse(
                true,
                'Photo not found!',
                null
            );
        }

        // Hapus file foto dari storage
        // Pastikan file ada sebelum menghapus
        if ($homePhoto->photo_img_path && Storage::disk('public')->exists($homePhoto->photo_img_path)) {
            Storage::disk('public')->delete($homePhoto->photo_img_path);
        }
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
