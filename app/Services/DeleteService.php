<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteService
{
    public function deleteUser(User $user)
    {
        DB::transaction(function () use ($user) {
            // Jika role member
            if ($user->role === 'member') {
                $member = $user->member;

                // Hapus relasi jika ada, jika tidak maka skip
                // Hapus semua dokumen yang dimiliki member, termasuk home photo jika ada
                $documents = $member->documents()->get();
                foreach ($documents as $document) {
                    // Hapus home photo jika ada relasi homePhoto pada document
                    if (method_exists($document, 'homePhoto') && $document->homePhoto()->exists()) {
                        $document->homePhoto()->delete();
                    }
                    $document->delete();
                }
                $member->documents()->delete();

                if ($member->studyPlans()->exists()) {
                    $member->studyPlans()->delete();
                }
                if ($member->studyMembers()->exists()) {
                    $member->studyMembers()->delete();
                }
                $member->delete();



                // Jika role admin
            } elseif ($user->role === 'admin') {
                $admin = $user->admin;

                if ($admin) {
                    $admin->delete();
                }
            }

            // Terakhir, hapus user
            $user->tokens()->delete();
            $user->delete();
        });
    }
}
