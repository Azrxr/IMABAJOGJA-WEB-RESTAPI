<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\DeleteService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DeleteController extends Controller
{
    protected $deleteService;
    protected $userService;

    public function __construct(DeleteService $deleteService, UserService $userService)
    {
        $this->deleteService = $deleteService;
        $this->userService = $userService;
    }

    // Untuk API
    public function destroyAccountApi(Request $request)

    {
        $user = Auth::user();
        $this->deleteService->deleteUser($user);

        return response()->json([
            'error' => false,
            'message' => 'Akun berhasil dihapus.'
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'error' => false,
                'message' => 'Akun berhasil dihapus.',
            ], 200);
        }
    }

    public function showDeleteForm()
    {
        return view('auth.delete-account');
    }

    public function destroyAccount(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Proses login ulang untuk validasi kredensial
        $credentials = $request->only('login', 'password');
        $loginResult = $this->userService->login($credentials);

        if (!$loginResult['status']) {
            return back()->withErrors(['error' => 'Kredensial tidak valid.']);
        }

        $user = Auth::user();
        $this->deleteService->deleteUser($user);

        return view('auth.delete-account-succes'); // buatkan file blade ini
    }
}
