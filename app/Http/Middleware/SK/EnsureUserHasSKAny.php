<?php

namespace App\Http\Middleware\SK;

use App\Models\SuratKeputusan;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // ✅ Pastikan Import DB Facade ada
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasSKAny
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        /**
         * =========================
         * MAHASISWA
         * =========================
         */
        if ($user->isMahasiswa()) {
            // Mahasiswa biasanya punya kolom mahasiswa_id di tabel SK (One to One/Many)
            // Atau jika via pivot juga, sesuaikan query ini.
            // Asumsi: SK terikat langsung ke mahasiswa_id di tabel surat_keputusan
            $hasActiveSK = SuratKeputusan::where('mahasiswa_id', $user->id)
                ->where('status', 'active')
                ->exists();

            if (! $hasActiveSK) {
                return redirect()
                    ->route('mahasiswa.dashboard')
                    ->with('warning', 'Anda belum memiliki SK Pembimbing aktif.');
            }
        }

        /**
         * =========================
         * DOSEN
         * =========================
         */
        if ($user->isDosen()) {
            // Logika: Cari di tabel pivot apakah dosen ini punya SK yang statusnya 'active'
            $hasActiveBimbingan = DB::table('dosen_mahasiswa')
                ->join('surat_keputusan', 'dosen_mahasiswa.sk_id', '=', 'surat_keputusan.id')
                ->where('dosen_mahasiswa.dosen_id', $user->id)
                ->where('surat_keputusan.status', 'active')
                ->exists();

            if (! $hasActiveBimbingan) {
                return redirect()
                    ->route('dosen.dashboard')
                    ->with('warning', 'Anda belum memiliki mahasiswa bimbingan aktif berdasarkan SK.');
            }
        }

        return $next($request);
    }
}
