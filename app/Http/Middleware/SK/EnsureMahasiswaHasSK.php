<?php

namespace App\Http\Middleware\SK;

use App\Models\SuratKeputusan;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureMahasiswaHasSK
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Jika user adalah mahasiswa, cek apakah memiliki SK aktif
        if ($user->isMahasiswa()) {
            $hasActiveSK = SuratKeputusan::where('mahasiswa_id', $user->id)
                ->where('status', 'active')
                ->exists();

            if (! $hasActiveSK) {
                return redirect()->route('mahasiswa.dashboard')
                    ->with('warning', 'Anda belum memiliki Surat Keputusan (SK) Pembimbing yang aktif.
                            Silakan hubungi admin untuk mendapatkan SK.');
            }
        }

        return $next($request);
    }
}
