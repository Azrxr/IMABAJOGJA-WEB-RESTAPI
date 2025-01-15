<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
    public function profile_admin(Request $request)
    {
        $admin = User::with('admin')
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
}
