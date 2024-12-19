<?php

namespace App\Http\Controllers;

use App\Models\Poin;
use App\Models\User;
use Illuminate\Http\Request;

class PoinController extends Controller
{
    /**
     * Menampilkan semua poin pengguna.
     */
    public function index()
    {
        $poin = Poin::with('user')->get();
        return response()->json($poin, 200);
    }

    /**
     * Menampilkan poin spesifik untuk pengguna tertentu.
     */
    public function show($id)
    {
        $poin = Poin::with('user')->where('user_id', $id)->first();

        if (!$poin) {
            return response()->json(['message' => 'Data poin tidak ditemukan.'], 404);
        }

        return response()->json($poin, 200);
    }

    /**
     * Menambahkan atau memperbarui poin pengguna.
     */
    public function updatePoin(Request $request, $id)
    {
        $validated = $request->validate([
            'total_poin' => 'required|integer|min:0',
        ]);

        $poin = Poin::where('user_id', $id)->first();

        if (!$poin) {
            return response()->json(['message' => 'Data poin tidak ditemukan.'], 404);
        }

        $poin->update(['total_poin' => $validated['total_poin']]);

        return response()->json([
            'message' => 'Poin berhasil diperbarui.',
            'data' => $poin,
        ], 200);
    }

    /**
     * Menambahkan poin ke pengguna tertentu.
     */
    public function addPoin(Request $request, $id)
    {
        $validated = $request->validate([
            'jumlah_poin' => 'required|integer|min:0',
        ]);

        $poin = Poin::where('user_id', $id)->first();

        if (!$poin) {
            return response()->json(['message' => 'Data poin tidak ditemukan.'], 404);
        }

        $poin->update(['total_poin' => $poin->total_poin + $validated['jumlah_poin']]);

        return response()->json([
            'message' => 'Poin berhasil ditambahkan.',
            'data' => $poin,
        ], 200);
    }

    /**
     * Reset poin pengguna (opsional).
     */
    public function resetPoin($id)
    {
        $poin = Poin::where('user_id', $id)->first();

        if (!$poin) {
            return response()->json(['message' => 'Data poin tidak ditemukan.'], 404);
        }

        $poin->update(['total_poin' => 0]);

        return response()->json([
            'message' => 'Poin berhasil direset.',
            'data' => $poin,
        ], 200);
    }
}
