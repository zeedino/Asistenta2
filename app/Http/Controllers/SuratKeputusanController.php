<?php

namespace App\Http\Controllers;

use App\Models\DosenMahasiswa;
use App\Models\SuratKeputusan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SuratKeputusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sks = SuratKeputusan::with(['mahasiswa', 'admin', 'dosenPembimbing'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('surat-keputusan.index', compact('sks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua mahasiswa aktif
        $mahasiswas = User::where('role', 'mahasiswa')
            ->where('status', 'aktif')
            ->orderBy('username')
            ->get();

        // Ambil semua dosen aktif
        $dosens = User::where('role', 'dosen')
            ->where('status', 'aktif')
            ->orderBy('username')
            ->get();

        // Generate nomor SK otomatis
        $lastSK = SuratKeputusan::orderBy('id', 'desc')->first();
        $nextNumber = $lastSK ? (int) explode('/', $lastSK->nomor_sk)[0] + 1 : 1;
        $nomorSK = sprintf('%03d', $nextNumber).'/SK/TI/'.date('Y');

        // Tahun akademik options
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $tahunAkademikOptions = [
            ($currentYear - 1).'/'.$currentYear,
            $currentYear.'/'.$nextYear,
            ($currentYear + 1).'/'.($currentYear + 2),
        ];

        return view('surat-keputusan.create', compact(
            'mahasiswas',
            'dosens',
            'nomorSK',
            'tahunAkademikOptions'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_sk' => 'required|string|max:100|unique:surat_keputusan',
            'tanggal_sk' => 'required|date',
            'tahun_akademik' => 'required|string|max:20',
            'semester' => 'required|in:Ganjil,Genap',
            'mahasiswa_id' => 'required|exists:users,id',
            'pembimbing1_dosen_id' => 'required|exists:users,id',
            'pembimbing2_dosen_id' => 'required|exists:users,id',
            'file_sk' => 'nullable|file|mimes:pdf|max:10240',
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Validasi tambahan: Pastikan pembimbing 1 dan 2 berbeda
        if ($request->pembimbing1_dosen_id == $request->pembimbing2_dosen_id) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pembimbing 1 dan Pembimbing 2 harus orang yang berbeda.');
        }

        try {
            DB::transaction(function () use ($request) {

                // 1. Handle File Upload
                $filePath = null;
                if ($request->hasFile('file_sk')) {
                    $file = $request->file('file_sk');
                    $fileName = 'SK_'.time().'_'.$file->getClientOriginalName();
                    $filePath = $file->storeAs('surat_keputusan', $fileName, 'public');
                }

                // 2. Create SK Record
                $sk = SuratKeputusan::create([
                    'nomor_sk' => $request->nomor_sk,
                    'tanggal_sk' => $request->tanggal_sk,
                    'tahun_akademik' => $request->tahun_akademik,
                    'semester' => $request->semester,
                    'mahasiswa_id' => $request->mahasiswa_id,
                    'admin_id' => Auth::id(),
                    'file_sk' => $filePath,
                    'keterangan' => $request->keterangan,
                    'status' => 'active', // Set langsung active
                ]);

                // 3. Attach Pembimbing 1
                DosenMahasiswa::create([
                    'dosen_id' => $request->pembimbing1_dosen_id,
                    'mahasiswa_id' => $request->mahasiswa_id,
                    'sk_id' => $sk->id,
                    'posisi' => 'Pembimbing 1',
                ]);

                // 4. Attach Pembimbing 2
                DosenMahasiswa::create([
                    'dosen_id' => $request->pembimbing2_dosen_id,
                    'mahasiswa_id' => $request->mahasiswa_id,
                    'sk_id' => $sk->id,
                    'posisi' => 'Pembimbing 2',
                ]);
            });

            return redirect()->route('admin.surat-keputusan.index')
                ->with('success', 'Surat Keputusan berhasil dibuat dan Pembimbing telah ditetapkan!');

        } catch (\Exception $e) {
            // Jika error, kembali ke form dengan pesan error
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SuratKeputusan $suratKeputusan)
    {
        $suratKeputusan->load(['mahasiswa', 'admin', 'dosenPembimbing', 'dosenMahasiswa']);

        return view('surat-keputusan.show', compact('suratKeputusan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratKeputusan $suratKeputusan)
    {
        $mahasiswas = User::where('role', 'mahasiswa')
            ->where('status', 'aktif')
            ->orderBy('username')
            ->get();

        $dosens = User::where('role', 'dosen')
            ->where('status', 'aktif')
            ->orderBy('username')
            ->get();

        $tahunAkademikOptions = [
            '2023/2024',
            '2024/2025',
            '2025/2026',
            '2026/2027',
        ];

        // Get existing pembimbing assignments
        $existingPembimbing1 = $suratKeputusan->dosenMahasiswa()
            ->where('posisi', 'Pembimbing 1')
            ->first();

        $existingPembimbing2 = $suratKeputusan->dosenMahasiswa()
            ->where('posisi', 'Pembimbing 2')
            ->first();

        return view('surat-keputusan.edit', compact(
            'suratKeputusan',
            'mahasiswas',
            'dosens',
            'tahunAkademikOptions',
            'existingPembimbing1',
            'existingPembimbing2'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuratKeputusan $suratKeputusan)
    {
        $request->validate([
            'nomor_sk' => 'required|string|max:100|unique:surat_keputusan,nomor_sk,'.$suratKeputusan->id,
            'tanggal_sk' => 'required|date',
            'tahun_akademik' => 'required|string|max:20',
            'semester' => 'required|in:Ganjil,Genap',
            'mahasiswa_id' => 'required|exists:users,id',
            'pembimbing1_dosen_id' => 'required|exists:users,id',
            'pembimbing2_dosen_id' => 'required|exists:users,id',
            'file_sk' => 'nullable|file|mimes:pdf|max:10240',
            'keterangan' => 'nullable|string|max:500',
        ]);

        if ($request->pembimbing1_dosen_id == $request->pembimbing2_dosen_id) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pembimbing 1 dan Pembimbing 2 harus orang yang berbeda.');
        }

        try {
            DB::transaction(function () use ($request, $suratKeputusan) {

                // 1. Handle File Upload (Simpan path baru dulu)
                $newFilePath = null;
                if ($request->hasFile('file_sk')) {
                    $file = $request->file('file_sk');
                    $fileName = 'SK_'.time().'_'.$file->getClientOriginalName();
                    $newFilePath = $file->storeAs('surat_keputusan', $fileName, 'public');
                }

                // 2. Update SK Record
                $updateData = [
                    'nomor_sk' => $request->nomor_sk,
                    'tanggal_sk' => $request->tanggal_sk,
                    'tahun_akademik' => $request->tahun_akademik,
                    'semester' => $request->semester,
                    'mahasiswa_id' => $request->mahasiswa_id,
                    'keterangan' => $request->keterangan,
                ];

                // Hanya update file_sk jika ada file baru
                if ($newFilePath) {
                    // Hapus file lama jika ada
                    if ($suratKeputusan->file_sk && Storage::disk('public')->exists($suratKeputusan->file_sk)) {
                        Storage::disk('public')->delete($suratKeputusan->file_sk);
                    }
                    $updateData['file_sk'] = $newFilePath;
                }

                $suratKeputusan->update($updateData);

                // 3. Update Pembimbing (Delete Old & Create New)
                // Hapus semua assignment lama untuk SK ini
                $suratKeputusan->dosenMahasiswa()->delete();

                // Create Pembimbing 1 Baru
                DosenMahasiswa::create([
                    'dosen_id' => $request->pembimbing1_dosen_id,
                    'mahasiswa_id' => $request->mahasiswa_id,
                    'sk_id' => $suratKeputusan->id,
                    'posisi' => 'Pembimbing 1',
                ]);

                // Create Pembimbing 2 Baru
                DosenMahasiswa::create([
                    'dosen_id' => $request->pembimbing2_dosen_id,
                    'mahasiswa_id' => $request->mahasiswa_id,
                    'sk_id' => $suratKeputusan->id,
                    'posisi' => 'Pembimbing 2',
                ]);
            });

            return redirect()->route('admin.surat-keputusan.index')
                ->with('success', 'Surat Keputusan berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuratKeputusan $suratKeputusan)
    {
        try {
            DB::transaction(function () use ($suratKeputusan) {
                // 1. Hapus Relasi Dosen Mahasiswa (Pivot)
                $suratKeputusan->dosenMahasiswa()->delete();

                // 2. Hapus File Fisik
                if ($suratKeputusan->file_sk && Storage::disk('public')->exists($suratKeputusan->file_sk)) {
                    Storage::disk('public')->delete($suratKeputusan->file_sk);
                }

                // 3. Hapus Record SK
                $suratKeputusan->delete();
            });

            return redirect()->route('admin.surat-keputusan.index')
                ->with('success', 'Surat Keputusan berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }

    /**
     * Download SK file
     */
    public function download(SuratKeputusan $suratKeputusan)
    {
        if (! $suratKeputusan->file_sk || ! Storage::disk('public')->exists($suratKeputusan->file_sk)) {
            return redirect()->back()->with('error', 'File SK tidak ditemukan di server.');
        }

        $nomorSkSafe = str_replace(['/', '\\'], '-', $suratKeputusan->nomor_sk);
        $fileName = 'SK_'.$nomorSkSafe.'.pdf';

        return Storage::disk('public')->download($suratKeputusan->file_sk, $fileName);
    }
}
