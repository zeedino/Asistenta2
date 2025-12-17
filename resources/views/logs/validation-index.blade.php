<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Logbook - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Validasi Logbook Mahasiswa</h1>
                    <p class="text-gray-600">Review dan validasi logbook bimbingan mahasiswa</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('logs.index') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <a href="{{ route('dashboard') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </div>
            </div>

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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Menunggu Validasi</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $submittedLogs->count() }}</h3>
                        </div>
                        <div class="bg-yellow-50 p-3 rounded-lg">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Tervalidasi</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $validatedLogs->count() }}</h3>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Ditolak</p>
                            <h3 class="text-2xl font-bold text-gray-800">
                                {{ $validatedLogs->where('status', 'rejected')->count() }}</h3>
                        </div>
                        <div class="bg-red-50 p-3 rounded-lg">
                            <i class="fas fa-times-circle text-red-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button id="submittedTab"
                            class="py-4 px-6 text-sm font-medium border-b-2 border-blue-500 text-blue-600 flex items-center">
                            <i class="fas fa-clock mr-2"></i>
                            Menunggu Validasi ({{ $submittedLogs->count() }})
                        </button>
                        <button id="validatedTab"
                            class="py-4 px-6 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            Riwayat Validasi ({{ $validatedLogs->count() }})
                        </button>
                    </nav>
                </div>
            </div>

            <div id="submittedContent" class="tab-content">
                @if ($submittedLogs->count() > 0)
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800">Logbook Menunggu Validasi</h2>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @foreach ($submittedLogs as $log)
                                <div class="px-6 py-4 hover:bg-gray-50 transition duration-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <div
                                                        class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                                        <i class="fas fa-clock text-yellow-600 text-lg"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="font-semibold text-gray-800 text-lg">
                                                        {{ $log->meeting->title ?? 'Logbook Bimbingan' }}
                                                    </h3>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                                        <div class="flex items-center text-sm text-gray-600">
                                                            <i class="fas fa-user mr-2"></i>
                                                            Mahasiswa: {{ $log->mahasiswa->username }}
                                                        </div>
                                                        <div class="flex items-center text-sm text-gray-600">
                                                            <i class="fas fa-calendar mr-2"></i>
                                                            {{ $log->created_at->format('d M Y H:i') }}
                                                        </div>
                                                    </div>
                                                    @if ($log->activity_description)
                                                        <p class="text-sm text-gray-600 mt-2">
                                                            {{ Str::limit($log->activity_description, 150) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Menunggu Validasi
                                            </span>
                                            <a href="{{ route('logs.show', $log) }}"
                                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200 text-sm">
                                                <i class="fas fa-eye mr-1"></i>Review
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                        <i class="fas fa-check-circle text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-2xl font-bold text-gray-600 mb-2">Tidak Ada Logbook Menunggu</h3>
                        <p class="text-gray-500 mb-6">Semua logbook mahasiswa sudah divalidasi.</p>
                    </div>
                @endif
            </div>

            <div id="validatedContent" class="tab-content hidden">
                @if ($validatedLogs->count() > 0)
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800">Riwayat Validasi</h2>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @foreach ($validatedLogs as $log)
                                <div class="px-6 py-4 hover:bg-gray-50 transition duration-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <div
                                                        class="w-12 h-12 rounded-lg flex items-center justify-center
                                                    {{ $log->status == 'validated' ? 'bg-green-100' : 'bg-red-100' }}">
                                                        <i
                                                            class="fas {{ $log->status == 'validated' ? 'fa-check-circle text-green-600' : 'fa-times-circle text-red-600' }} text-lg"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="font-semibold text-gray-800 text-lg">
                                                        {{ $log->meeting->title ?? 'Logbook Bimbingan' }}
                                                    </h3>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                                        <div class="flex items-center text-sm text-gray-600">
                                                            <i class="fas fa-user mr-2"></i>
                                                            Mahasiswa: {{ $log->mahasiswa->username }}
                                                        </div>
                                                        <div class="flex items-center text-sm text-gray-600">
                                                            <i class="fas fa-calendar mr-2"></i>
                                                            {{ $log->updated_at->format('d M Y H:i') }}
                                                        </div>
                                                    </div>
                                                    @if ($log->dosen_feedback)
                                                        <p class="text-sm text-gray-600 mt-2">
                                                            <strong>Feedback:</strong>
                                                            {{ Str::limit($log->dosen_feedback, 120) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-medium
                                            {{ $log->status == 'validated' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $log->status == 'validated' ? 'Tervalidasi' : 'Ditolak' }}
                                            </span>
                                            <a href="{{ route('logs.show', $log) }}"
                                                class="text-blue-600 hover:text-blue-800 transition duration-200">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-lg border border-gray-200 p-12 text-center">
                        <i class="fas fa-history text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-2xl font-bold text-gray-600 mb-2">Belum Ada Riwayat Validasi</h3>
                        <p class="text-gray-500">Riwayat validasi akan muncul di sini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        document.getElementById('submittedTab').addEventListener('click', function() {
            showTab('submitted');
        });

        document.getElementById('validatedTab').addEventListener('click', function() {
            showTab('validated');
        });

        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });

            // Remove active styles from all tabs
            document.querySelectorAll('nav button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            document.getElementById(tabName + 'Content').classList.remove('hidden');

            // Add active styles to selected tab
            document.getElementById(tabName + 'Tab').classList.add('border-blue-500', 'text-blue-600');
            document.getElementById(tabName + 'Tab').classList.remove('border-transparent', 'text-gray-500');
        }

        // Auto-hide flash messages
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                // PERBAIKAN 3: Gunakan selector spesifik '.flash-message'
                const flashMessages = document.querySelectorAll('.flash-message');
                flashMessages.forEach(message => {
                    message.style.transition = 'opacity 0.5s ease';
                    message.style.opacity = '0';
                    setTimeout(() => message.remove(), 500);
                });
            }, 5000);
        });
    </script>
</body>

</html>
