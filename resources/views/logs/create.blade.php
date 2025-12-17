<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tulis Logbook Baru - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Tulis Logbook Baru</h1>
                        <p class="text-gray-600">Isi detail kegiatan bimbingan yang telah dilakukan</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('logs.index') }}"
                            class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>
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

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="{{ route('logs.store') }}" method="POST">
                    @csrf

                    <!-- Pilih Meeting -->
                    <div class="mb-6">
                        <label for="meeting_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Bimbingan <span class="text-red-500">*</span>
                        </label>
                        <select name="meeting_id" id="meeting_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                            <option value="">-- Pilih Bimbingan --</option>
                            @foreach ($meetings as $meeting)
                                <option value="{{ $meeting->id }}"
                                    {{ old('meeting_id') == $meeting->id ? 'selected' : '' }}>
                                    {{ $meeting->title }} -
                                    {{ $meeting->dosen->username }} -
                                    {{ $meeting->meeting_date ? \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y H:i') : 'Tanggal belum ditentukan' }}
                                </option>
                            @endforeach
                        </select>
                        @if ($meetings->count() == 0)
                            <p class="text-yellow-600 text-sm mt-2">
                                Tidak ada bimbingan yang tersedia untuk dibuat logbook.
                                Pastikan bimbingan sudah selesai atau dikonfirmasi.
                            </p>
                        @endif
                    </div>

                    <!-- Activity Description -->
                    <div class="mb-6">
                        <label for="activity_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="activity_description" id="activity_description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Jelaskan kegiatan apa yang dilakukan selama bimbingan..." required>{{ old('activity_description') }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Minimal 10 karakter</p>
                    </div>

                    <!-- Progress -->
                    <div class="mb-6">
                        <label for="progress" class="block text-sm font-medium text-gray-700 mb-2">
                            Progress yang Dicapai <span class="text-red-500">*</span>
                        </label>
                        <textarea name="progress" id="progress" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Jelaskan progress atau hasil yang dicapai..." required>{{ old('progress') }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Minimal 10 karakter</p>
                    </div>

                    <!-- Obstacles -->
                    <div class="mb-6">
                        <label for="obstacles" class="block text-sm font-medium text-gray-700 mb-2">
                            Kendala/Hambatan (Opsional)
                        </label>
                        <textarea name="obstacles" id="obstacles" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Jelaskan kendala atau hambatan yang dihadapi...">{{ old('obstacles') }}</textarea>
                    </div>

                    <!-- Next Plan -->
                    <div class="mb-6">
                        <label for="next_plan" class="block text-sm font-medium text-gray-700 mb-2">
                            Rencana Selanjutnya (Opsional)
                        </label>
                        <textarea name="next_plan" id="next_plan" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Jelaskan rencana untuk bimbingan berikutnya...">{{ old('next_plan') }}</textarea>
                    </div>

                    <!-- Information -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>Informasi
                        </h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Logbook akan dibuat dengan status <strong>Draft</strong></li>
                            <li>• Anda bisa edit dan submit nanti</li>
                            <li>• Setelah disubmit, dosen akan memvalidasi logbook</li>
                        </ul>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('logs.index') }}"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                            <i class="fas fa-save mr-2"></i>Simpan Draft
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
