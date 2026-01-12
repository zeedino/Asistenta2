<?php

namespace App\Http\Middleware\SK;

use App\Models\Document;
use App\Models\Log;
use App\Models\Meeting;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureDosenSupervisesMahasiswa
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Hanya validasi jika user adalah dosen
        if ($user && $user->isDosen()) {
            $mahasiswaId = $this->getMahasiswaIdFromRequest($request);

            // Jika kita berhasil mendeteksi ID mahasiswa dari route/request
            if ($mahasiswaId) {
                // Cek Validasi ke Table Pivot (Sumber Kebenaran)
                $isSupervisor = DB::table('dosen_mahasiswa')
                    ->where('dosen_id', $user->id)
                    ->where('mahasiswa_id', $mahasiswaId)
                    // Kita tidak perlu cek sk_id spesifik, asalkan ada relasi aktif
                    ->exists();

                if (! $isSupervisor) {
                    // Jika Request Ajax/JSON (biar tidak redirect html error)
                    if ($request->expectsJson()) {
                        abort(403, 'Anda tidak memiliki akses legal SK untuk mahasiswa ini.');
                    }

                    return redirect()->route('dosen.dashboard')
                        ->with('warning', 'Akses Ditolak: Anda tidak terdaftar sebagai pembimbing mahasiswa tersebut dalam SK.');
                }
            }
        }

        return $next($request);
    }

    /**
     * Extract mahasiswa_id secara cerdas (Handle ID string atau Model Object)
     */
    private function getMahasiswaIdFromRequest(Request $request): ?int
    {
        // 1. Cek Route MEETINGS
        $meeting = $request->route('meeting');
        if ($meeting) {
            // Jika Route Model Binding aktif, ini adalah Objek
            if ($meeting instanceof Meeting) {
                return $meeting->mahasiswa_id;
            }
            // Jika binding belum jalan, ini adalah ID (string/int)
            $meetingModel = Meeting::find($meeting);

            return $meetingModel?->mahasiswa_id;
        }

        // 2. Cek Route DOCUMENTS
        $document = $request->route('document');
        if ($document) {
            if ($document instanceof Document) {
                return $document->user_id; // Asumsi user_id adalah mahasiswa
            }
            $docModel = Document::find($document);

            return $docModel?->user_id;
        }

        // 3. Cek Route LOGS
        $log = $request->route('log');
        if ($log) {
            if ($log instanceof Log) {
                return $log->mahasiswa_id;
            }
            $logModel = Log::find($log);

            return $logModel?->mahasiswa_id;
        }

        // 4. Cek Input Request (Fallback)
        return $request->input('mahasiswa_id')
            ?? $request->input('user_id')
            ?? $request->input('student_id');
    }
}
