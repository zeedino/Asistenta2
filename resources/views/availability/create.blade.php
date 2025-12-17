<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal Bimbingan - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Tambah Jadwal Bimbingan</h1>
                <p class="text-gray-600">Tambahkan waktu available untuk bimbingan mahasiswa</p>
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
                <form action="{{ route('availability.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date"
                                   id="date"
                                   name="date"
                                   value="{{ old('date') }}"
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
                                   value="{{ old('start_time') }}"
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
                                   value="{{ old('end_time') }}"
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
                                  placeholder="Contoh: Bimbingan proposal, Revisi bab 1, dll.">{{ old('notes') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Tambahkan catatan untuk mahasiswa (maks. 500 karakter)</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('availability.index') }}"
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-200">
                            Batal
                        </a>
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                            Simpan Jadwal
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tips -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                <h3 class="font-semibold text-blue-800 mb-2">
                    <i class="fas fa-lightbulb mr-2"></i>Tips
                </h3>
                <ul class="text-sm text-blue-700 space-y-1">
                    <li>• Pastikan waktu mulai lebih awal dari waktu selesai</li>
                    <li>• Beri jeda antar jadwal untuk persiapan</li>
                    <li>• Tambahkan catatan untuk informasi tambahan</li>
                </ul>
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
    </script>
</body>
</html>
