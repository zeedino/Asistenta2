<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'dosen_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    // Relationship dengan User (Dosen)
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    // Relationship dengan Meetings
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    // Scope untuk availability yang available
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Scope untuk availability berdasarkan dosen
    public function scopeByDosen($query, $dosenId)
    {
        return $query->where('dosen_id', $dosenId);
    }
}
