<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logbook Bimbingan - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        @if (Auth::user()->isMahasiswa())
                            Logbook Bimbingan Saya
                        @elseif(Auth::user()->isDosen())
                            Logbook Mahasiswa Bimbingan
                        @else
                            Manajemen Logbook
                        @endif
                    </h1>
                    <p class="text-gray-600">
                        @if (Auth::user()->isMahasiswa())
                            Kelola dan pantau progress bimbingan Anda
                        @elseif(Auth::user()->isDosen())
                            Validasi logbook mahasiswa bimbingan
                        @else
                            Kelola semua logbook sistem
                        @endif
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('dashboard') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium transition duration-200">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>

                    @if (Auth::user()->isMahasiswa())
                        <a href="{{ route('logs.create') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                            <i class="fas fa-plus mr-2"></i>Tulis Logbook
                        </a>
                    @endif

                    @if (Auth::user()->isDosen())
                        <a href="{{ route('logs.validation.index') }}"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium transition duration-200">
                            <i class="fas fa-check-circle mr-2"></i>Validasi Logbook
                        </a>
                    @endif
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

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                @php
                    $stats = [
                        'total' => $logs->count(),
                        'draft' => $logs->where('status', 'draft')->count(),
                        'submitted' => $logs->where('status', 'submitted')->count(),
                        'validated' => $logs->where('status', 'validated')->count(),
                    ];
                @endphp
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-list text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Logbook</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-gray-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-edit text-gray-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Draft</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['draft'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Menunggu Validasi</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['submitted'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tervalidasi</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['validated'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logs List -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Daftar Logbook</h2>
                </div>

                @if ($logs->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach ($logs as $log)
                            <div class="px-6 py-4 hover:bg-gray-50 transition duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-clipboard-list text-blue-600 text-lg"></i>
                                                </div>
                                            </div>

                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-800 text-lg">
                                                    Logbook: {{ $log->meeting->title ?? 'Meeting' }}
                                                </h3>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <i class="fas fa-user mr-2"></i>
                                                        @if (Auth::user()->isMahasiswa())
                                                            Dosen: {{ $log->dosen->username }}
                                                        @else
                                                            Mahasiswa: {{ $log->mahasiswa->username }}
                                                        @endif
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
                                            class="px-3 py-1 rounded-full text-xs font-medium
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

                                        <a href="{{ route('logs.show', $log) }}"
                                            class="text-blue-600 hover:text-blue-800 transition duration-200">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Action Buttons untuk Mahasiswa -->
                                        @if (Auth::user()->isMahasiswa())
                                            @if ($log->canEdit())
                                                <a href="{{ route('logs.edit', $log) }}"
                                                    class="text-green-600 hover:text-green-800 transition duration-200">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('logs.submit', $log) }}" method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('Submit logbook untuk validasi dosen?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-yellow-600 hover:text-yellow-800 transition duration-200">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-clipboard-list text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-600 mb-2">
                            @if (Auth::user()->isMahasiswa())
                                Belum ada logbook
                            @else
                                Belum ada logbook mahasiswa
                            @endif
                        </h3>
                        <p class="text-gray-500 mb-4">
                            @if (Auth::user()->isMahasiswa())
                                Mulai dengan menulis logbook pertama Anda
                            @else
                                Mahasiswa belum membuat logbook
                            @endif
                        </p>
                        @if (Auth::user()->isMahasiswa())
                            <a href="{{ route('logs.create') }}"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                                <i class="fas fa-plus mr-2"></i>Tulis Logbook Pertama
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
