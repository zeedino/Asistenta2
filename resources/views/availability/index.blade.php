<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jadwal Bimbingan - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Kelola Jadwal Bimbingan</h1>
                    <p class="text-gray-600">Atur waktu available untuk bimbingan mahasiswa</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('dashboard') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium transition duration-200">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('availability.create') }}"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Tambah Jadwal
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

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-calendar text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Jadwal</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $availabilities->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Available</p>
                            <h3 class="text-2xl font-bold text-gray-800">
                                {{ $availabilities->where('status', 'available')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Booked</p>
                            <h3 class="text-2xl font-bold text-gray-800">
                                {{ $availabilities->where('status', 'booked')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-red-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Cancelled</p>
                            <h3 class="text-2xl font-bold text-gray-800">
                                {{ $availabilities->where('status', 'cancelled')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Availability List -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Daftar Jadwal Bimbingan</h2>
                </div>

                @if ($availabilities->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach ($availabilities as $availability)
                            <div class="px-6 py-4 hover:bg-gray-50 transition duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4">
                                            <div class="text-center bg-gray-100 rounded-lg p-3 min-w-20">
                                                <div class="text-lg font-bold text-gray-800">
                                                    {{ \Carbon\Carbon::parse($availability->date)->format('d') }}
                                                </div>
                                                <div class="text-sm text-gray-600 uppercase">
                                                    {{ \Carbon\Carbon::parse($availability->date)->format('M') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ \Carbon\Carbon::parse($availability->date)->format('Y') }}
                                                </div>
                                            </div>

                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-800">
                                                    {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                                                </h3>
                                                @if ($availability->notes)
                                                    <p class="text-sm text-gray-600 mt-1">{{ $availability->notes }}
                                                    </p>
                                                @endif
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Dibuat: {{ $availability->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-medium
                                        {{ $availability->status == 'available'
                                            ? 'bg-green-100 text-green-800'
                                            : ($availability->status == 'booked'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($availability->status) }}
                                        </span>

                                        <a href="{{ route('availability.edit', $availability) }}"
                                            class="text-blue-600 hover:text-blue-800 transition duration-200 {{ $availability->status === 'booked' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $availability->status === 'booked' ? 'onclick="return false;"' : '' }}>
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('availability.destroy', $availability) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 transition duration-200 {{ $availability->status === 'booked' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $availability->status === 'booked' ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-600 mb-2">Belum ada jadwal bimbingan</h3>
                        <p class="text-gray-500 mb-4">Mulai dengan menambahkan jadwal bimbingan pertama Anda</p>
                        <div class="flex justify-center space-x-3">
                            <a href="{{ route('availability.create') }}"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                                <i class="fas fa-plus mr-2"></i>Tambah Jadwal Pertama
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Back to Dashboard -->
            <div class="mt-8 text-center">
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('meetings.index') }}"
                        class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition duration-200">
                        <i class="fas fa-list mr-2"></i>Lihat Bimbingan
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
