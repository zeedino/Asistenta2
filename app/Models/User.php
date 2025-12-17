<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'token',
        'status',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is dosen
     */
    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    /**
     * Check if user is mahasiswa
     */
    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    /**
     * Check if mahasiswa has active SK
     * Untuk middleware EnsureMahasiswaHasSK
     */
    public function hasActiveSK(): bool
    {
        if (! $this->isMahasiswa()) {
            return false;
        }

        // Cek apakah ada SK aktif untuk mahasiswa ini
        return SuratKeputusan::where('mahasiswa_id', $this->id)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Untuk Dosen: Cek apakah punya mahasiswa bimbingan aktif
     * Untuk AvailabilityController validation
     */
    public function hasMahasiswaBimbinganAktif(): bool
    {
        if (! $this->isDosen()) {
            return false;
        }

        return DB::table('dosen_mahasiswa as dm')
            ->join('surat_keputusan as sk', 'dm.sk_id', '=', 'sk.id')
            ->where('dm.dosen_id', $this->id)
            ->where('sk.status', 'active')
            ->exists();
    }

    /**
     * Untuk Dosen: Ambil ID mahasiswa bimbingan aktif
     */
    public function mahasiswaBimbinganAktifIds()
    {
        if (! $this->isDosen()) {
            return collect();
        }

        return DB::table('dosen_mahasiswa as dm')
            ->join('surat_keputusan as sk', 'dm.sk_id', '=', 'sk.id')
            ->where('dm.dosen_id', $this->id)
            ->where('sk.status', 'active')
            ->pluck('dm.mahasiswa_id');
    }

    /**
     * Untuk Mahasiswa: Ambil dosen pembimbing yang aktif (untuk method availableSlots)
     * Ini return collection, bukan query builder
     */
    public function dosenPembimbingAktif()
    {
        if (! $this->isMahasiswa()) {
            return collect();
        }

        return $this->dosenPembimbing()
            ->whereHas('suratKeputusan', function ($query) {
                $query->where('mahasiswa_id', $this->id)
                    ->where('status', 'active');
            })
            ->get();
    }

    // ==================== RELATIONSHIPS ====================

    // Untuk DOSEN: Availability yang dibuat
    public function availabilities()
    {
        return $this->hasMany(Availability::class, 'dosen_id');
    }

    // Untuk DOSEN: Meetings sebagai pembimbing
    public function dosenMeetings()
    {
        return $this->hasMany(Meeting::class, 'dosen_id');
    }

    // Untuk MAHASISWA: Meetings sebagai yang dibimbing
    public function mahasiswaMeetings()
    {
        return $this->hasMany(Meeting::class, 'mahasiswa_id');
    }

    // Untuk DOSEN: Logs yang perlu divalidasi
    public function dosenLogs()
    {
        return $this->hasMany(Log::class, 'dosen_id');
    }

    // Untuk MAHASISWA: Logs yang dibuat
    public function mahasiswaLogs()
    {
        return $this->hasMany(Log::class, 'mahasiswa_id');
    }

    // Documents yang diupload
    public function documents()
    {
        return $this->hasMany(Document::class, 'user_id');
    }

    public function suratKeputusan()
    {
        return $this->hasMany(SuratKeputusan::class, 'mahasiswa_id');
    }

    // Untuk DOSEN: Mahasiswa bimbingan (melalui pivot)
    public function mahasiswaBimbingan()
    {
        return $this->belongsToMany(User::class, 'dosen_mahasiswa', 'dosen_id', 'mahasiswa_id')
            ->withPivot(['sk_id', 'posisi'])
            ->withTimestamps();
    }

    // Untuk MAHASISWA: Dosen pembimbing (melalui pivot)
    public function dosenPembimbing()
    {
        return $this->belongsToMany(User::class, 'dosen_mahasiswa', 'mahasiswa_id', 'dosen_id')
            ->withPivot(['sk_id', 'posisi'])
            ->withTimestamps();
    }

    // Untuk MAHASISWA: Relasi ke pivot table
    public function dosenMahasiswa()
    {
        return $this->hasMany(DosenMahasiswa::class, 'mahasiswa_id');
    }

    // Untuk DOSEN: Relasi ke pivot table sebagai dosen
    public function dosenMahasiswaAsDosen()
    {
        return $this->hasMany(DosenMahasiswa::class, 'dosen_id');
    }

    // Untuk ADMIN: SK yang dibuat oleh admin ini
    public function skDibuat()
    {
        return $this->hasMany(SuratKeputusan::class, 'admin_id');
    }

    // ==================== SCOPES ====================

    // Scope untuk mendapatkan user berdasarkan role
    public function scopeDosen($query)
    {
        return $query->where('role', 'dosen');
    }

    public function scopeMahasiswa($query)
    {
        return $query->where('role', 'mahasiswa');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }
}
