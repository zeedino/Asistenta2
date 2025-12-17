<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'mahasiswa_id',
        'dosen_id',
        'activity_description',
        'progress',
        'obstacles',
        'next_plan',
        'status',
        'dosen_feedback',
        'validated_at',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    // Relationship dengan Meeting
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

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

    // ==================== SCOPES ====================

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeValidated($query)
    {
        return $query->where('status', 'validated');
    }

    public function scopeByMahasiswa($query, $mahasiswaId)
    {
        return $query->where('mahasiswa_id', $mahasiswaId);
    }

    public function scopeByDosen($query, $dosenId)
    {
        return $query->where('dosen_id', $dosenId);
    }

    // ==================== METHODS ====================

    public function canEdit()
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function canSubmit()
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function canValidate()
    {
        return $this->status === 'submitted';
    }

    public function submit()
    {
        $this->update(['status' => 'submitted']);
    }

    public function validateLog($feedback = null)
    {
        $this->update([
            'status' => 'validated',
            'dosen_feedback' => $feedback,
            'validated_at' => now(),
        ]);
    }

    public function rejectLog($feedback = null)
    {
        $this->update([
            'status' => 'rejected',
            'dosen_feedback' => $feedback,
        ]);
    }

    // Get status color untuk badge
    public function getStatusColor()
    {
        return match ($this->status) {
            'draft' => 'gray',
            'submitted' => 'yellow',
            'validated' => 'green',
            'rejected' => 'red',
            default => 'gray'
        };
    }

    // Get status text untuk display
    public function getStatusText()
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Menunggu Validasi',
            'validated' => 'Tervalidasi',
            'rejected' => 'Ditolak',
            default => 'Unknown'
        };
    }
}
