<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Bimbingan - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Detail Bimbingan</h1>
                    <p class="text-gray-600">Informasi lengkap request bimbingan</p>
                </div>
                <a href="{{ route('meetings.index') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Meeting Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Meeting Info Card -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Bimbingan</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Bimbingan</label>
                                <p class="text-gray-900 font-medium">{{ $meeting->title }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    {{ $meeting->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                       ($meeting->status == 'confirmed' ? 'bg-green-100 text-green-800' :
                                       ($meeting->status == 'completed' ? 'bg-purple-100 text-purple-800' :
                                       ($meeting->status == 'rejected' ? 'bg-red-100 text-red-800' :
                                       ($meeting->status == 'cancelled' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800')))) }}">
                                    {{ ucfirst($meeting->status) }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    @if(Auth::user()->isMahasiswa())
                                        Dosen Pembimbing
                                    @else
                                        Mahasiswa
                                    @endif
                                </label>
                                <p class="text-gray-900">
                                    @if(Auth::user()->isMahasiswa())
                                        {{ $meeting->dosen->username }}
                                    @else
                                        {{ $meeting->mahasiswa->username }}
                                    @endif
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Diajukan</label>
                                <p class="text-gray-900">{{ $meeting->created_at->format('d F Y H:i') }}</p>
                            </div>

                            @if($meeting->meeting_date)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jadwal Bimbingan</label>
                                <p class="text-gray-900">
                                    {{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d F Y H:i') }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Agenda Card -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Agenda Bimbingan</h2>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $meeting->agenda }}</p>
                        </div>
                    </div>

                    <!-- Availability Info -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Jadwal</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <p class="text-gray-900">
                                    {{ $meeting->availability->date->format('d F Y') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                                <p class="text-gray-900">
                                    {{ \Carbon\Carbon::parse($meeting->availability->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($meeting->availability->end_time)->format('H:i') }}
                                </p>
                            </div>
                            @if($meeting->availability->notes)
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Dosen</label>
                                <p class="text-gray-900 bg-blue-50 p-3 rounded-lg">
                                    {{ $meeting->availability->notes }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if(Auth::user()->isMahasiswa() && in_array($meeting->status, ['pending', 'confirmed']))
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Aksi</h2>
                        <form action="{{ route('meetings.cancel', $meeting) }}"
                              method="POST"
                              onsubmit="return confirm('Apakah Anda yakin ingin membatalkan bimbingan ini?')">
                            @csrf
                            <button type="submit"
                                    class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 font-medium transition duration-200">
                                <i class="fas fa-times mr-2"></i>Batalkan Bimbingan
                            </button>
                        </form>
                    </div>
                    @endif

                    @if(Auth::user()->isDosen() && $meeting->status === 'pending')
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Konfirmasi Bimbingan</h2>
                        <div class="flex space-x-4">
                            <form action="{{ route('meetings.updateStatus', $meeting) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit"
                                        class="w-full bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 font-medium transition duration-200"
                                        onclick="return confirm('Konfirmasi bimbingan ini?')">
                                    <i class="fas fa-check mr-2"></i>Setujui
                                </button>
                            </form>
                            <form action="{{ route('meetings.updateStatus', $meeting) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit"
                                        class="w-full bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 font-medium transition duration-200"
                                        onclick="return confirm('Tolak bimbingan ini?')">
                                    <i class="fas fa-times mr-2"></i>Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    @if(Auth::user()->isDosen() && $meeting->status === 'confirmed')
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Tandai sebagai Selesai</h2>
                        <form action="{{ route('meetings.complete', $meeting) }}"
                              method="POST"
                              onsubmit="return confirm('Tandai bimbingan sebagai selesai?')">
                            @csrf
                            <button type="submit"
                                    class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 font-medium transition duration-200">
                                <i class="fas fa-flag-checkered mr-2"></i>Tandai Selesai
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                <!-- Right Column - Notes & Timeline -->
                <div class="space-y-6">
                    <!-- Dosen Notes -->
                    @if($meeting->dosen_notes)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-comment-dots text-blue-600 mr-2"></i>
                            Catatan Dosen
                        </h2>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-gray-700 whitespace-pre-line">{{ $meeting->dosen_notes }}</p>
                        </div>
                        @if($meeting->status === 'rejected')
                        <p class="text-sm text-red-600 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Bimbingan ini telah ditolak
                        </p>
                        @endif
                    </div>
                    @endif

                    <!-- Status Timeline -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Status Timeline</h2>
                        <div class="space-y-4">
                            <!-- Created -->
                            <div class="flex items-start">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-plus text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Diajukan</p>
                                    <p class="text-sm text-gray-500">{{ $meeting->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            <!-- Status Updates -->
                            @if($meeting->status === 'confirmed')
                            <div class="flex items-start">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-check text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Disetujui</p>
                                    <p class="text-sm text-gray-500">{{ $meeting->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($meeting->status === 'rejected')
                            <div class="flex items-start">
                                <div class="bg-red-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-times text-red-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Ditolak</p>
                                    <p class="text-sm text-gray-500">{{ $meeting->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($meeting->status === 'completed')
                            <div class="flex items-start">
                                <div class="bg-purple-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-flag-checkered text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Selesai</p>
                                    <p class="text-sm text-gray-500">{{ $meeting->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($meeting->status === 'cancelled')
                            <div class="flex items-start">
                                <div class="bg-gray-100 p-2 rounded-full mr-3">
                                    <i class="fas fa-ban text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">Dibatalkan</p>
                                    <p class="text-sm text-gray-500">{{ $meeting->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Aksi Cepat</h2>
                        <div class="space-y-3">
                            <a href="{{ route('meetings.index') }}"
                               class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium transition duration-200 flex items-center justify-center">
                                <i class="fas fa-list mr-2"></i>Lihat Semua Bimbingan
                            </a>

                            @if(Auth::user()->isMahasiswa())
                            <a href="{{ route('available.slots') }}"
                               class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200 flex items-center justify-center">
                                <i class="fas fa-calendar-plus mr-2"></i>Ajukan Bimbingan Baru
                            </a>
                            @endif

                            <a href="{{ route('dashboard') }}"
                               class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium transition duration-200 flex items-center justify-center">
                                <i class="fas fa-home mr-2"></i>Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dosen Notes Modal (for Dosen only) -->
    @if(Auth::user()->isDosen() && $meeting->status === 'pending')
    <div id="notesModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Tambahkan Catatan</h3>
                <button onclick="closeNotesModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="rejectForm" method="POST">
                @csrf
                <input type="hidden" name="status" value="rejected">

                <div class="mb-4">
                    <label for="dosen_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Alasan Penolakan (Opsional)
                    </label>
                    <textarea id="dosen_notes"
                              name="dosen_notes"
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Berikan alasan penolakan atau saran untuk mahasiswa..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeNotesModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-200">
                        Batal
                    </button>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-200">
                        Tolak Bimbingan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openNotesModal(formAction) {
            document.getElementById('rejectForm').action = formAction;
            document.getElementById('notesModal').classList.remove('hidden');
        }

        function closeNotesModal() {
            document.getElementById('notesModal').classList.add('hidden');
        }
    </script>
    @endif
</body>
</html>
