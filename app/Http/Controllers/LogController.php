<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // ✅ Pastikan ini ada

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isMahasiswa()) {
            // Mahasiswa: Lihat log sendiri
            // ✅ Middleware sk.mahasiswa sudah handle validasi SK, tapi query tetap specific user
            $logs = Log::with('dosen', 'meeting')
                ->where('mahasiswa_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

        } elseif ($user->isDosen()) {
            // ✅ FIX: Query Eksplisit Mahasiswa Bimbingan dari Pivot & SK
            // Menggantikan $user->mahasiswaBimbinganAktifIds() yang bermasalah
            $mahasiswaIds = DB::table('dosen_mahasiswa')
                ->join('surat_keputusan', 'dosen_mahasiswa.sk_id', '=', 'surat_keputusan.id')
                ->where('dosen_mahasiswa.dosen_id', $user->id)
                ->where('surat_keputusan.status', 'active')
                ->pluck('dosen_mahasiswa.mahasiswa_id');

            $logs = Log::with('mahasiswa', 'meeting')
                ->where('dosen_id', $user->id)
                ->whereIn('mahasiswa_id', $mahasiswaIds) // ✅ Filter Log hanya dari mahasiswa valid
                ->orderBy('created_at', 'desc')
                ->get();

        } else {
            // Admin bisa lihat semua
            $logs = Log::with('mahasiswa', 'dosen', 'meeting')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('logs.index', compact('logs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // ✅ Middleware sk.mahasiswa sudah handle validasi mahasiswa
        if (! $user->isMahasiswa()) {
            abort(403, 'Hanya mahasiswa yang dapat membuat logbook.');
        }

        // Get completed/confirmed meetings that don't have logs yet
        // ✅ HANYA meeting mahasiswa sendiri
        $meetings = Meeting::with('dosen')
            ->where('mahasiswa_id', $user->id)
            ->whereIn('status', ['completed', 'confirmed'])
            ->whereDoesntHave('logs')
            ->orderBy('meeting_date', 'desc')
            ->get();

        return view('logs.create', compact('meetings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user->isMahasiswa()) {
            abort(403, 'Hanya mahasiswa yang dapat membuat logbook.');
        }

        $request->validate([
            'meeting_id' => 'required|exists:meetings,id',
            'activity_description' => 'required|string|min:10',
            'progress' => 'required|string|min:10',
            'obstacles' => 'nullable|string',
            'next_plan' => 'nullable|string',
        ]);

        // Cek apakah meeting milik mahasiswa dan sudah selesai
        $meeting = Meeting::find($request->meeting_id);

        if ($meeting->mahasiswa_id !== $user->id) {
            abort(403, 'Unauthorized access to this meeting.');
        }

        // ✅ TAMBAHKAN VALIDASI: Meeting harus dari dosen pembimbing (Query Eksplisit)
        $isValidMeeting = DB::table('dosen_mahasiswa')
            ->join('surat_keputusan', 'dosen_mahasiswa.sk_id', '=', 'surat_keputusan.id')
            ->where('dosen_mahasiswa.dosen_id', $meeting->dosen_id)
            ->where('dosen_mahasiswa.mahasiswa_id', $user->id)
            ->where('surat_keputusan.status', 'active')
            ->exists();

        if (! $isValidMeeting) {
            abort(403, 'Meeting ini tidak dari dosen pembimbing Anda yang aktif.');
        }

        if (! $meeting->canCreateLog()) {
            return redirect()->back()
                ->with('error', 'Hanya bisa membuat log untuk bimbingan yang sudah selesai atau dikonfirmasi.')
                ->withInput();
        }

        // Cek apakah sudah ada log untuk meeting ini
        $existingLog = Log::where('meeting_id', $request->meeting_id)->first();
        if ($existingLog) {
            return redirect()->back()
                ->with('error', 'Logbook untuk bimbingan ini sudah ada.')
                ->withInput();
        }

        // Create log
        Log::create([
            'meeting_id' => $request->meeting_id,
            'mahasiswa_id' => $user->id,
            'dosen_id' => $meeting->dosen_id,
            'activity_description' => $request->activity_description,
            'progress' => $request->progress,
            'obstacles' => $request->obstacles,
            'next_plan' => $request->next_plan,
            'status' => 'draft',
        ]);

        return redirect()->route('logs.index')
            ->with('success', 'Logbook berhasil dibuat! Anda bisa submit setelah mengedit jika perlu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Log $log)
    {
        $user = Auth::user();

        if ($user->isMahasiswa()) {
            // ✅ Hanya bisa lihat log sendiri
            if ($log->mahasiswa_id !== $user->id) {
                abort(403, 'Unauthorized access.');
            }
        } elseif ($user->isDosen()) {
            // ✅ Hanya bisa lihat log mahasiswa bimbingan
            if ($log->dosen_id !== $user->id) {
                abort(403, 'Ini bukan logbook untuk Anda.');
            }

            // ✅ FIX: Validasi SK Aktif di halaman Show (Eksplisit)
            $isSupervisor = DB::table('dosen_mahasiswa')
                ->join('surat_keputusan', 'dosen_mahasiswa.sk_id', '=', 'surat_keputusan.id')
                ->where('dosen_mahasiswa.dosen_id', $user->id)
                ->where('dosen_mahasiswa.mahasiswa_id', $log->mahasiswa_id)
                ->where('surat_keputusan.status', 'active')
                ->exists();

            if (! $isSupervisor) {
                abort(403, 'Anda tidak memiliki SK aktif untuk membimbing mahasiswa ini.');
            }

        } elseif (! $user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $log->load('mahasiswa', 'dosen', 'meeting');

        return view('logs.show', compact('log'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Log $log)
    {
        $user = Auth::user();

        // ✅ Middleware sk.mahasiswa sudah handle validasi mahasiswa
        if ($log->mahasiswa_id !== $user->id) {
            abort(403, 'Hanya pembuat log yang dapat mengedit.');
        }

        if (! $log->canEdit()) {
            return redirect()->route('logs.show', $log)
                ->with('error', 'Logbook sudah disubmit dan tidak dapat diedit.');
        }

        return view('logs.edit', compact('log'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Log $log)
    {
        $user = Auth::user();

        // ✅ Middleware sk.mahasiswa sudah handle validasi mahasiswa
        if ($log->mahasiswa_id !== $user->id) {
            abort(403, 'Hanya pembuat log yang dapat mengedit.');
        }

        if (! $log->canEdit()) {
            return redirect()->route('logs.show', $log)
                ->with('error', 'Logbook sudah disubmit dan tidak dapat diedit.');
        }

        $request->validate([
            'activity_description' => 'required|string|min:10',
            'progress' => 'required|string|min:10',
            'obstacles' => 'nullable|string',
            'next_plan' => 'nullable|string',
        ]);

        // Update log data
        $log->update([
            'activity_description' => $request->activity_description,
            'progress' => $request->progress,
            'obstacles' => $request->obstacles,
            'next_plan' => $request->next_plan,
        ]);

        // Check if user wants to submit
        if ($request->action === 'submit') {
            $log->submit();

            return redirect()->route('logs.index')
                ->with('success', 'Logbook berhasil diperbarui dan disubmit! Menunggu validasi dosen.');
        }

        return redirect()->route('logs.index')
            ->with('success', 'Logbook berhasil diperbarui!');
    }

    /**
     * Submit log for validation
     */
    public function submit(Log $log)
    {
        $user = Auth::user();

        // ✅ Middleware sk.mahasiswa sudah handle validasi mahasiswa
        if ($log->mahasiswa_id !== $user->id) {
            abort(403, 'Hanya pembuat log yang dapat submit.');
        }

        if (! $log->canSubmit()) {
            return redirect()->route('logs.show', $log)
                ->with('error', 'Logbook sudah disubmit sebelumnya.');
        }

        $log->submit();

        return redirect()->route('logs.index')
            ->with('success', 'Logbook berhasil disubmit! Menunggu validasi dosen.');
    }

    /**
     * Validate log (dosen only)
     */
    public function validateLog(Request $request, Log $log)
    {
        $user = Auth::user();

        // ✅ Middleware sk.dosen sudah handle validasi relasi via ID
        // Tapi kita tetap cek ownership
        if (! $user->isDosen() || $log->dosen_id !== $user->id) {
            abort(403, 'Hanya dosen pembimbing yang dapat memvalidasi logbook.');
        }

        if (! $log->canValidate()) {
            return redirect()->route('logs.show', $log)
                ->with('error', 'Logbook tidak dapat divalidasi.');
        }

        $request->validate([
            'dosen_feedback' => 'nullable|string|max:1000',
        ]);

        $log->validateLog($request->dosen_feedback);

        return redirect()->route('logs.index')
            ->with('success', 'Logbook berhasil divalidasi!');
    }

    /**
     * Reject log (dosen only)
     */
    public function reject(Request $request, Log $log)
    {
        $user = Auth::user();

        if (! $user->isDosen() || $log->dosen_id !== $user->id) {
            abort(403, 'Hanya dosen pembimbing yang dapat menolak logbook.');
        }

        if (! $log->canValidate()) {
            return redirect()->route('logs.show', $log)
                ->with('error', 'Logbook tidak dapat ditolak.');
        }

        $request->validate([
            'dosen_feedback' => 'required|string|min:5|max:1000',
        ]);

        $log->rejectLog($request->dosen_feedback);

        return redirect()->route('logs.index')
            ->with('success', 'Logbook berhasil ditolak. Mahasiswa dapat memperbaiki dan submit ulang.');
    }

    /**
     * Display validation queue for dosen
     */
    public function validationIndex()
    {
        $user = Auth::user();

        if (! $user->isDosen()) {
            abort(403, 'Hanya dosen yang dapat mengakses halaman validasi.');
        }

        // ✅ FIX: Query Eksplisit untuk Antrian Validasi
        // Mengambil hanya mahasiswa yang terkait via SK Aktif
        $mahasiswaIds = DB::table('dosen_mahasiswa')
            ->join('surat_keputusan', 'dosen_mahasiswa.sk_id', '=', 'surat_keputusan.id')
            ->where('dosen_mahasiswa.dosen_id', $user->id)
            ->where('surat_keputusan.status', 'active')
            ->pluck('dosen_mahasiswa.mahasiswa_id');

        $submittedLogs = Log::with('mahasiswa', 'meeting')
            ->where('dosen_id', $user->id)
            ->whereIn('mahasiswa_id', $mahasiswaIds) // ✅ Filter aman
            ->where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->get();

        $validatedLogs = Log::with('mahasiswa', 'meeting')
            ->where('dosen_id', $user->id)
            ->whereIn('mahasiswa_id', $mahasiswaIds) // ✅ Filter aman
            ->whereIn('status', ['validated', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('logs.validation-index', compact('submittedLogs', 'validatedLogs'));
    }
}
