<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'dosen_mahasiswa';

    protected $fillable = [
        'dosen_id',
        'mahasiswa_id',
        'sk_id',
        'posisi',
    ];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function suratKeputusan()
    {
        return $this->belongsTo(SuratKeputusan::class, 'sk_id');
    }

    /**
     * Alias untuk relasi SK, agar Blade bisa memakai $pivot->sk
     */
    public function sk()
    {
        return $this->belongsTo(SuratKeputusan::class, 'sk_id');
    }

    /**
     * Accessor supaya $pivot->sk tetap aman diakses
     */
    public function getSkAttribute()
    {
        return $this->suratKeputusan;
    }

    public function scopePembimbing1($query)
    {
        return $query->where('posisi', 'Pembimbing 1');
    }

    public function scopePembimbing2($query)
    {
        return $query->where('posisi', 'Pembimbing 2');
    }

    public function scopeByDosen($query, $dosenId)
    {
        return $query->where('dosen_id', $dosenId);
    }

    public function scopeByMahasiswa($query, $mahasiswaId)
    {
        return $query->where('mahasiswa_id', $mahasiswaId);
    }

    public function isPembimbing1()
    {
        return $this->posisi === 'Pembimbing 1';
    }

    public function isPembimbing2()
    {
        return $this->posisi === 'Pembimbing 2';
    }
}
