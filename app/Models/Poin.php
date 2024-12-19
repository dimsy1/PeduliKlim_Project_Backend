<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Poin extends Model
{
    /**
     * Kolom yang dapat diisi secara massal.
     */
    protected $fillable = [
        'total_poin',
        'user_id',
    ];

    /**
     * Nilai default untuk kolom.
     */
    protected $attributes = [
        'total_poin' => 0,
    ];

    /**
     * Relasi ke tabel User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
