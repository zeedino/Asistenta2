<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'availability_id',
        'title',
        'agenda',
        'status',
        'dosen_notes',
        'mahasiswa_notes',
        'meeting_date',
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
    ];

    // Relationship dengan User (Mahasiswa)
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    // Relationship dengan User (Dosen)
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    // Relationship dengan Availability
    public function availability()
    {
        return $this->belongsTo(Availability::class);
    }

    // Relationship dengan Logs
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    // Relationship dengan Documents
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Scope untuk meetings berdasarkan status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeByMahasiswa($query, $mahasiswaId)
    {
        return $query->where('mahasiswa_id', $mahasiswaId);
    }

    public function scopeByDosen($query, $dosenId)
    {
        return $query->where('dosen_id', $dosenId);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function canCreateLog()
    {
        return $this->status === 'completed' || $this->status === 'confirmed';
    }
}
