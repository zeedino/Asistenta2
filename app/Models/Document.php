<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'meeting_id',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'category',
        'status',
        'dosen_feedback',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // ==================== CONSTANTS ====================
    const CATEGORIES = [
        'proposal' => 'Proposal',
        'draft' => 'Draft',
        'revisi' => 'Revisi',
        'laporan' => 'Laporan',
        'presentasi' => 'Presentasi',
        'final' => 'Final',
        'lainnya' => 'Lainnya',
    ];

    const STATUSES = [  // Opsional: tambahkan juga untuk status
        'draft' => 'Draft',
        'submitted' => 'Menunggu Review',
        'reviewed' => 'Telah Direview',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
    ];

    // Relationship dengan User (uploader)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship dengan Meeting
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
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

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByMeeting($query, $meetingId)
    {
        return $query->where('meeting_id', $meetingId);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
    // ==================== METHODS ====================

    public function canSubmit()
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    public function canEdit()
    {
        return $this->status === 'draft' || $this->status === 'rejected';
    }

    public function canReview()
    {
        return $this->status === 'submitted';
    }

    public function submit()
    {
        $this->update(['status' => 'submitted']);
    }

    public function approve($dosen_feedback = null)
    {
        $this->update([
            'status' => 'approved',
            'dosen_feedback' => $dosen_feedback,
            'reviewed_at' => now(),
        ]);
    }

    public function reject($dosen_feedback = null)
    {
        $this->update([
            'status' => 'rejected',
            'dosen_feedback' => $dosen_feedback,
            'reviewed_at' => now(),
        ]);
    }

    public function getStatusColor()
    {
        return match ($this->status) {
            'draft' => 'gray',
            'submitted' => 'yellow',
            'reviewed' => 'blue',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray'
        };
    }

    public function getStatusText()
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Menunggu Review',
            'reviewed' => 'Telah Direview',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Unknown'
        };
    }

    public function getCategoryText()
    {
        return match ($this->category) {
            'proposal' => 'Proposal',
            'draft' => 'Draft',
            'revisi' => 'Revisi',
            'laporan' => 'Laporan',
            'presentasi' => 'Presentasi',
            'final' => 'Final',
            'lainnya' => 'Lainnya',
            default => 'Tidak Diketahui'
        };
    }

    public function getFileSizeFormatted()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        } else {
            return $bytes.' bytes';
        }
    }
}
