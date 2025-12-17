<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Logbook - AsistenTA</title>
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
                        <h1 class="text-3xl font-bold text-gray-800">Edit Logbook</h1>
                        <p class="text-gray-600">Perbarui detail logbook bimbingan Anda</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('logs.show', $log) }}"
                            class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>

                <!-- Status Info -->
                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            <span class="text-blue-800 font-medium">Status: {{ $log->getStatusText() }}</span>
                        </div>
                        @if ($log->status == 'draft')
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">
                                <i class="fas fa-edit mr-1"></i>Dapat diedit
                            </span>
                        @elseif($log->status == 'rejected')
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Perlu diperbaiki
                            </span>
                        @endif
                    </div>

                    @if ($log->status == 'rejected' && $log->dosen_feedback)
                        <div class="mt-2 p-3 bg-white border border-red-200 rounded">
                            <p class="text-sm text-red-700">
                                <strong>Feedback Dosen:</strong> {{ $log->dosen_feedback }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

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
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <form action="{{ route('logs.update', $log) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Meeting Info (Readonly) -->
                    <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <h3 class="font-semibold text-gray-800 mb-2">Informasi Bimbingan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Judul Bimbingan:</span>
                                <p class="font-medium">{{ $log->meeting->title }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Dosen Pembimbing:</span>
                                <p class="font-medium">{{ $log->dosen->username }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Tanggal Bimbingan:</span>
                                <p class="font-medium">
                                    @if ($log->meeting->meeting_date)
                                        {{ \Carbon\Carbon::parse($log->meeting->meeting_date)->format('d M Y H:i') }}
                                    @else
                                        Belum ditentukan
                                    @endif
                                </p>
                            </div>
                            <div>
                                <span class="text-gray-600">Status Logbook:</span>
                                <span
                                    class="px-2 py-1 rounded text-xs font-medium
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
                        </div>
                    </div>

                    <!-- Activity Description -->
                    <div class="mb-6">
                        <label for="activity_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="activity_description" id="activity_description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Jelaskan kegiatan apa yang dilakukan selama bimbingan..." required>{{ old('activity_description', $log->activity_description) }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Minimal 10 karakter</p>
                    </div>

                    <!-- Progress -->
                    <div class="mb-6">
                        <label for="progress" class="block text-sm font-medium text-gray-700 mb-2">
                            Progress yang Dicapai <span class="text-red-500">*</span>
                        </label>
                        <textarea name="progress" id="progress" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Jelaskan progress atau hasil yang dicapai..." required>{{ old('progress', $log->progress) }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Minimal 10 karakter</p>
                    </div>

                    <!-- Obstacles -->
                    <div class="mb-6">
                        <label for="obstacles" class="block text-sm font-medium text-gray-700 mb-2">
                            Kendala/Hambatan (Opsional)
                        </label>
                        <textarea name="obstacles" id="obstacles" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Jelaskan kendala atau hambatan yang dihadapi...">{{ old('obstacles', $log->obstacles) }}</textarea>
                    </div>

                    <!-- Next Plan -->
                    <div class="mb-6">
                        <label for="next_plan" class="block text-sm font-medium text-gray-700 mb-2">
                            Rencana Selanjutnya (Opsional)
                        </label>
                        <textarea name="next_plan" id="next_plan" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Jelaskan rencana untuk bimbingan berikutnya...">{{ old('next_plan', $log->next_plan) }}</textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            <p>Terakhir diupdate: {{ $log->updated_at->format('d M Y H:i') }}</p>
                        </div>

                        <div class="flex space-x-3">
                            <a href="{{ route('logs.show', $log) }}"
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                                Batal
                            </a>

                            <!-- Save as Draft Button -->
                            <button type="submit" name="action" value="save"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                                <i class="fas fa-save mr-2"></i>Simpan Perubahan
                            </button>

                            <!-- Submit Button (Only for draft/rejected logs) -->
                            @if ($log->status == 'draft' || $log->status == 'rejected')
                                <button type="submit" name="action" value="submit"
                                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-medium transition duration-200">
                                    <i class="fas fa-paper-plane mr-2"></i>Simpan & Submit
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Information Card -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
                <h3 class="font-semibold text-blue-800 mb-3 flex items-center">
                    <i class="fas fa-lightbulb mr-2"></i>Tips Menulis Logbook yang Baik
                </h3>
                <ul class="text-sm text-blue-700 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                        <span>Jelaskan kegiatan bimbingan secara detail dan spesifik</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                        <span>Catat progress yang benar-benar dicapai, bukan rencana</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                        <span>Jika ada kendala, jelaskan secara jelas dan solusi yang dicoba</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle mt-1 mr-2 text-green-500"></i>
                        <span>Rencana selanjutnya harus konkret dan dapat diukur</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Confirmation for submit action
        document.addEventListener('DOMContentLoaded', function() {
            const submitButton = document.querySelector('button[value="submit"]');
            if (submitButton) {
                submitButton.addEventListener('click', function(e) {
                    if (!confirm(
                            'Apakah Anda yakin ingin menyimpan dan submit logbook? Setelah disubmit, Anda tidak dapat mengedit lagi hingga divalidasi dosen.'
                        )) {
                        e.preventDefault();
                    }
                });
            }

            // Auto-hide flash messages
            setTimeout(() => {
                const flashMessages = document.querySelectorAll('.bg-green-50, .bg-red-50');
                flashMessages.forEach(message => {
                    message.style.transition = 'opacity 0.5s ease';
                    message.style.opacity = '0';
                    setTimeout(() => message.remove(), 500);
                });
            }, 5000);

            // Character count validation
            const activityDesc = document.getElementById('activity_description');
            const progress = document.getElementById('progress');

            function validateMinLength(textarea, minLength) {
                if (textarea.value.length < minLength && textarea.value.length > 0) {
                    textarea.classList.add('border-red-300');
                    textarea.classList.remove('border-gray-300');
                } else {
                    textarea.classList.remove('border-red-300');
                    textarea.classList.add('border-gray-300');
                }
            }

            if (activityDesc) {
                activityDesc.addEventListener('input', function() {
                    validateMinLength(this, 10);
                });
            }

            if (progress) {
                progress.addEventListener('input', function() {
                    validateMinLength(this, 10);
                });
            }
        });
    </script>
</body>

</html>
