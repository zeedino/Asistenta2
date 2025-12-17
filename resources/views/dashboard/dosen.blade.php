<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Dosen</h1>
                <p class="text-gray-600 mt-1">Selamat datang, {{ Auth::user()->username }}</p>
            </div>

            @if (session('warning'))
                <div
                    class="flash-message bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                        </div>
                        <div class="ml-3">
                            <p class="font-medium">Perhatian</p>
                            <p class="mt-1">{{ session('warning') }}</p>
                            <div class="mt-2 text-sm">
                                @if (!Auth::user()->hasMahasiswaBimbinganAktif())
                                    <p class="mb-1">Untuk mendapatkan mahasiswa bimbingan:</p>
                                    <ol class="list-decimal list-inside ml-2 space-y-1">
                                        <li>Hubungi Admin untuk membuat Surat Keputusan (SK)</li>
                                        <li>Tunggu Admin menetapkan mahasiswa bimbingan</li>
                                        <li>SK akan diaktifkan oleh Admin</li>
                                    </ol>
                                @endif
                            </div>
                        </div>
                        <button type="button" onclick="this.parentElement.parentElement.remove()"
                            class="ml-auto -mx-1.5 -my-1.5 text-yellow-500 hover:text-yellow-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="flash-message bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="flash-message bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            @php
                $hasMahasiswa = Auth::user()->hasMahasiswaBimbinganAktif();
            @endphp

            @if (!$hasMahasiswa)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-5 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600 text-xl mt-0.5"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-blue-800 mb-2">Status Bimbingan</h3>
                            <p class="text-blue-700 mb-3">
                                Anda belum memiliki mahasiswa bimbingan yang aktif. Fitur-fitur berikut memerlukan Surat
                                Keputusan (SK) bimbingan:
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                <div class="flex items-center text-blue-600">
                                    <i class="fas fa-calendar-plus mr-2"></i>
                                    <span>Membuat Jadwal Availability</span>
                                </div>
                                <div class="flex items-center text-blue-600">
                                    <i class="fas fa-clipboard-check mr-2"></i>
                                    <span>Validasi Logbook Mahasiswa</span>
                                </div>
                                <div class="flex items-center text-blue-600">
                                    <i class="fas fa-file-check mr-2"></i>
                                    <span>Review Dokumen Mahasiswa</span>
                                </div>
                                <div class="flex items-center text-blue-600">
                                    <i class="fas fa-comment-dots mr-2"></i>
                                    <span>Konfirmasi Meeting</span>
                                </div>
                            </div>
                            <div class="bg-blue-100 border border-blue-300 rounded p-3">
                                <p class="text-sm font-medium text-blue-800 mb-1">Langkah mendapatkan mahasiswa
                                    bimbingan:</p>
                                <ol class="list-decimal list-inside ml-3 text-sm text-blue-700 space-y-1">
                                    <li>Hubungi Admin kampus/akademik</li>
                                    <li>Minta dibuatkan Surat Keputusan (SK) bimbingan</li>
                                    <li>Tunggu Admin menetapkan mahasiswa</li>
                                    <li>SK akan aktif setelah ditandatangani</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                        <div>
                            <p class="font-medium text-green-800">Status Bimbingan Aktif</p>
                            <p class="text-green-700 text-sm">
                                ✅ Anda memiliki {{ $mahasiswaBimbinganCount ?? 0 }} mahasiswa bimbingan aktif.
                                Semua fitur bimbingan dapat diakses.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg border p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Mahasiswa</p>
                            <p class="text-xl font-semibold text-gray-800">
                                {{ $mahasiswaBimbinganCount ?? 0 }}
                            </p>
                        </div>
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <i class="fas fa-user-graduate text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Bimbingan</p>
                            <p class="text-xl font-semibold text-gray-800">{{ $totalMeetings }}</p>
                        </div>
                        <div class="p-2 bg-green-50 rounded-lg">
                            <i class="fas fa-calendar-check text-green-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Menunggu Logbook & Bimbingan</p>
                            <p class="text-xl font-semibold text-gray-800">{{ $pendingMeetings + $pendingLogs }}</p>
                        </div>
                        <div class="p-2 bg-yellow-50 rounded-lg">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Dokumen</p>
                            <p class="text-xl font-semibold text-gray-800">{{ $totalDocuments }}</p>
                        </div>
                        <div class="p-2 bg-purple-50 rounded-lg">
                            <i class="fas fa-file-alt text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-lg border p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Mahasiswa Bimbingan</h2>
                            <span class="text-sm text-gray-500">
                                Total: {{ $mahasiswaBimbinganCount ?? 0 }}
                            </span>
                        </div>

                        @if (isset($mahasiswaBimbingan) && $mahasiswaBimbingan->count() > 0)
                            <div class="space-y-4">
                                @foreach ($mahasiswaBimbingan as $assignment)
                                    @php
                                        // Ambil data SK dari pivot
                                        $skId = $assignment->pivot->sk_id ?? null;
                                        $sk = $skId ? App\Models\SuratKeputusan::find($skId) : null;
                                    @endphp
                                    <div
                                        class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-user-graduate text-gray-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $assignment->username }}</p>
                                                <div class="flex items-center mt-1">
                                                    <span
                                                        class="text-xs px-2 py-0.5 rounded
                                                        {{ $assignment->pivot->posisi == 'Pembimbing 1' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                        {{ $assignment->pivot->posisi }}
                                                    </span>
                                                    @if ($sk)
                                                        <span class="text-xs text-gray-500 ml-2">
                                                            SK: {{ $sk->nomor_sk }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('meetings.index') }}?mahasiswa={{ $assignment->id }}"
                                                class="text-sm text-blue-600 hover:text-blue-800 px-3 py-1 border border-blue-200 rounded hover:bg-blue-50">
                                                <i class="fas fa-comment-dots mr-1"></i> Meetings
                                            </a>
                                            <a href="{{ route('logs.index') }}?mahasiswa={{ $assignment->id }}"
                                                class="text-sm text-green-600 hover:text-green-800 px-3 py-1 border border-green-200 rounded hover:bg-green-50">
                                                <i class="fas fa-clipboard-list mr-1"></i> Logs
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-user-graduate text-3xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Belum ada mahasiswa bimbingan</p>
                                <p class="text-sm text-gray-400 mt-1">Mahasiswa akan muncul setelah SK ditetapkan</p>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-lg border p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Permintaan Bimbingan</h2>
                            <a href="{{ route('meetings.index') }}"
                                class="text-sm text-blue-600 hover:text-blue-800">
                                Lihat semua
                            </a>
                        </div>

                        @if ($recentMeetings->count() > 0)
                            <div class="space-y-3">
                                @foreach ($recentMeetings as $meeting)
                                    <div class="flex items-center justify-between p-3 border rounded-lg">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-800 truncate">{{ $meeting->title }}</p>
                                            <div class="flex items-center mt-1">
                                                <span
                                                    class="text-sm text-gray-600">{{ $meeting->mahasiswa->username }}</span>
                                                <span
                                                    class="text-xs px-2 py-0.5 rounded ml-2
                                                    {{ $meeting->status == 'pending'
                                                        ? 'bg-yellow-100 text-yellow-800'
                                                        : ($meeting->status == 'confirmed'
                                                            ? 'bg-green-100 text-green-800'
                                                            : 'bg-gray-100 text-gray-800') }}">
                                                    {{ $meeting->status }}
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ route('meetings.show', $meeting) }}"
                                            class="ml-3 text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Tidak ada permintaan bimbingan</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg border p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
                        <div class="space-y-3">
                            <a href="{{ route('availability.create') }}"
                                class="flex items-center p-3 border rounded-lg hover:bg-blue-50 hover:border-blue-200 transition
                                {{ !$hasMahasiswa ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if (!$hasMahasiswa) onclick="alert('Anda perlu memiliki mahasiswa bimbingan untuk membuat jadwal.'); return false;" @endif>
                                <i class="fas fa-calendar-plus text-blue-600 mr-3"></i>
                                <span>Tambah Jadwal</span>
                                @if (!$hasMahasiswa)
                                    <span class="ml-auto text-xs text-yellow-600">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('logs.validation.index') }}"
                                class="flex items-center p-3 border rounded-lg hover:bg-green-50 hover:border-green-200 transition
                                {{ !$hasMahasiswa ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if (!$hasMahasiswa) onclick="alert('Anda perlu memiliki mahasiswa bimbingan untuk validasi logbook.'); return false;" @endif>
                                <i class="fas fa-clipboard-check text-green-600 mr-3"></i>
                                <span>Validasi Logbook</span>
                                @if (!$hasMahasiswa)
                                    <span class="ml-auto text-xs text-yellow-600">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('documents.review.index') }}"
                                class="flex items-center p-3 border rounded-lg hover:bg-yellow-50 hover:border-yellow-200 transition
                                {{ !$hasMahasiswa ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if (!$hasMahasiswa) onclick="alert('Anda perlu memiliki mahasiswa bimbingan untuk review dokumen.'); return false;" @endif>
                                <i class="fas fa-file-check text-yellow-600 mr-3"></i>
                                <span>Review Dokumen</span>
                                @if (!$hasMahasiswa)
                                    <span class="ml-auto text-xs text-yellow-600">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('meetings.calendar') }}"
                                class="flex items-center p-3 border rounded-lg hover:bg-purple-50 hover:border-purple-200 transition
                                {{ !$hasMahasiswa ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if (!$hasMahasiswa) onclick="alert('Anda perlu memiliki mahasiswa bimbingan untuk melihat kalender bimbingan.'); return false;" @endif>
                                <i class="fas fa-calendar-alt text-purple-600 mr-3"></i>
                                <span>Lihat Kalender</span>
                                @if (!$hasMahasiswa)
                                    <span class="ml-auto text-xs text-yellow-600">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide flash messages
        setTimeout(() => {
            // PERBAIKAN: Gunakan class selector khusus '.flash-message'
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(msg => {
                msg.style.transition = 'opacity 0.3s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 300);
            });
        }, 4000);
    </script>
</body>

</html>
