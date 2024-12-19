<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'username' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role' => 'in:pengguna,admin'
        ], [
            'username.required' => 'Kolom username wajib diisi.',
            'email.required' => 'Kolom email wajib diisi.',
            'email.email' => 'Masukkan format email yang valid.',
            'email.unique' => 'Email ini sudah digunakan.',
            'password.required' => 'Kolom password wajib diisi.',
            'password.confirmed' => 'Password konfirmasi tidak cocok.'
        ]);
    
        $user = User::create([
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role' => $fields['role'] ?? 'pengguna',
        ]);
    
        $token = $user->createToken($fields['username']);
    
        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken
        ], 201);
    }
    

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'message' => 'Kredensial yang diberikan salah'
            ];
        }

        $token = $user->createToken($user->username);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return [
            'message' => 'Anda berhasil logout'
        ];
    }
}
