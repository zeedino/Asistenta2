<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Meeting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // ✅ TAMBAHKAN IMPORT INI

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isMahasiswa()) {
            // ✅ Hanya meeting milik sendiri (middleware sk.mahasiswa sudah handle SK)
            $meetings = Meeting::with('dosen', 'availability')
                ->where('mahasiswa_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

        } elseif ($user->isDosen()) {
            // ✅ HANYA meeting mahasiswa bimbingan
            // Gunakan method baru dari Model User
            $mahasiswaIds = $user->mahasiswaBimbinganAktifIds();

            $meetings = Meeting::with('mahasiswa', 'availability')
                ->where('dosen_id', $user->id)
                ->whereIn('mahasiswa_id', $mahasiswaIds) // ✅ FILTER INI
                ->orderBy('created_at', 'desc')
                ->get();

        } else {
            // Admin bisa lihat semua
            $meetings = Meeting::with('mahasiswa', 'dosen', 'availability')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('meetings.index', compact('meetings'));
    }

    /**
     * Store a newly created meeting request.
     */
    public function store(Request $request)
    {
        \Log::info('=== MEETING STORE METHOD STARTED ===');
        \Log::info('Request data:', $request->all());

        try {
            $user = Auth::user();

            // ✅ Middleware sudah handle:
            // 1. auth (user login)
            // 2. check.role:mahasiswa (user adalah mahasiswa)
            // 3. sk.mahasiswa (mahasiswa punya SK aktif)

            // ❌ BISA HAPUS validasi redundant ini:
            // if (! $user) { ... }
            // if (! $user->isMahasiswa()) { ... }

            $request->validate([
                'availability_id' => 'required|exists:availabilities,id',
                'title' => 'required|string|max:255',
                'agenda' => 'required|string|min:10',
            ]);

            // Check if availability exists and is available
            $availability = Availability::find($request->availability_id);

            if (! $availability) {
                return redirect()->back()
                    ->with('error', 'Jadwal tidak ditemukan.')
                    ->withInput();
            }

            /**
             * Kita cek langsung ke tabel 'dosen_mahasiswa' dan 'surat_keputusan'
             */
            $isDosenPembimbing = DB::table('dosen_mahasiswa')
                ->join('surat_keputusan', 'dosen_mahasiswa.sk_id', '=', 'surat_keputusan.id')
                ->where('dosen_mahasiswa.mahasiswa_id', $user->id)           // Cek Mahasiswa Login
                ->where('dosen_mahasiswa.dosen_id', $availability->dosen_id) // Cek Dosen Pemilik Jadwal
                ->where('surat_keputusan.status', 'active')                  // Cek SK Aktif
                ->exists();

            if (! $isDosenPembimbing) {
                return redirect()->back()
                    ->with('error', 'Anda hanya bisa request meeting dengan dosen pembimbing Anda (sesuai SK aktif).')
                    ->withInput();
            }

            // ✅ DEBUG DETAIL: Cek tipe data dan nilai
            \Log::info('Availability debug:', [
                'date' => $availability->date,
                'date_type' => gettype($availability->date),
                'start_time' => $availability->start_time,
                'start_time_type' => gettype($availability->start_time),
            ]);

            if ($availability->status !== 'available') {
                return redirect()->back()
                    ->with('error', 'Jadwal sudah tidak tersedia.')
                    ->withInput();
            }

            // Check if user already has a pending meeting with this availability
            $existingMeeting = Meeting::where('mahasiswa_id', $user->id)
                ->where('availability_id', $request->availability_id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->first();

            if ($existingMeeting) {
                return redirect()->back()
                    ->with('error', 'Anda sudah mengajukan bimbingan pada jadwal ini.')
                    ->withInput();
            }

            // ✅ PERBAIKAN: Format meeting_date yang benar
            $meetingDate = null;

            // Coba beberapa format sampai berhasil
            try {
                // Coba format 1: Gabung date dan start_time
                if (is_string($availability->date) && is_string($availability->start_time)) {
                    $meetingDate = $availability->date.' '.$availability->start_time;
                    \Log::info('Format 1 - String concatenation:', ['meeting_date' => $meetingDate]);
                }
                // Coba format 2: Jika date adalah Carbon object
                elseif ($availability->date instanceof \Carbon\Carbon) {
                    $meetingDate = $availability->date->format('Y-m-d').' '.$availability->start_time;
                    \Log::info('Format 2 - Carbon object:', ['meeting_date' => $meetingDate]);
                }

                // Validasi format datetime
                if ($meetingDate && ! strtotime($meetingDate)) {
                    \Log::warning('Invalid datetime format, setting to null');
                    $meetingDate = null;
                }

            } catch (\Exception $e) {
                \Log::warning('Datetime parsing failed, setting to null', ['error' => $e->getMessage()]);
                $meetingDate = null;
            }

            \Log::info('Final meeting_date:', ['meeting_date' => $meetingDate]);

            // Create meeting request
            $meeting = Meeting::create([
                'mahasiswa_id' => $user->id,
                'dosen_id' => $availability->dosen_id,
                'availability_id' => $availability->id,
                'title' => $request->title,
                'agenda' => $request->agenda,
                'status' => 'pending',
                'meeting_date' => $meetingDate, // Bisa null sementara
            ]);

            // Update availability status to booked
            $availability->update(['status' => 'booked']);

            \Log::info('Meeting created successfully', ['meeting_id' => $meeting->id]);

            return redirect()->route('meetings.index')
                ->with('success', 'Request bimbingan berhasil dikirim! Menunggu konfirmasi dosen.');

        } catch (\Exception $e) {
            \Log::error('Meeting store error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified meeting.
     */
    public function show(Meeting $meeting)
    {
        $user = Auth::user();

        // Authorization check
        if ($user->isMahasiswa()) {
            // ✅ Hanya bisa lihat meeting sendiri
            if ($meeting->mahasiswa_id !== $user->id) {
                abort(403, 'Unauthorized access.');
            }
        } elseif ($user->isDosen()) {
            // ✅ Hanya bisa lihat meeting mahasiswa bimbingan
            if ($meeting->dosen_id !== $user->id) {
                abort(403, 'Ini bukan meeting Anda.');
            }

            // Cek apakah dosen membimbing mahasiswa ini
            $isSupervisor = DB::table('dosen_mahasiswa')
                ->where('dosen_id', $user->id)
                ->where('mahasiswa_id', $meeting->mahasiswa_id)
                ->exists();

            if (! $isSupervisor) {
                abort(403, 'Anda tidak membimbing mahasiswa ini.');
            }
        } else {
            // Admin selalu bisa akses
            if (! $user->isAdmin()) {
                abort(403, 'Unauthorized access.');
            }
        }

        $meeting->load('mahasiswa', 'dosen', 'availability');

        return view('meetings.show', compact('meeting'));
    }

    /**
     * Update meeting status (confirm/reject).
     */
    public function updateStatus(Request $request, Meeting $meeting)
    {
        $user = Auth::user();

        // ✅ Middleware 'sk.dosen' sudah handle validasi relasi
        // Tapi kita tetap cek untuk safety
        if ($meeting->dosen_id !== $user->id) {
            abort(403, 'Hanya dosen pembimbing yang dapat mengkonfirmasi bimbingan.');
        }

        $request->validate([
            'status' => 'required|in:confirmed,rejected',
            'dosen_notes' => 'nullable|string|max:1000',
        ]);

        $meeting->update([
            'status' => $request->status,
            'dosen_notes' => $request->dosen_notes,
        ]);

        // If rejected, make availability available again
        if ($request->status === 'rejected') {
            $meeting->availability->update(['status' => 'available']);
        }

        $statusText = $request->status === 'confirmed' ? 'dikonfirmasi' : 'ditolak';

        return redirect()->route('meetings.index')
            ->with('success', "Bimbingan berhasil {$statusText}.");
    }

    /**
     * Cancel meeting request (mahasiswa).
     */
    public function cancel(Meeting $meeting)
    {
        $user = Auth::user();

        // ✅ Middleware 'sk.mahasiswa' sudah handle validasi mahasiswa
        if ($meeting->mahasiswa_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        if (! in_array($meeting->status, ['pending', 'confirmed'])) {
            return redirect()->back()
                ->with('error', 'Tidak dapat membatalkan bimbingan dengan status ini.');
        }

        // Make availability available again
        $meeting->availability->update(['status' => 'available']);

        $meeting->update([
            'status' => 'cancelled',
            'mahasiswa_notes' => 'Dibatalkan oleh mahasiswa',
        ]);

        return redirect()->route('meetings.index')
            ->with('success', 'Bimbingan berhasil dibatalkan.');
    }

    /**
     * Complete meeting (dosen).
     */
    public function complete(Meeting $meeting)
    {
        $user = Auth::user();

        // ✅ Middleware 'sk.dosen' sudah handle validasi relasi
        if ($meeting->dosen_id !== $user->id) {
            abort(403, 'Hanya dosen pembimbing yang dapat menyelesaikan bimbingan.');
        }

        if ($meeting->status !== 'confirmed') {
            return redirect()->back()
                ->with('error', 'Hanya bimbingan yang sudah dikonfirmasi yang dapat diselesaikan.');
        }

        $meeting->update(['status' => 'completed']);

        return redirect()->route('meetings.index')
            ->with('success', 'Bimbingan berhasil ditandai sebagai selesai.');
    }

    /**
     * Get meetings for calendar view.
     */
    public function calendar()
    {
        $user = Auth::user();

        if ($user->isMahasiswa()) {
            $meetings = Meeting::where('mahasiswa_id', $user->id)
                ->whereIn('status', ['confirmed', 'completed'])
                ->with('dosen')
                ->get();

        } elseif ($user->isDosen()) {
            // ✅ HANYA meeting mahasiswa bimbingan
            $mahasiswaIds = $user->mahasiswaBimbinganAktifIds();

            $meetings = Meeting::where('dosen_id', $user->id)
                ->whereIn('mahasiswa_id', $mahasiswaIds) // ✅ FILTER INI
                ->whereIn('status', ['confirmed', 'completed'])
                ->with('mahasiswa')
                ->get();

        } else {
            $meetings = Meeting::whereIn('status', ['confirmed', 'completed'])
                ->with('mahasiswa', 'dosen')
                ->get();
        }

        $events = [];
        foreach ($meetings as $meeting) {
            // Determine event title based on user role
            if ($user->isMahasiswa()) {
                $title = $meeting->title.' - '.$meeting->dosen->username;
            } elseif ($user->isDosen()) {
                $title = $meeting->title.' - '.$meeting->mahasiswa->username;
            } else {
                $title = $meeting->title.' - '.$meeting->mahasiswa->username.' & '.$meeting->dosen->username;
            }

            // Determine event color based on status
            $color = $meeting->status === 'completed' ? '#10B981' : '#3B82F6'; // Green for completed, Blue for confirmed

            $events[] = [
                'title' => $title,
                'start' => $meeting->meeting_date ?? $meeting->created_at->format('Y-m-d H:i:s'),
                'end' => $meeting->meeting_date ?
                         \Carbon\Carbon::parse($meeting->meeting_date)->addHour()->format('Y-m-d H:i:s') :
                         \Carbon\Carbon::parse($meeting->created_at)->addHour()->format('Y-m-d H:i:s'),
                'color' => $color,
                'url' => route('meetings.show', $meeting),
                'extendedProps' => [
                    'description' => $meeting->agenda,
                    'status' => $meeting->status,
                    'created_at' => $meeting->created_at->format('d M Y H:i'),
                ],
            ];
        }

        return view('meetings.calendar', compact('events'));
    }
}
