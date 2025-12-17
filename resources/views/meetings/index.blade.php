<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Bimbingan - AsistenTA</title>
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
                            Bimbingan Saya
                        @elseif(Auth::user()->isDosen())
                            Bimbingan Mahasiswa
                        @else
                            Manajemen Bimbingan
                        @endif
                    </h1>
                    <p class="text-gray-600">
                        @if (Auth::user()->isMahasiswa())
                            Kelola request bimbingan Anda
                        @elseif(Auth::user()->isDosen())
                            Kelola bimbingan mahasiswa
                        @else
                            Kelola semua bimbingan sistem
                        @endif
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('meetings.calendar') }}"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium transition duration-200">
                        <i class="fas fa-calendar-alt mr-2"></i>Kalender
                    </a>
                    <a href="{{ route('dashboard') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium transition duration-200">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>

                    @if (Auth::user()->isMahasiswa())
                        <a href="{{ route('available.slots') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                            <i class="fas fa-plus mr-2"></i>Ajukan Bimbingan
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
                        'total' => $meetings->count(),
                        'pending' => $meetings->where('status', 'pending')->count(),
                        'confirmed' => $meetings->where('status', 'confirmed')->count(),
                        'completed' => $meetings->where('status', 'completed')->count(),
                    ];
                @endphp
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-list text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Menunggu</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['pending'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Dikonfirmasi</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['confirmed'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-flag-checkered text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Selesai</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['completed'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meetings List -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Daftar Bimbingan</h2>
                </div>

                @if ($meetings->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach ($meetings as $meeting)
                            <div class="px-6 py-4 hover:bg-gray-50 transition duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-calendar-check text-blue-600 text-lg"></i>
                                                </div>
                                            </div>

                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-800 text-lg">{{ $meeting->title }}
                                                </h3>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <i class="fas fa-user mr-2"></i>
                                                        @if (Auth::user()->isMahasiswa())
                                                            Dosen: {{ $meeting->dosen->username }}
                                                        @else
                                                            Mahasiswa: {{ $meeting->mahasiswa->username }}
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <i class="fas fa-clock mr-2"></i>
                                                        {{ $meeting->meeting_date ? \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y H:i') : 'Jadwal belum ditentukan' }}
                                                    </div>
                                                </div>
                                                @if ($meeting->agenda)
                                                    <p class="text-sm text-gray-600 mt-2">
                                                        {{ Str::limit($meeting->agenda, 100) }}</p>
                                                @endif
                                                <p class="text-xs text-gray-500 mt-2">
                                                    Diajukan: {{ $meeting->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $meeting->status == 'pending'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : ($meeting->status == 'confirmed'
                                                ? 'bg-green-100 text-green-800'
                                                : ($meeting->status == 'completed'
                                                    ? 'bg-purple-100 text-purple-800'
                                                    : ($meeting->status == 'rejected'
                                                        ? 'bg-red-100 text-red-800'
                                                        : 'bg-gray-100 text-gray-800'))) }}">
                                            {{ ucfirst($meeting->status) }}
                                        </span>

                                        <a href="{{ route('meetings.show', $meeting) }}"
                                            class="text-blue-600 hover:text-blue-800 transition duration-200">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Action Buttons -->
                                        @if (Auth::user()->isMahasiswa() && in_array($meeting->status, ['pending', 'confirmed']))
                                            <form action="{{ route('meetings.cancel', $meeting) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan bimbingan ini?')">
                                                @csrf
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 transition duration-200">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if (Auth::user()->isDosen() && $meeting->status === 'pending')
                                            <div class="flex space-x-2">
                                                <form action="{{ route('meetings.updateStatus', $meeting) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button type="submit"
                                                        class="text-green-600 hover:text-green-800 transition"
                                                        onclick="return confirm('Konfirmasi bimbingan ini?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('meetings.updateStatus', $meeting) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 transition"
                                                        onclick="return confirm('Tolak bimbingan ini?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        {{-- DOSEN: saat confirmed (selesai / batalkan) --}}
                                        @if (Auth::user()->isDosen() && $meeting->status === 'confirmed')
                                            <div class="flex space-x-2">
                                                <form action="{{ route('meetings.complete', $meeting) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Tandai bimbingan sebagai selesai?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-purple-600 hover:text-purple-800 transition">
                                                        <i class="fas fa-flag-checkered"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('meetings.cancel', $meeting) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Batalkan bimbingan ini?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 transition">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-600 mb-2">
                            @if (Auth::user()->isMahasiswa())
                                Belum ada bimbingan
                            @else
                                Belum ada request bimbingan
                            @endif
                        </h3>
                        <p class="text-gray-500 mb-4">
                            @if (Auth::user()->isMahasiswa())
                                Mulai dengan mengajukan bimbingan pertama Anda
                            @else
                                Mahasiswa belum mengajukan bimbingan
                            @endif
                        </p>
                        @if (Auth::user()->isMahasiswa())
                            <a href="{{ route('available.slots') }}"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                                <i class="fas fa-plus mr-2"></i>Ajukan Bimbingan Pertama
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
