<?php

namespace App\Http\Controllers;

use App\Models\KontenEdukasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KontenEdukasiController extends Controller
{
    /**
     * Menampilkan daftar semua konten edukasi yang dipublikasikan.
     */
    public function index()
    {
        $kontenEdukasi = KontenEdukasi::where('is_published', true)->get();
        return response()->json($kontenEdukasi, 200);
    }

    /**
     * Membuat konten edukasi baru.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'judul' => 'required|max:255',
            'isi_konten' => 'required',
            'tipe_konten' => 'required|in:artikel,video',
            'is_published' => 'required|boolean',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $kontenEdukasi = KontenEdukasi::create([
            'judul' => $fields['judul'],
            'isi_konten' => $fields['isi_konten'],
            'tipe_konten' => $fields['tipe_konten'],
            'is_published' => $fields['is_published'],
            'thumbnail' => $thumbnailPath,
            'user_id' => Auth::id(),
        ]);

        return response()->json($kontenEdukasi, 201);
    }

    /**
     * Menampilkan detail konten edukasi.
     */
    public function show(KontenEdukasi $kontenEdukasi)
    {
        return response()->json($kontenEdukasi, 200);
    }

    /**
     * Memperbarui konten edukasi yang ada.
     */
    public function update(Request $request, KontenEdukasi $kontenEdukasi)
    {
        $fields = $request->validate([
            'judul' => 'sometimes|max:255',
            'isi_konten' => 'sometimes',
            'tipe_konten' => 'sometimes|in:artikel,video',
            'is_published' => 'sometimes|boolean',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Hapus thumbnail lama jika ada
            if ($kontenEdukasi->thumbnail) {
                Storage::disk('public')->delete($kontenEdukasi->thumbnail);
            }

            $fields['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $kontenEdukasi->update($fields);

        return response()->json($kontenEdukasi, 200);
    }

    /**
     * Menghapus konten edukasi.
     */
    public function destroy(KontenEdukasi $kontenEdukasi)
    {
        if ($kontenEdukasi->thumbnail) {
            Storage::disk('public')->delete($kontenEdukasi->thumbnail);
        }

        $kontenEdukasi->delete();

        return response()->json(['message' => 'Konten edukasi berhasil dihapus'], 200);
    }
}
