<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke tabel ValidasiTantangan.
     */
    public function validasiTantangan(): HasMany
    {
        return $this->hasMany(ValidasiTantangan::class);
    }

    /**
     * Relasi ke tabel Poin.
     */
    public function poin(): HasOne
    {
        return $this->hasOne(Poin::class);
    }

    /**
     * Relasi ke tabel KontenEdukasi.
     */
    public function kontenEdukasi(): HasMany
    {
        return $this->hasMany(KontenEdukasi::class);
    }
}
