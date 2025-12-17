<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Logbook - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Detail Logbook</h1>
                    <p class="text-gray-600">Review detail logbook bimbingan</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('logs.index') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
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
                <!-- Left Column - Log Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Log Information Card -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Informasi Logbook</h2>
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium
                                {{ $log->status == 'draft'
                                    ? 'bg-gray-100 text-gray-800'
                                    : ($log->status == 'submitted'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : ($log->status == 'validated'
                                            ? 'bg-green-100 text-green-800'
                                            : ($log->status == 'rejected'
                                                ? 'bg-red-100 text-red-800'
                                                : 'bg-gray-100 text-gray-800'))) }}">
                                {{ $log->getStatusText() }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            <!-- Meeting Info -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bimbingan</label>
                                <p class="text-gray-900">{{ $log->meeting->title }}</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    dengan {{ $log->dosen->username }} •
                                    @if ($log->meeting->meeting_date)
                                        {{ \Carbon\Carbon::parse($log->meeting->meeting_date)->format('d M Y H:i') }}
                                    @else
                                        Tanggal belum ditentukan
                                    @endif
                                </p>
                            </div>

                            <!-- Activity Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kegiatan</label>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <p class="text-gray-900 whitespace-pre-line">{{ $log->activity_description }}</p>
                                </div>
                            </div>

                            <!-- Progress -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Progress yang
                                    Dicapai</label>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <p class="text-gray-900 whitespace-pre-line">{{ $log->progress }}</p>
                                </div>
                            </div>

                            <!-- Obstacles -->
                            @if ($log->obstacles)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kendala/Hambatan</label>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <p class="text-gray-900 whitespace-pre-line">{{ $log->obstacles }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Next Plan -->
                            @if ($log->next_plan)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rencana
                                        Selanjutnya</label>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <p class="text-gray-900 whitespace-pre-line">{{ $log->next_plan }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Dosen Feedback -->
                    @if ($log->dosen_feedback)
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-comment-alt text-blue-600 mr-2"></i>Feedback Dosen
                            </h2>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-line">{{ $log->dosen_feedback }}</p>
                                @if ($log->validated_at)
                                    <p class="text-sm text-gray-600 mt-2">
                                        Divalidasi pada: {{ $log->validated_at->format('d M Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column - Actions & Info -->
                <div class="space-y-6">
                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h2>

                        <!-- Mahasiswa Actions -->
                        @if (Auth::user()->isMahasiswa())
                            @if ($log->canEdit())
                                <div class="space-y-3">
                                    <form action="{{ route('logs.submit', $log) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 font-medium transition duration-200 flex items-center justify-center"
                                            onclick="return confirm('Submit logbook untuk validasi dosen?')">
                                            <i class="fas fa-paper-plane mr-2"></i>Submit untuk Validasi
                                        </button>
                                    </form>

                                    <a href="{{ route('logs.edit', $log) }}"
                                        class="block w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200 text-center">
                                        <i class="fas fa-edit mr-2"></i>Edit Logbook
                                    </a>
                                </div>
                            @elseif($log->status == 'rejected')
                                <div class="space-y-3">
                                    <p class="text-sm text-gray-600 mb-3">Logbook Anda ditolak. Silakan perbaiki dan
                                        submit ulang.</p>
                                    <a href="{{ route('logs.edit', $log) }}"
                                        class="block w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200 text-center">
                                        <i class="fas fa-edit mr-2"></i>Edit & Submit Ulang
                                    </a>
                                </div>
                            @else
                                <p class="text-sm text-gray-600 text-center py-2">
                                    @if ($log->status == 'submitted')
                                        Menunggu validasi dosen...
                                    @elseif($log->status == 'validated')
                                        Logbook sudah tervalidasi
                                    @endif
                                </p>
                            @endif
                        @endif

                        <!-- Dosen Actions -->
                        @if (Auth::user()->isDosen() && $log->canValidate())
                            <div class="space-y-3">
                                <form action="{{ route('logs.validate', $log) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label for="dosen_feedback"
                                            class="block text-sm font-medium text-gray-700 mb-1">
                                            Feedback (Opsional)
                                        </label>
                                        <textarea name="dosen_feedback" id="dosen_feedback" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                            placeholder="Berikan feedback atau catatan..."></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="submit"
                                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium transition duration-200">
                                            <i class="fas fa-check mr-1"></i>Validasi
                                        </button>
                                        <button type="button" onclick="showRejectForm()"
                                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium transition duration-200">
                                            <i class="fas fa-times mr-1"></i>Tolak
                                        </button>
                                    </div>
                                </form>

                                <!-- Reject Form (Hidden by Default) -->
                                <form action="{{ route('logs.reject', $log) }}" method="POST" id="rejectForm"
                                    class="hidden space-y-3">
                                    @csrf
                                    <div>
                                        <label for="reject_feedback"
                                            class="block text-sm font-medium text-gray-700 mb-1">
                                            Alasan Penolakan *
                                        </label>
                                        <textarea name="dosen_feedback" id="reject_feedback" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                            placeholder="Jelaskan alasan penolakan..." required></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="button" onclick="hideRejectForm()"
                                            class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium transition duration-200">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium transition duration-200">
                                            <i class="fas fa-times mr-1"></i>Konfirmasi Tolak
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Log Information -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dibuat:</span>
                                <span class="font-medium">{{ $log->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Diupdate:</span>
                                <span class="font-medium">{{ $log->updated_at->format('d M Y H:i') }}</span>
                            </div>
                            @if ($log->validated_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Divalidasi:</span>
                                    <span class="font-medium">{{ $log->validated_at->format('d M Y H:i') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Mahasiswa:</span>
                                <span class="font-medium">{{ $log->mahasiswa->username }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dosen:</span>
                                <span class="font-medium">{{ $log->dosen->username }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showRejectForm() {
            document.getElementById('rejectForm').classList.remove('hidden');
        }

        function hideRejectForm() {
            document.getElementById('rejectForm').classList.add('hidden');
        }
    </script>
</body>

</html>
