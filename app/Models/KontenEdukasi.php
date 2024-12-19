<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KontenEdukasi extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika menggunakan nama tabel default Laravel)
    protected $table = 'konten_edukasis';

    // Kolom yang dapat diisi melalui mass assignment
    protected $fillable = [
        'judul',
        'isi_konten',
        'tipe_konten',
        'is_published',
        'thumbnail',
        'user_id',
    ];

    /**
     * Relasi ke tabel user (satu ke satu).
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
