<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahasiswa Dashboard - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        i.fa {
            opacity: 1 !important;
            visibility: visible !important;
        }

        i.fa:hover {
            opacity: 1 !important;
            visibility: visible !important;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Mahasiswa</h1>
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
                                @if (!Auth::user()->hasActiveSK())
                                    <p class="mb-1">Untuk mendapatkan Surat Keputusan (SK):</p>
                                    <ol class="list-decimal list-inside ml-2 space-y-1">
                                        <li>Hubungi Admin atau bagian akademik</li>
                                        <li>Minta dibuatkan SK Pembimbing</li>
                                        <li>Tunggu Admin menetapkan dosen pembimbing</li>
                                        <li>SK akan diaktifkan setelah ditandatangani</li>
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
                $hasActiveSK = Auth::user()->hasActiveSK();
                $hasDosenPembimbing = isset($dosenPembimbing) && $dosenPembimbing->count() > 0;
            @endphp

            @if (!$hasActiveSK)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-5 mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600 text-xl mt-0.5"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-blue-800 mb-2">Status Surat Keputusan (SK)</h3>
                            <p class="text-blue-700 mb-3">
                                Anda belum memiliki Surat Keputusan (SK) Pembimbing yang aktif.
                                Fitur-fitur berikut memerlukan SK aktif:
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                <div class="flex items-center text-blue-600">
                                    <i class="fas fa-calendar-plus mr-2"></i>
                                    <span>Request Bimbingan (Meeting)</span>
                                </div>
                                <div class="flex items-center text-blue-600">
                                    <i class="fas fa-clipboard-list mr-2"></i>
                                    <span>Membuat Logbook</span>
                                </div>
                                <div class="flex items-center text-blue-600">
                                    <i class="fas fa-file-upload mr-2"></i>
                                    <span>Upload Dokumen Bimbingan</span>
                                </div>
                                <div class="flex items-center text-blue-600">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    <span>Melihat Jadwal Dosen Pembimbing</span>
                                </div>
                            </div>
                            <div class="bg-blue-100 border border-blue-300 rounded p-3">
                                <p class="text-sm font-medium text-blue-800 mb-1">Langkah mendapatkan SK Pembimbing:</p>
                                <ol class="list-decimal list-inside ml-3 text-sm text-blue-700 space-y-1">
                                    <li>Hubungi Admin atau bagian akademik kampus</li>
                                    <li>Minta dibuatkan Surat Keputusan Pembimbing</li>
                                    <li>Tunggu Admin menetapkan dosen pembimbing</li>
                                    <li>SK akan aktif setelah ditandatangani</li>
                                </ol>
                                @if (Auth::user()->isAdmin())
                                    <p class="text-sm text-blue-800 mt-2">
                                        <i class="fas fa-user-shield mr-1"></i>
                                        Anda adalah Admin. Anda bisa membuat SK di
                                        <a href="{{ route('admin.surat-keputusan.create') }}"
                                            class="font-medium underline">
                                            halaman Admin → SK Management
                                        </a>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div id="active-sk-banner"
                    class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 transition-opacity duration-500">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                        <div>
                            <p class="font-medium text-green-800">Status SK Aktif</p>
                            <p class="text-green-700 text-sm">
                                ✅ Anda memiliki SK Pembimbing aktif dengan {{ $dosenPembimbingCount ?? 0 }} dosen
                                pembimbing.
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
                            <p class="text-sm text-gray-500 mb-1">Bimbingan</p>
                            <p class="text-xl font-semibold text-gray-800">{{ $myMeetings }}</p>
                        </div>
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <i class="fas fa-calendar text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Menunggu</p>
                            <p class="text-xl font-semibold text-gray-800">{{ $pendingMeetings }}</p>
                        </div>
                        <div class="p-2 bg-yellow-50 rounded-lg">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Logbook</p>
                            <p class="text-xl font-semibold text-gray-800">{{ $totalLogs }}</p>
                        </div>
                        <div class="p-2 bg-purple-50 rounded-lg">
                            <i class="fas fa-file-alt text-purple-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Dokumen</p>
                            <p class="text-xl font-semibold text-gray-800">{{ $totalDocuments }}</p>
                        </div>
                        <div class="p-2 bg-green-50 rounded-lg">
                            <i class="fas fa-file text-green-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-lg border p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Dosen Pembimbing</h2>
                            <span class="text-sm text-gray-500">
                                {{ $dosenPembimbingCount ?? 0 }} Dosen
                            </span>
                        </div>

                        @if ($hasDosenPembimbing)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($dosenPembimbing as $dosen)
                                    <div
                                        class="border rounded-lg p-4
                    {{ $dosen->pivot->posisi == 'Pembimbing 1' ? 'bg-blue-50 border-blue-200' : 'bg-purple-50 border-purple-200' }}">

                                        <div class="flex items-center mb-3">
                                            <div
                                                class="w-10 h-10 rounded-full flex items-center justify-center mr-3
                            {{ $dosen->pivot->posisi == 'Pembimbing 1' ? 'bg-blue-100' : 'bg-purple-100' }}">
                                                <span
                                                    class="fas fa-user-tie
                                {{ $dosen->pivot->posisi == 'Pembimbing 1' ? 'text-blue-600' : 'text-purple-600' }}">
                                                </span>
                                            </div>

                                            <div>
                                                <p class="font-medium text-gray-800">{{ $dosen->username }}</p>
                                                <span
                                                    class="text-xs px-2 py-0.5 rounded
                                {{ $dosen->pivot->posisi == 'Pembimbing 1' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                    {{ $dosen->pivot->posisi }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="text-sm text-gray-600 space-y-1">
                                            <p>{{ $dosen->email }}</p>

                                            @if ($dosen->pivot->suratKeputusan)
                                                <p class="text-xs text-gray-500">
                                                    SK: {{ $dosen->pivot->suratKeputusan->nomor_sk ?? 'N/A' }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="mt-3 flex space-x-2">
                                            <a href="{{ route('available.slots') }}?dosen={{ $dosen->id }}"
                                                class="text-sm text-blue-600 hover:text-blue-800 px-3 py-1 border border-blue-200 rounded hover:bg-blue-50
                                                {{ !$hasActiveSK ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                @if (!$hasActiveSK) onclick="alert('Anda perlu memiliki SK Pembimbing aktif untuk mengajukan bimbingan.'); return false;" @endif>
                                                <i class="fas fa-calendar-plus mr-1"></i> Ajukan
                                            </a>

                                            <a href="{{ route('meetings.index') }}?dosen={{ $dosen->id }}"
                                                class="text-sm text-purple-600 hover:text-purple-800 px-3 py-1 border border-purple-200 rounded hover:bg-purple-50
                                                {{ !$hasActiveSK ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                @if (!$hasActiveSK) onclick="alert('Anda perlu memiliki SK Pembimbing aktif untuk melihat riwayat bimbingan.'); return false;" @endif>
                                                <i class="fas fa-list mr-1"></i> Riwayat
                                            </a>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-user-tie text-3xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Belum ada dosen pembimbing</p>
                                <p class="text-sm text-gray-400 mt-1">Menunggu penetapan SK dari admin</p>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-lg border p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Bimbingan Mendatang</h2>
                            <a href="{{ route('meetings.index') }}"
                                class="text-sm text-blue-600 hover:text-blue-800">
                                Lihat semua
                            </a>
                        </div>

                        @if ($upcomingMeetings->count() > 0)
                            <div class="space-y-3">
                                @foreach ($upcomingMeetings as $meeting)
                                    <div class="flex items-center justify-between p-3 border rounded-lg">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-800">{{ $meeting->title }}</p>
                                            <div class="flex items-center mt-1">
                                                <span
                                                    class="text-sm text-gray-600">{{ $meeting->dosen->username }}</span>
                                                @if ($meeting->meeting_date)
                                                    <span class="text-xs text-gray-500 ml-2">
                                                        {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M H:i') }}
                                                    </span>
                                                @endif
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
                            <p class="text-gray-500 text-center py-4">Tidak ada bimbingan mendatang</p>
                            @if ($hasDosenPembimbing)
                                <div class="text-center">
                                    <a href="{{ route('available.slots') }}"
                                        class="text-sm text-blue-600 hover:text-blue-800
                                        {{ !$hasActiveSK ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        @if (!$hasActiveSK) onclick="alert('Anda perlu memiliki SK Pembimbing aktif untuk mengajukan bimbingan.'); return false;" @endif>
                                        Ajukan bimbingan
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg border p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
                        <div class="space-y-3">
                            <a href="{{ route('available.slots') }}"
                                class="flex items-center p-3 border rounded-lg hover:bg-blue-50 hover:border-blue-200 transition
                                {{ !$hasActiveSK ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if (!$hasActiveSK) onclick="alert('Anda perlu memiliki SK Pembimbing aktif untuk mengajukan bimbingan.'); return false;" @endif>
                                <i class="fas fa-calendar-plus text-blue-600 mr-3"></i>
                                <span>Ajukan Bimbingan</span>
                                @if (!$hasActiveSK)
                                    <span class="ml-auto text-xs text-yellow-600">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('logs.create') }}"
                                class="flex items-center p-3 border rounded-lg hover:bg-green-50 hover:border-green-200 transition
                                {{ !$hasActiveSK ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if (!$hasActiveSK) onclick="alert('Anda perlu memiliki SK Pembimbing aktif untuk membuat logbook.'); return false;" @endif>
                                <i class="fas fa-plus text-green-600 mr-3"></i>
                                <span>Tulis Logbook</span>
                                @if (!$hasActiveSK)
                                    <span class="ml-auto text-xs text-yellow-600">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('documents.create') }}"
                                class="flex items-center p-3 border rounded-lg hover:bg-yellow-50 hover:border-yellow-200 transition
                                {{ !$hasActiveSK ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if (!$hasActiveSK) onclick="alert('Anda perlu memiliki SK Pembimbing aktif untuk upload dokumen.'); return false;" @endif>
                                <i class="fas fa-upload text-yellow-600 mr-3"></i>
                                <span>Upload Dokumen</span>
                                @if (!$hasActiveSK)
                                    <span class="ml-auto text-xs text-yellow-600">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('meetings.calendar') }}"
                                class="flex items-center p-3 border rounded-lg hover:bg-purple-50 hover:border-purple-200 transition
                                {{ !$hasActiveSK ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if (!$hasActiveSK) onclick="alert('Anda perlu memiliki SK Pembimbing aktif untuk melihat kalender bimbingan.'); return false;" @endif>
                                <i class="fas fa-calendar-alt text-purple-600 mr-3"></i>
                                <span>Lihat Kalender</span>
                                @if (!$hasActiveSK)
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
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(msg => {
                msg.style.transition = 'opacity 0.3s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 300);
            });
        }, 4000);

        setTimeout(() => {
            const skBanner = document.getElementById('active-sk-banner');
            if (skBanner) {
                skBanner.style.opacity = '0';
                setTimeout(() => {
                    skBanner.remove();
                }, 500);
            }
        }, 5000);
    </script>
</body>

</html>
