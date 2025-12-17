<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal Bimbingan - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Edit Jadwal Bimbingan</h1>
                <p class="text-gray-600">Update waktu available untuk bimbingan mahasiswa</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('availability.update', $availability) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   id="date"
                                   name="date"
                                   value="{{ old('date', $availability->date->format('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Pilih tanggal untuk bimbingan</p>
                        </div>

                        <!-- Start Time -->
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Waktu Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time"
                                   id="start_time"
                                   name="start_time"
                                   value="{{ old('start_time', $availability->start_time) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>

                        <!-- End Time -->
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Waktu Selesai <span class="text-red-500">*</span>
                            </label>
                            <input type="time"
                                   id="end_time"
                                   name="end_time"
                                   value="{{ old('end_time', $availability->end_time) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea id="notes"
                                  name="notes"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Contoh: Bimbingan proposal, Revisi bab 1, dll.">{{ old('notes', $availability->notes) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Tambahkan catatan untuk mahasiswa (maks. 500 karakter)</p>
                    </div>

                    <!-- Current Status -->
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-700 mb-2">Status Saat Ini</h4>
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                {{ $availability->status == 'available' ? 'bg-green-100 text-green-800' :
                                   ($availability->status == 'booked' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($availability->status) }}
                            </span>
                            <span class="text-sm text-gray-600">
                                Dibuat: {{ $availability->created_at->format('d M Y H:i') }}
                            </span>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('availability.index') }}"
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-200">
                            Batal
                        </a>
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                            Update Jadwal
                        </button>
                    </div>
                </form>
            </div>

            <!-- Back to List -->
            <div class="mt-6">
                <a href="{{ route('availability.index') }}"
                   class="text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Jadwal
                </a>
            </div>
        </div>
    </div>

    <script>
        // Set minimum time to current time if today is selected
        document.getElementById('date').addEventListener('change', function() {
            const selectedDate = this.value;
            const today = new Date().toISOString().split('T')[0];

            if (selectedDate === today) {
                const now = new Date();
                const currentTime = now.getHours().toString().padStart(2, '0') + ':' +
                                  now.getMinutes().toString().padStart(2, '0');

                document.getElementById('start_time').min = currentTime;
                document.getElementById('end_time').min = currentTime;
            } else {
                document.getElementById('start_time').removeAttribute('min');
                document.getElementById('end_time').removeAttribute('min');
            }
        });

        // Initialize min time based on current date
        const currentDate = document.getElementById('date').value;
        const today = new Date().toISOString().split('T')[0];
        if (currentDate === today) {
            const now = new Date();
            const currentTime = now.getHours().toString().padStart(2, '0') + ':' +
                              now.getMinutes().toString().padStart(2, '0');

            document.getElementById('start_time').min = currentTime;
            document.getElementById('end_time').min = currentTime;
        }
    </script>
</body>
</html>
