<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Bimbingan Tersedia - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Jadwal Bimbingan Tersedia</h1>
                        <p class="text-gray-600">Pilih jadwal yang tersedia untuk request bimbingan dengan dosen</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('dashboard') }}"
                            class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium transition duration-200">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>

                        <a href="{{ route('meetings.index') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                            <i class="fas fa-list mr-2"></i>Bimbingan Saya
                        </a>
                    </div>
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-calendar text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Dosen Tersedia</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $groupedAvailabilities->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-clock text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Slot Tersedia</p>
                            <h3 class="text-2xl font-bold text-gray-800">
                                {{ $groupedAvailabilities->sum(function ($availabilities) {return $availabilities->count();}) }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-user-graduate text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Dosen Aktif</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $groupedAvailabilities->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Slots by Dosen -->
            @if ($groupedAvailabilities->count() > 0)
                <div class="space-y-6">
                    @foreach ($groupedAvailabilities as $dosenId => $availabilities)
                        @php
                            $dosen = $availabilities->first()->dosen;
                        @endphp

                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <!-- Dosen Header -->
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="bg-white bg-opacity-20 p-2 rounded-full">
                                            <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-bold text-white">{{ $dosen->username }}</h2>
                                            <p class="text-blue-100 text-sm">Dosen Pembimbing</p>
                                        </div>
                                    </div>
                                    <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm">
                                        {{ $availabilities->count() }} slot tersedia
                                    </span>
                                </div>
                            </div>

                            <!-- Availability Slots -->
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($availabilities as $availability)
                                        <div
                                            class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <h3 class="font-semibold text-gray-800">
                                                        {{ \Carbon\Carbon::parse($availability->date)->translatedFormat('l, d F Y') }}
                                                    </h3>
                                                    <p class="text-lg text-blue-600 font-medium">
                                                        {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                                                    </p>
                                                </div>
                                                <span
                                                    class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">
                                                    Tersedia
                                                </span>
                                            </div>

                                            @if ($availability->notes)
                                                <p class="text-sm text-gray-600 mb-4">{{ $availability->notes }}</p>
                                            @endif

                                            <div class="flex justify-between items-center">
                                                <span class="text-xs text-gray-500">
                                                    {{ $availability->created_at->diffForHumans() }}
                                                </span>
                                                <button
                                                    class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition duration-200 request-meeting-btn"
                                                    data-availability-id="{{ $availability->id }}"
                                                    data-dosen-name="{{ $dosen->username }}"
                                                    data-date="{{ \Carbon\Carbon::parse($availability->date)->format('d M Y') }}"
                                                    data-time="{{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}">
                                                    <i class="fas fa-calendar-plus mr-1"></i>Request
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-600 mb-4">Tidak Ada Jadwal Tersedia</h3>
                    <p class="text-gray-500 mb-6">Saat ini belum ada jadwal bimbingan yang tersedia. Silakan coba lagi
                        nanti.</p>
                    <div class="flex justify-center space-x-4">
                        <button onclick="location.reload()"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh
                        </button>
                    </div>
                </div>
            @endif

            <!-- Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
                <h3 class="font-semibold text-blue-800 mb-3 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Penting
                </h3>
                <ul class="text-sm text-blue-700 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                        <span>Pilih jadwal yang sesuai dengan waktu luang Anda</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                        <span>Pastikan Anda sudah mempersiapkan materi yang akan dibimbing</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                        <span>Request bimbingan akan menunggu konfirmasi dari dosen</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                        <span>Anda akan mendapatkan notifikasi ketika request dikonfirmasi</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Request Meeting Modal -->
    <div id="requestMeetingModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Request Bimbingan</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="meetingRequestForm" method="POST" action="{{ route('meetings.store') }}">
                @csrf
                <input type="hidden" id="availability_id" name="availability_id">

                <div class="space-y-4">
                    <!-- Dosen & Jadwal Info -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dosen</label>
                        <p id="dosenInfo" class="text-gray-800 font-semibold bg-gray-50 p-2 rounded"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jadwal</label>
                        <p id="scheduleInfo" class="text-gray-800 bg-gray-50 p-2 rounded"></p>
                    </div>

                    <!-- Title Input -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Bimbingan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: Bimbingan Proposal Bab 1" required value="{{ old('title') }}">
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Agenda Input -->
                    <div>
                        <label for="agenda" class="block text-sm font-medium text-gray-700 mb-2">
                            Agenda Bimbingan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="agenda" name="agenda" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Jelaskan agenda dan materi yang akan dibimbing..." required>{{ old('agenda') }}</textarea>
                        @error('agenda')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" id="cancelRequest"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-200">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                        Kirim Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById('requestMeetingModal');
        const closeModal = document.getElementById('closeModal');
        const cancelRequest = document.getElementById('cancelRequest');
        const requestButtons = document.querySelectorAll('.request-meeting-btn');
        const dosenInfo = document.getElementById('dosenInfo');
        const scheduleInfo = document.getElementById('scheduleInfo');
        const availabilityIdInput = document.getElementById('availability_id');

        requestButtons.forEach(button => {
            button.addEventListener('click', function() {
                const availabilityId = this.getAttribute('data-availability-id');
                const dosenName = this.getAttribute('data-dosen-name');
                const date = this.getAttribute('data-date');
                const time = this.getAttribute('data-time');

                console.log('Button clicked:', {
                    availabilityId,
                    dosenName,
                    date,
                    time
                });

                availabilityIdInput.value = availabilityId;
                dosenInfo.textContent = dosenName;
                scheduleInfo.textContent = `${date} | ${time}`;

                modal.classList.remove('hidden');
            });
        });

        [closeModal, cancelRequest].forEach(button => {
            button.addEventListener('click', function() {
                modal.classList.add('hidden');
                // Reset form
                document.getElementById('meetingRequestForm').reset();
            });
        });

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                document.getElementById('meetingRequestForm').reset();
            }
        });

        // Form submission handling
        document.getElementById('meetingRequestForm').addEventListener('submit', function(e) {
            console.log('Form submitted');
            // Form will submit normally via POST request
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            submitBtn.disabled = true;
        });
    </script>
</body>

</html>
