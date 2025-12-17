<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalender Bimbingan - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <style>
        .fc-event {
            cursor: pointer;
        }
        .fc-event:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Kalender Bimbingan</h1>
                    <p class="text-gray-600">
                        @if(Auth::user()->isMahasiswa())
                            Lihat jadwal bimbingan Anda dalam kalender
                        @elseif(Auth::user()->isDosen())
                            Lihat jadwal bimbingan mahasiswa dalam kalender
                        @else
                            Lihat semua jadwal bimbingan dalam kalender
                        @endif
                    </p>
                </div>
                <div class="flex space-x-3">
                    <!-- Tombol Kembali ke Dashboard -->
                    <a href="{{ route('dashboard') }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium transition duration-200">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <!-- Tombol Kembali ke List -->
                    <a href="{{ route('meetings.index') }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                        <i class="fas fa-list mr-2"></i>Lihat List
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Calendar Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total di Kalender</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ count($events) }}</h3>
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
                            <h3 class="text-2xl font-bold text-gray-800">
                                {{ count(array_filter($events, function($event) { return strpos($event['color'], '#3B82F6') !== false; })) }}
                            </h3>
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
                            <h3 class="text-2xl font-bold text-gray-800">
                                {{ count(array_filter($events, function($event) { return strpos($event['color'], '#10B981') !== false; })) }}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="bg-orange-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-clock text-orange-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Bulan Ini</p>
                            <h3 class="text-2xl font-bold text-gray-800">
                                {{ count(array_filter($events, function($event) {
                                    return date('Y-m', strtotime($event['start'])) === date('Y-m');
                                })) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Container -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div id="calendar" class="max-w-full"></div>
            </div>

            <!-- Legend -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Keterangan Warna</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-3"></div>
                        <span class="text-gray-700">Bimbingan Dikonfirmasi</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-3"></div>
                        <span class="text-gray-700">Bimbingan Selesai</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-gray-400 rounded mr-3"></div>
                        <span class="text-gray-700">Klik event untuk detail</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 text-center">
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center text-gray-600 hover:text-gray-800 font-medium transition duration-200">
                        <i class="fas fa-home mr-2"></i>Kembali ke Dashboard
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('meetings.index') }}"
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition duration-200">
                        <i class="fas fa-list mr-2"></i>Lihat Semua Bimbingan
                    </a>
                    @if(Auth::user()->isMahasiswa())
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('available.slots') }}"
                       class="inline-flex items-center text-green-600 hover:text-green-800 font-medium transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Ajukan Bimbingan Baru
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var events = @json($events);

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'id',
                firstDay: 1, // Senin sebagai hari pertama
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    week: 'Minggu',
                    day: 'Hari'
                },
                events: events,
                eventClick: function(info) {
                    // Redirect to meeting detail when event is clicked
                    if (info.event.url) {
                        window.location.href = info.event.url;
                        return false;
                    }
                },
                eventDisplay: 'block',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                dayMaxEvents: 3, // Maximum events per day before showing "+ more"
                views: {
                    timeGrid: {
                        dayMaxEventRows: 4 // Adjust for week/day view
                    }
                },
                eventDidMount: function(info) {
                    // Add tooltip
                    if (info.event.extendedProps.description) {
                        info.el.setAttribute('title', info.event.extendedProps.description);
                    }
                }
            });

            calendar.render();
        });
    </script>
</body>
</html>
