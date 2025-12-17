<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeputusan extends Model
{
    use HasFactory;

    protected $table = 'surat_keputusan';

    protected $fillable = [
        'nomor_sk',
        'tanggal_sk',
        'tahun_akademik',
        'semester',
        'mahasiswa_id',
        'admin_id',
        'file_sk',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal_sk' => 'date',
    ];

    // ==================== RELATIONSHIPS ====================

    // Mahasiswa yang dibimbing (SK dibuat untuk mahasiswa ini)
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    // Admin yang membuat SK
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Dosen pembimbing (melalui pivot table)
    public function dosenPembimbing()
    {
        return $this->belongsToMany(User::class, 'dosen_mahasiswa', 'sk_id', 'dosen_id')
            ->withPivot('posisi')
            ->withTimestamps();
    }

    // Relasi ke pivot table
    public function dosenMahasiswa()
    {
        return $this->hasMany(DosenMahasiswa::class, 'sk_id');
    }

    // ==================== SCOPES ====================

    public function scopeByTahunAkademik($query, $tahun)
    {
        return $query->where('tahun_akademik', $tahun);
    }

    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeByMahasiswa($query, $mahasiswaId)
    {
        return $query->where('mahasiswa_id', $mahasiswaId);
    }

    public function scopeByDosen($query, $dosenId)
    {
        return $query->whereHas('dosenPembimbing', function ($q) use ($dosenId) {
            $q->where('users.id', $dosenId);
        });
    }

    // ✅ TAMBAHKAN SCOPE INI untuk middleware
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ==================== METHODS ====================

    public function getFormattedTanggal()
    {
        return $this->tanggal_sk->translatedFormat('d F Y');
    }

    public function getInfo()
    {
        return "SK {$this->nomor_sk} - {$this->mahasiswa->username} ({$this->tahun_akademik} {$this->semester})";
    }

    // Get pembimbing 1
    public function pembimbing1()
    {
        return $this->dosenPembimbing()
            ->wherePivot('posisi', 'Pembimbing 1')
            ->first();
    }

    // Get pembimbing 2
    public function pembimbing2()
    {
        return $this->dosenPembimbing()
            ->wherePivot('posisi', 'Pembimbing 2')
            ->first();
    }

    // Check if SK has both pembimbing
    public function hasCompletePembimbing()
    {
        return $this->dosenMahasiswa()->count() === 2;
    }

    // ✅ TAMBAHKAN METHOD INI untuk middleware
    /**
     * Check if SK is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    // ✅ TAMBAHKAN METHOD INI untuk convenience
    /**
     * Check if specific dosen is a supervisor for this SK
     */
    public function hasDosenAsSupervisor($dosenId): bool
    {
        return $this->dosenPembimbing()
            ->where('users.id', $dosenId)
            ->exists();
    }
}
