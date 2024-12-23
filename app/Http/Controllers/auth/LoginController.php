<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    protected $userService;
    /**
     * Constructor to inject UserService.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    /**
     * Handle user login request.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function loginUser(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // kredensial
        $credentials = $request->only('login', 'password');

        // Proses login menggunakan UserService
        $loginResult = $this->userService->login($credentials);

        // Jika login berhasil
        if ($loginResult['status']) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => false,
                    'message' => 'Login successful',
                    'loginResult' => [
                        'username' => $loginResult['user']['username'] ?? '',
                        'userId' => (string) ($loginResult['user']['id'] ?? ''),
                        'token' => $loginResult['token'] ?? '',
                        'role' => $loginResult['role'] ?? '',
                    ],
                ], 200);
            }

            // Redirect untuk UI
            return redirect()->route('dashboard')
                ->cookie('token', $loginResult['token'], 60, '/', null, true, true);
        }

        // login gagal
        if ($request->wantsJson()) {
            return response()->json([
                'loginResult' => null,
                'error' => true,
                'message' => $loginResult['message'],
            ], 401);
        }

        return back()->withErrors(['error' => $loginResult['message']]);
    }

    /**
     * Handle user logout request.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->userService->logout();
        if ($request->wantsJson()) {
            return response()->json([
                'error' => false,
                'message' => 'Logout successful',
            ], 200);
        }
        return redirect('/login');
    }
}
