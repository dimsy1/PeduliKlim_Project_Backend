<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Hanya admin yang dapat melihat daftar pengguna
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $fields = $request->validate([
            'username' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required|in:pengguna,admin',
        ]);

        // Buat pengguna baru dengan password yang di-hash
        $user = User::create([
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
            'role' => $fields['role'],
        ]);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        // Hanya admin atau pengguna yang sedang login yang dapat melihat detailnya
        if ($request->user()->role !== 'admin' && $request->user()->id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Hanya admin yang dapat mengupdate data pengguna lain
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validasi input
        $fields = $request->validate([
            'username' => 'sometimes|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes',
            'role' => 'sometimes|in:pengguna,admin',
        ]);

        // Hash password jika diupdate
        if (isset($fields['password'])) {
            $fields['password'] = Hash::make($fields['password']);
        }

        $user->update($fields);

        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $authUser = $request->user();

        // Cek apakah user yang sedang login mencoba menghapus dirinya sendiri
        if ($authUser->id === $user->id) {
            return response()->json(['message' => 'Anda tidak dapat menghapus akun Anda sendiri.'], 403);
        }

        // Cek apakah admin mencoba menghapus admin lain
        if ($authUser->role === 'admin' && $user->role === 'admin') {
            return response()->json(['message' => 'Admin tidak dapat menghapus akun admin lain.'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus'], 200);
    }
}
