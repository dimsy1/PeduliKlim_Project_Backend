<?php

namespace App\Http\Controllers;

use App\Models\TantanganHarian;
use App\Models\ValidasiTantangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TantanganHarianController extends Controller
{
    /**
     * Menampilkan daftar tantangan harian.
     */
    public function index()
    {
        $userId = Auth::id();
        $completedChallenges = ValidasiTantangan::where('user_id', $userId)
            ->pluck('tantangan_harian_id');

        $tantanganHarian = TantanganHarian::where('is_aktif', true)
            ->get()
            ->map(function ($tantangan) use ($completedChallenges) {
                $tantangan->is_completed = $completedChallenges->contains($tantangan->id); // Tandai selesai
                return $tantangan;
            });

        return response()->json($tantanganHarian, 200);
    }

    /**
     * Membuat tantangan harian baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'aktivitas' => 'required|string|max:255',
            'deskripsi_aktivitas' => 'required|string',
            'poin' => 'required|integer',
            'is_aktif' => 'required|boolean',
        ]);

        $tantanganHarian = TantanganHarian::create($validated);

        return response()->json([
            'message' => 'Tantangan harian berhasil dibuat',
            'data' => $tantanganHarian,
        ], 201);
    }

    /**
     * Menampilkan detail tantangan harian.
     */
    public function show($id)
    {
        $tantanganHarian = TantanganHarian::findOrFail($id);
        return response()->json($tantanganHarian, 200);
    }

    /**
     * Memperbarui tantangan harian.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'aktivitas' => 'required|string|max:255',
            'deskripsi_aktivitas' => 'required|string',
            'poin' => 'required|integer|min:1',
            'is_aktif' => 'required|boolean',
        ]);

        $tantanganHarian = TantanganHarian::findOrFail($id);
        $tantanganHarian->update($validated);

        return response()->json([
            'message' => 'Tantangan harian berhasil diperbarui',
            'data' => $tantanganHarian,
        ], 200);
    }

    /**
     * Menghapus tantangan harian.
     */
    public function destroy($id)
    {
        $tantanganHarian = TantanganHarian::findOrFail($id);
        $tantanganHarian->delete();

        return response()->json([
            'message' => 'Tantangan harian berhasil dihapus',
        ], 200);
    }

    /**
     * Menandai tantangan sebagai selesai.
     */
    public function markAsCompleted(Request $request, $id)
    {
        $request->validate([
            'bukti' => 'required|image|max:2048', // Validasi untuk bukti aktivitas (gambar)
        ]);
    
        $tantanganHarian = TantanganHarian::findOrFail($id);
    
        // Simpan bukti aktivitas di tabel ValidasiTantangan
        $path = $request->file('bukti')->store('bukti_tantangan', 'public');
        $validasiTantangan = ValidasiTantangan::create([
            'tantangan_harian_id' => $tantanganHarian->id,
            'user_id' => Auth::id(),
            'bukti' => $path,
            'is_validated' => false, // Menunggu validasi admin
        ]);
    
        return response()->json([
            'message' => 'Bukti tantangan berhasil diunggah. Tantangan ditandai sebagai selesai.',
            'data' => [
                'tantangan_harian_id' => $tantanganHarian->id,
                'is_validated' => $validasiTantangan->is_validated,
            ],
        ], 201);
    }
    
    public function getCompletedChallenges()
    {
        $userId = Auth::id();
        $completedChallenges = ValidasiTantangan::where('user_id', $userId)
            ->with('tantanganHarian') // Relasi ke model TantanganHarian
            ->get(['tantangan_harian_id', 'is_validated']); // Ambil data yang diperlukan

        return response()->json($completedChallenges);
    }

    public function getTotalPoints()
    {
        $userId = Auth::id();
        $totalPoints = ValidasiTantangan::where('user_id', $userId)
            ->where('is_validated', true) // Hanya tantangan yang sudah divalidasi
            ->join('tantangan_harians', 'validasi_tantangans.tantangan_harian_id', '=', 'tantangan_harians.id')
            ->sum('tantangan_harians.poin'); // Sum dari poin tantangan

        return response()->json(['total_points' => $totalPoints], 200);
    }

}
