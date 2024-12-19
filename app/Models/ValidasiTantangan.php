<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidasiTantangan extends Model
{
    protected $fillable = [
        'bukti',
        'is_validated',
        'user_id',                // Tambahkan user_id
        'tantangan_harian_id',    // Tambahkan tantangan_harian_id
    ];

    /**
     * Relasi ke tabel TantanganHarian.
     *
     * @return BelongsTo
     */
    public function tantanganHarian(): BelongsTo
    {
        return $this->belongsTo(TantanganHarian::class, 'tantangan_harian_id');
    }

    /**
     * Relasi ke tabel User.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
