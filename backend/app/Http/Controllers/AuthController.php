<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255|min:8',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registered successfully',
            'data' => [
                'user'  => $user,
                'token' => $user->createToken('api-token')->plainTextToken,
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email or password invalid',
                'data' => null
            ]);
        }

        return response()->json([
            'message' => 'Logged in successfully',
            'data' => [
                'user'  => $user,
                'token' => $user->createToken('api-token')->plainTextToken,
            ],
            'success' => true
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out successfully',
            'data' => null,
            'success' => true
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'message' => 'ok',
            'data' => $request->user(),
            'success' => true
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // Delete any existing token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        $token = Str::random(16);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        // Send email with the token
        Mail::send('emails.password-reset', ['token' => $token, 'email' => $request->email], function ($mail) use ($request) {
            $mail->to($request->email)
                ->subject('Password Reset Request');
        });

        return response()->json([
            'success' => true,
            'message' => 'Password reset link sent to your email',
            'data' => null
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:8'
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        // Check otken exists and matches
        if (!$record || !Hash::check($request->token, $record->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token',
                'data' => null
            ], 422);
        }

        // Optional: expire token after 60 minutes
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return response()->json([
                'success' => false,
                'message' => 'Token has expired.',
                'data'    => null,
            ], 422);
        }

        // Update password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete the token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Revoke all existing tokens (optional but recommended)
        $user = User::where('email', $request->email)->first();
        $user->tokens()->delete();

        return response()->json([
            'success'   => true,
            'message' => 'Password reset successfully.',
            'data'    => null,
        ]);
    }
}
