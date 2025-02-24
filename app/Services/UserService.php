<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Support\Facades\Notification;

class UserService
{
    /**
     * Handle the user login logic.
     *
     * @param array $credentials
     * @return array
     */
    public function login(array $credentials)
    {
        // Cek apakah input adalah email atau username
        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Fetch user by email or username
        $user = User::where($loginField, $credentials['login'])->first();

        // Email not found
        if (!$user) {
            return [
                'status' => false,
                'message' => 'Email not found. Please check your email and try again.',
            ];
        }

        // Password mismatch
        if (!Hash::check($credentials['password'], $user->password)) {
            return [
                'status' => false,
                'message' => 'Incorrect password. Please check your password and try again.',
            ];
        }

        // // Validate user existence and password
        // if (!$user || !Hash::check($credentials['password'], $user->password)) {
        //     return [
        //         'status' => false,
        //         'message' => 'Invalid credentials',
        //     ];
        // }

        // Check if user is banned
        if ($user->banned) {
            return [
                'status' => false,
                'message' => 'Your account is banned. Reason: ' . ($user->ban_reason ?? 'No reason provided.'),

            ];
        }

        // Attempt to authenticate the user
        if (Auth::attempt([$loginField => $credentials['login'], 'password' => $credentials['password']])) {
            // Create token after successful authentication
            $user->tokens()->delete();
            $token = $user->createToken('API Token')->plainTextToken;

            return [
                'status' => true,
                'token' => $token,
                'role' => $user->role,
                'user' => $user,
            ];
        }

        return [
            'status' => false,
            'message' => 'Login failed',
        ];
    }

    /**
     * Logout the authenticated user.
     *
     * @return void
     */
    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }

    /**
     * Register a new user.
     *
     * @param array $data
     * @return array
     */
    public function register(array $data)
    {
        // Create the user
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => null,  // Set to null for verification process
            'role' => 'member',  // Or set a default role
        ]);

        // Send email verification
        //$this->sendVerificationEmail($user);

        return [
            'status' => true,
            'message' => 'Registration successful.',
            'user' => $user,
        ];
    }

    public function register_admin(array $data)
    {
        // Create the user
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => null,
            'role' => 'admin',  // Or set a default role
        ]);

        $admin = Admin::create([
            'user_id' => $user->id,
            'fullname' => $data['fullname'],
            'phone_number' => $data['phone_number'],
        ]);

        return [
            'status' => true,
            'message' => 'Registration successful.',
            'user' => $user, 
            'admin' => $admin
        ];
    }

    /**
     * Send email verification notification.
     *
     * @param User $user
     * @return void
     */
    private function sendVerificationEmail(User $user)
    {
        // Create a verification token
        $verificationToken = Str::random(60);

        // Store the token in the user (or you can use a different way to store the token)
        $user->remember_token = $verificationToken;
        $user->save();

        // Send the email (Assuming VerifyEmailNotification exists)
        Notification::send($user, new VerifyEmailNotification($verificationToken));
    }

    // Resend verification email
    public function resendVerificationEmail(User $user)
    {
        // Check if the user has already verified their email
        if ($user->email_verified_at) {
            return [
                'status' => false,
                'message' => 'Your email is already verified.'
            ];
        }

        // Send the verification email again
        $this->sendVerificationEmail($user);

        return [
            'status' => true,
            'message' => 'Verification email resent successfully. Please check your inbox.'
        ];
    }
}
