<?php

namespace App\Http\Controllers;

use App\Models\ValidasiTantangan;
use App\Models\TantanganHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiTantanganController extends Controller
{
    /**
     * Menampilkan daftar validasi tantangan (untuk admin).
     */
    public function index()
    {
        // Hanya admin yang bisa melihat semua validasi
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $validasiTantangan = ValidasiTantangan::with(['tantanganHarian', 'user'])->get();

        return response()->json($validasiTantangan, 200);
    }

    /**
     * Menampilkan detail validasi tantangan tertentu.
     */
    public function show($id)
    {
        $validasiTantangan = ValidasiTantangan::with(['tantanganHarian', 'user'])->findOrFail($id);

        return response()->json($validasiTantangan, 200);
    }

    /**
     * Memvalidasi tantangan harian (admin).
     */
    public function validateChallenge(Request $request, $id)
    {
        // Validasi hanya dapat dilakukan oleh admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $validasiTantangan = ValidasiTantangan::findOrFail($id);

        // Update status validasi
        $validasiTantangan->update([
            'is_validated' => true,
        ]);

        // Tambahkan poin ke pengguna
        $user = $validasiTantangan->user;
        $user->poin()->increment('total_poin', $validasiTantangan->tantanganHarian->poin);

        return response()->json([
            'message' => 'Tantangan berhasil divalidasi dan poin ditambahkan ke pengguna',
            'data' => $validasiTantangan,
        ], 200);
    }

    /**
     * Menghapus validasi tantangan (opsional, jika admin perlu membatalkan validasi).
     */
    public function destroy($id)
    {
        // Hanya admin yang bisa menghapus validasi
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $validasiTantangan = ValidasiTantangan::findOrFail($id);
        $validasiTantangan->delete();

        return response()->json(['message' => 'Validasi tantangan berhasil dihapus'], 200);
    }

    public function rejectChallenge($id)
    {
        // Validasi hanya dapat dilakukan oleh admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $validasiTantangan = ValidasiTantangan::findOrFail($id);

        // Update status validasi menjadi rejected
        $validasiTantangan->update([
            'is_validated' => false,
            'status' => 'rejected', // Menandai tantangan sebagai ditolak
        ]);

        return response()->json([
            'message' => 'Tantangan berhasil ditolak',
            'data' => $validasiTantangan,
        ], 200);
    }

}


