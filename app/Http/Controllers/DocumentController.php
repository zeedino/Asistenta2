<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // ✅ TAMBAHKAN IMPORT INI
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isMahasiswa()) {
            // ✅ Middleware sk.mahasiswa sudah handle validasi SK
            $documents = Document::with(['user', 'meeting'])
                ->where(function ($query) use ($user) {
                    // ✅ DOKUMEN MILIK MAHASISWA SENDIRI
                    $query->where('user_id', $user->id)
                          // ✅ ATAU DOKUMEN DOSEN PEMBIMBINGNYA
                        ->orWhere(function ($q) use ($user) {
                            $q->whereHas('user', function ($userQuery) {
                                $userQuery->where('role', 'dosen');
                            })
                                ->whereHas('meeting', function ($meetingQuery) use ($user) {
                                    $meetingQuery->where('mahasiswa_id', $user->id);
                                });
                        });
                })
                ->orderBy('created_at', 'desc')
                ->get();

        } elseif ($user->isDosen()) {
            // ✅ HANYA dokumen mahasiswa bimbingan + dokumen sendiri
            $mahasiswaIds = $user->mahasiswaBimbinganAktifIds();

            $documents = Document::with(['user', 'meeting'])
                ->where(function ($query) use ($user, $mahasiswaIds) {
                    // DOKUMEN MILIK DOSEN SENDIRI
                    $query->where('user_id', $user->id)
                          // ATAU DOKUMEN MAHASISWA BIMBINGANNYA
                        ->orWhere(function ($q) use ($mahasiswaIds) {
                            $q->whereHas('user', function ($userQuery) {
                                $userQuery->where('role', 'mahasiswa');
                            })
                                ->whereIn('user_id', $mahasiswaIds); // ✅ FILTER INI
                        });
                })
                ->orderBy('created_at', 'desc')
                ->get();

        } else {
            // Admin bisa lihat semua
            $documents = Document::with('user', 'meeting')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        // Get meetings yang bisa di-link
        if ($user->isMahasiswa()) {
            // ✅ Middleware sk.mahasiswa sudah handle validasi SK
            $meetings = Meeting::with('dosen')
                ->where('mahasiswa_id', $user->id)
                ->whereIn('status', ['confirmed', 'completed'])
                ->orderBy('meeting_date', 'desc')
                ->get();

        } elseif ($user->isDosen()) {
            // ✅ HANYA meeting mahasiswa bimbingan
            $mahasiswaIds = $user->mahasiswaBimbinganAktifIds();

            $meetings = Meeting::with('mahasiswa')
                ->where('dosen_id', $user->id)
                ->whereIn('mahasiswa_id', $mahasiswaIds) // ✅ FILTER INI
                ->whereIn('status', ['confirmed', 'completed'])
                ->orderBy('meeting_date', 'desc')
                ->get();

        } else {
            $meetings = Meeting::with('mahasiswa', 'dosen')
                ->whereIn('status', ['confirmed', 'completed'])
                ->orderBy('meeting_date', 'desc')
                ->get();
        }

        return view('documents.create', compact('meetings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // ✅ Middleware sudah handle:
        // - Mahasiswa: auth + check.role:mahasiswa + sk.mahasiswa
        // - Dosen: auth + check.role:dosen

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'meeting_id' => 'nullable|exists:meetings,id',
            'category' => 'required|in:proposal,draft,revisi,laporan,presentasi,final,lainnya',
            'document_file' => 'required|file|max:10240',
        ]);

        // ✅ TAMBAHKAN VALIDASI MEETING (jika ada meeting_id)
        if ($request->meeting_id) {
            $meeting = Meeting::find($request->meeting_id);

            if ($user->isMahasiswa()) {
                // Validasi meeting milik mahasiswa dan dari dosen pembimbing
                if ($meeting->mahasiswa_id !== $user->id) {
                    abort(403, 'Meeting ini bukan milik Anda.');
                }

                // Cek apakah meeting dari dosen pembimbing
                $isValidMeeting = DB::table('dosen_mahasiswa')
                    ->where('dosen_id', $meeting->dosen_id)
                    ->where('mahasiswa_id', $user->id)
                    ->exists();

                if (! $isValidMeeting) {
                    abort(403, 'Meeting ini tidak dari dosen pembimbing Anda.');
                }
            }

            if ($user->isDosen()) {
                // Validasi meeting milik dosen dan dengan mahasiswa bimbingan
                if ($meeting->dosen_id !== $user->id) {
                    abort(403, 'Meeting ini bukan milik Anda.');
                }

                // Cek apakah meeting dengan mahasiswa bimbingan
                $isValidMeeting = DB::table('dosen_mahasiswa')
                    ->where('dosen_id', $user->id)
                    ->where('mahasiswa_id', $meeting->mahasiswa_id)
                    ->exists();

                if (! $isValidMeeting) {
                    abort(403, 'Meeting ini tidak dengan mahasiswa bimbingan Anda.');
                }
            }
        }

        // Handle file upload
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');

            $status = $user->isDosen() ? 'approved' : 'draft';

            // Create document
            $document = Document::create([
                'user_id' => $user->id,
                'meeting_id' => $request->meeting_id,
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientMimeType(),
                'category' => $request->category,
                'status' => $status,
            ]);

            return redirect()->route('documents.index')
                ->with('success', 'Dokumen berhasil diupload!');
        }

        return redirect()->back()
            ->with('error', 'Gagal mengupload file.')
            ->withInput();
    }

    public function edit(Document $document)
    {
        $user = Auth::user();

        if ($document->user_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        if (! $document->canEdit()) {
            return redirect()->route('documents.show', $document)
                ->with('error', 'Dokumen tidak dapat diedit.');
        }

        // Get meetings yang bisa di-link
        if ($user->isMahasiswa()) {
            $meetings = Meeting::with('dosen')
                ->where('mahasiswa_id', $user->id)
                ->whereIn('status', ['confirmed', 'completed'])
                ->orderBy('meeting_date', 'desc')
                ->get();
        } else {
            // Dosen: HANYA meeting mahasiswa bimbingan
            $mahasiswaIds = $user->mahasiswaBimbinganAktifIds();

            $meetings = Meeting::with('mahasiswa')
                ->where('dosen_id', $user->id)
                ->whereIn('mahasiswa_id', $mahasiswaIds) // ✅ FILTER INI
                ->whereIn('status', ['confirmed', 'completed'])
                ->orderBy('meeting_date', 'desc')
                ->get();
        }

        return view('documents.edit', compact('document', 'meetings'));
    }

    public function update(Request $request, Document $document)
    {
        $user = Auth::user();

        if ($document->user_id !== $user->id) {
            abort(403);
        }

        if (! $document->canEdit()) {
            return redirect()->route('documents.show', $document)
                ->with('error', 'Dokumen tidak dapat diperbarui.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:proposal,draft,revisi,laporan,presentasi,final,lainnya',
            'document_file' => 'nullable|file|max:10240',
        ]);

        // Update data dokumen
        $document->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
        ]);

        // Jika upload file baru
        if ($request->hasFile('document_file')) {
            // Hapus file lama
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }

            $file = $request->file('document_file');
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');

            $document->update([
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientMimeType(),
                'status' => 'draft',
                'reviewed_at' => null,
                'dosen_feedback' => null,
            ]);
        }

        return redirect()->route('documents.show', $document)
            ->with('success', 'Dokumen berhasil diperbarui. Silakan submit ulang.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $user = Auth::user();

        if ($user->isMahasiswa()) {
            // ✅ Mahasiswa bisa lihat: dokumen sendiri ATAU dokumen dosen bimbingannya
            if ($document->user_id !== $user->id) {
                // Cek apakah ini dokumen dosen pembimbingnya
                if (! $document->user->isDosen()) {
                    abort(403, 'Unauthorized access.');
                }

                // Cek apakah dosen ini pembimbing mahasiswa
                $isSupervisor = DB::table('dosen_mahasiswa')
                    ->where('dosen_id', $document->user_id)
                    ->where('mahasiswa_id', $user->id)
                    ->exists();

                if (! $isSupervisor) {
                    abort(403, 'Dosen ini bukan pembimbing Anda.');
                }
            }

        } elseif ($user->isDosen()) {
            // ✅ Dosen bisa lihat: dokumen sendiri ATAU dokumen mahasiswa bimbingannya
            if ($document->user_id !== $user->id) {
                if (! $document->user->isMahasiswa()) {
                    abort(403, 'Unauthorized access.');
                }

                // Cek apakah dosen membimbing mahasiswa ini
                $isSupervisor = DB::table('dosen_mahasiswa')
                    ->where('dosen_id', $user->id)
                    ->where('mahasiswa_id', $document->user_id)
                    ->exists();

                if (! $isSupervisor) {
                    abort(403, 'Anda tidak membimbing mahasiswa ini.');
                }
            }

        } else {
            // Admin selalu bisa akses
            if (! $user->isAdmin()) {
                abort(403, 'Unauthorized access.');
            }
        }

        $document->load('user', 'meeting');

        return view('documents.show', compact('document'));
    }

    /**
     * Download the specified resource.
     */
    public function download(Document $document)
    {
        $user = Auth::user();

        if ($user->isMahasiswa()) {
            if ($document->user_id !== $user->id) {
                // Cek apakah dokumen dari dosen pembimbing
                if (! $document->user->isDosen()) {
                    abort(403, 'Unauthorized access.');
                }

                $isSupervisor = DB::table('dosen_mahasiswa')
                    ->where('dosen_id', $document->user_id)
                    ->where('mahasiswa_id', $user->id)
                    ->exists();

                if (! $isSupervisor) {
                    abort(403, 'Dosen ini bukan pembimbing Anda.');
                }
            }
        }

        if ($user->isDosen()) {
            if ($document->user_id !== $user->id) {
                // Cek apakah dokumen dari mahasiswa bimbingan
                if (! $document->user->isMahasiswa()) {
                    abort(403, 'Unauthorized access.');
                }

                $isSupervisor = DB::table('dosen_mahasiswa')
                    ->where('dosen_id', $user->id)
                    ->where('mahasiswa_id', $document->user_id)
                    ->exists();

                if (! $isSupervisor) {
                    abort(403, 'Anda tidak membimbing mahasiswa ini.');
                }
            }
        }

        if (Storage::disk('public')->exists($document->file_path)) {
            return Storage::disk('public')->download($document->file_path, $document->file_name);
        }

        return redirect()->back()
            ->with('error', 'File tidak ditemukan.');
    }

    /**
     * Submit document for review
     */
    public function submit(Document $document)
    {
        $user = Auth::user();

        // ✅ Middleware sk.mahasiswa sudah handle validasi mahasiswa
        if ($document->user_id !== $user->id) {
            abort(403, 'Hanya pemilik dokumen yang dapat submit.');
        }

        if (! $document->canSubmit()) {
            return redirect()->route('documents.show', $document)
                ->with('error', 'Dokumen sudah disubmit atau tidak dapat dikirim ulang.');
        }

        $document->submit();

        return redirect()->route('documents.index')
            ->with('success', 'Dokumen berhasil disubmit! Menunggu review dosen.');
    }

    /**
     * Display review queue for dosen
     */
    public function reviewIndex()
    {
        $user = Auth::user();

        if (! $user->isDosen()) {
            abort(403, 'Hanya dosen yang dapat mengakses halaman review.');
        }

        // ✅ HANYA dokumen mahasiswa bimbingan
        $mahasiswaIds = $user->mahasiswaBimbinganAktifIds();

        $submittedDocuments = Document::with(['user', 'meeting'])
            ->whereHas('user', function ($query) use ($mahasiswaIds) {
                $query->where('role', 'mahasiswa')
                    ->whereIn('id', $mahasiswaIds); // ✅ FILTER INI
            })
            ->where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->get();

        $reviewedDocuments = Document::with(['user', 'meeting'])
            ->whereHas('user', function ($query) use ($mahasiswaIds) {
                $query->where('role', 'mahasiswa')
                    ->whereIn('id', $mahasiswaIds); // ✅ FILTER INI
            })
            ->whereIn('status', ['approved', 'rejected'])
            ->orderBy('reviewed_at', 'desc')
            ->get();

        return view('documents.review-index', compact('submittedDocuments', 'reviewedDocuments'));
    }

    /**
     * Approve document (dosen only)
     */
    public function approve(Request $request, Document $document)
    {
        $user = Auth::user();

        // ✅ Middleware sk.dosen sudah handle validasi relasi
        if (! $user->isDosen()) {
            abort(403, 'Hanya dosen yang dapat menyetujui dokumen.');
        }

        if (! $document->canReview()) {
            return redirect()->route('documents.show', $document)
                ->with('error', 'Dokumen tidak dapat disetujui.');
        }

        // ✅ TAMBAHKAN VALIDASI: dokumen harus dari mahasiswa bimbingan
        if ($document->user->isMahasiswa()) {
            $isSupervisor = DB::table('dosen_mahasiswa')
                ->where('dosen_id', $user->id)
                ->where('mahasiswa_id', $document->user_id)
                ->exists();

            if (! $isSupervisor) {
                abort(403, 'Anda tidak membimbing mahasiswa ini.');
            }
        }

        $request->validate([
            'dosen_feedback' => 'nullable|string|max:1000',
        ]);

        $document->approve($request->dosen_feedback);

        return redirect()->route('documents.review.index')
            ->with('success', 'Dokumen berhasil disetujui!');
    }

    /**
     * Reject document (dosen only)
     */
    public function reject(Request $request, Document $document)
    {
        $user = Auth::user();

        // ✅ Middleware sk.dosen sudah handle validasi relasi
        if (! $user->isDosen()) {
            abort(403, 'Hanya dosen yang dapat menolak dokumen.');
        }

        if (! $document->canReview()) {
            return redirect()->route('documents.show', $document)
                ->with('error', 'Dokumen tidak dapat ditolak.');
        }

        // ✅ TAMBAHKAN VALIDASI: dokumen harus dari mahasiswa bimbingan
        if ($document->user->isMahasiswa()) {
            $isSupervisor = DB::table('dosen_mahasiswa')
                ->where('dosen_id', $user->id)
                ->where('mahasiswa_id', $document->user_id)
                ->exists();

            if (! $isSupervisor) {
                abort(403, 'Anda tidak membimbing mahasiswa ini.');
            }
        }

        $request->validate([
            'dosen_feedback' => 'required|string|min:5|max:1000',
        ]);

        $document->reject($request->dosen_feedback);

        return redirect()->route('documents.review.index')
            ->with('success', 'Dokumen berhasil ditolak. Mahasiswa dapat memperbaiki dan submit ulang.');
    }
}
