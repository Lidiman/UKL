<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    /**
     * Authenticate a user and issue a Bearer Token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan tidak cocok dengan data kami.'],
            ]);
        }

        $deviceName = $request->device_name ?? 'API Token';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
            'message' => 'Login berhasil'
        ]);
    }

    /**
     * Revoke the current user's token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil, token telah dihapus'
        ]);
    }
    /**
     * Update the authenticated user's profile (name, email, password).
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'             => 'sometimes|required|string|max:255',
            'email'            => 'sometimes|required|email|unique:users,email,' . $user->id,
            'current_password' => 'required_with:new_password|string',
            'new_password'     => 'nullable|string|min:8|confirmed',
        ]);

        // Verify current password before allowing password change
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password saat ini tidak sesuai.',
                ], 422);
            }
            $user->password = Hash::make($request->new_password);
        }

        if ($request->filled('name'))  $user->name  = $request->name;
        if ($request->filled('email')) $user->email = $request->email;

        $user->save();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
            'message' => 'Profil berhasil diperbarui.',
        ]);
    }
}
