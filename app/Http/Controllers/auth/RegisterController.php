<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function registerPage()
    {
        return view('auth.register');
    }

    /**
     * Handle the registration request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Validate the request
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Pass the validated data to the UserService
        $registerResult = $this->userService->register($request->only('email', 'username', 'password'));

        if ($request->wantsJson()) {
            if ($registerResult['status']) {
                return response()->json([
                    'status' => true,
                    'message' => $registerResult['message'],
                    'user' => $registerResult['user']
                ], 201);  // HTTP status 201 Created
            }

            return response()->json([
                'status' => false,
                'message' => 'Registration failed. Please try again.'
            ], 400);  // HTTP status 400 Bad Request
        }
         // If the request is from a browser (wants HTML view)
        if ($registerResult['status']) {
            return redirect()->route('login')->with('status', $registerResult['message']);
        }

        return back()->withErrors(['error' => 'Registration failed. Please try again.']);
    }

    public function Register_admin(Request $req){
        $req->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'fullname' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $registerResult = $this->userService->register_admin($req->only('email', 'username', 'password', 'fullname', 'phone_number'));

        if ($req->wantsJson()) {
            if ($registerResult['status']) {
                return response()->json([
                    'status' => true,
                    'message' => $registerResult['message'],
                    'user' => $registerResult['user']
                ], 201);  // HTTP status 201 Created
            }

            return response()->json([
                'status' => false,
                'message' => 'Registration failed. Please try again.'
            ], 400);  // HTTP status 400 Bad Request
        }
         // If the request is from a browser (wants HTML view)
        if ($registerResult['status']) {
            return redirect()->route('login')->with('status', $registerResult['message']);
        }

        return back()->withErrors(['error' => 'Registration failed. Please try again.']);
    }

    /**
     * Verify the user's email address.
     *
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    public function verifyEmail($token)
    {
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Invalid verification token.']);
        }

        // Mark the user's email as verified
        $user->email_verified_at = now();
        $user->remember_token = null;
        $user->save();

        return redirect()->route('login')->with('status', 'Email verified successfully.');
    }

    // Resend email verifikasi
    public function resendVerificationEmail(Request $request)
    {
        // Validasi email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Temukan user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Resend email verifikasi
        $result = $this->userService->resendVerificationEmail($user);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => $result['status'],
                'message' => $result['message']
            ]);
        }

        return back()->with('status', $result['message']);
    }
}
