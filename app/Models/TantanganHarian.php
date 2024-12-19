<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TantanganHarian extends Model
{
    protected $fillable = [
        'aktivitas',
        'deskripsi_aktivitas',
        'poin',
        'is_aktif',
    ];

    /**
     * Relasi ke tabel ValidasiTantangan.
     *
     * @return HasMany
     */
    public function validasiTantangan(): HasMany
    {
        return $this->hasMany(ValidasiTantangan::class, 'tantangan_harian_id');
    }
}
