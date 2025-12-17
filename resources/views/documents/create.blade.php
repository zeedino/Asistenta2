<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen - AsistenTA</title>
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
                        <h1 class="text-3xl font-bold text-gray-800">Upload Dokumen Baru</h1>
                        <p class="text-gray-600">Unggah dokumen bimbingan Anda</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('documents.index') }}"
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

            <!-- Upload Form -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Document Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Dokumen <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: Proposal Skripsi Bab 1-3" value="{{ old('title') }}" required>
                    </div>

                    <!-- Document Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi (Opsional)
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Jelaskan tentang dokumen ini...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Meeting Selection -->
                    <div class="mb-6">
                        <label for="meeting_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Tautkan dengan Bimbingan (Opsional)
                        </label>
                        <select name="meeting_id" id="meeting_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Pilih Bimbingan --</option>
                            @foreach ($meetings as $meeting)
                                <option value="{{ $meeting->id }}"
                                    {{ old('meeting_id') == $meeting->id ? 'selected' : '' }}>
                                    {{ $meeting->title }} -
                                    @if (Auth::user()->isMahasiswa())
                                        {{ $meeting->dosen->username }}
                                    @else
                                        {{ $meeting->mahasiswa->username }}
                                    @endif
                                    -
                                    {{ $meeting->meeting_date ? \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') : 'Tanggal belum ditentukan' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-gray-500 text-xs mt-1">Pilih bimbingan terkait untuk memudahkan organisasi</p>
                    </div>

                    <!-- Document Type -->
                    <!-- Document Type -->
                    <div class="mb-6">
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori Dokumen <span class="text-red-500">*</span>
                        </label>
                        <select name="category" id="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="proposal" {{ old('category') == 'proposal' ? 'selected' : '' }}>Proposal
                            </option>
                            <option value="draft" {{ old('category') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="revisi" {{ old('category') == 'revisi' ? 'selected' : '' }}>Revisi</option>
                            <option value="laporan" {{ old('category') == 'laporan' ? 'selected' : '' }}>Laporan
                            </option>
                            <option value="presentasi" {{ old('category') == 'presentasi' ? 'selected' : '' }}>
                                Presentasi</option>
                            <option value="final" {{ old('category') == 'final' ? 'selected' : '' }}>Final</option>
                            <option value="lainnya" {{ old('category') == 'lainnya' ? 'selected' : '' }}>Lainnya
                            </option>
                        </select>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-6">
                        <label for="document_file" class="block text-sm font-medium text-gray-700 mb-2">
                            File Dokumen <span class="text-red-500">*</span>
                        </label>
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition duration-200">
                            <input type="file" name="document_file" id="document_file" class="hidden"
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip" required>
                            <div id="fileUploadArea" class="cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-3"></i>
                                <p class="text-lg font-medium text-gray-600">Klik untuk memilih file</p>
                                <p class="text-sm text-gray-500 mt-1">PDF, DOC, DOCX, PPT, XLS (Maks. 10MB)</p>
                                <p id="fileName" class="text-sm text-blue-600 font-medium mt-2 hidden"></p>
                            </div>
                        </div>
                        <p class="text-gray-500 text-xs mt-1">File akan disimpan secara aman di sistem</p>
                    </div>

                    <!-- Information -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>Informasi
                        </h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Dokumen akan dibuat dengan status <strong>Draft</strong></li>
                            <li>• Anda bisa submit untuk review dosen nanti</li>
                            <li>• File maksimal 10MB</li>
                            <li>• Format yang didukung: PDF, DOC, DOCX, PPT, XLS, TXT, ZIP</li>
                        </ul>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('documents.index') }}"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                            <i class="fas fa-upload mr-2"></i>Upload Dokumen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // File upload preview
        document.getElementById('fileUploadArea').addEventListener('click', function() {
            document.getElementById('document_file').click();
        });

        document.getElementById('document_file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileName').classList.remove('hidden');

                // Validate file size (10MB)
                const maxSize = 10 * 1024 * 1024; // 10MB in bytes
                if (file.size > maxSize) {
                    alert('File terlalu besar! Maksimal 10MB.');
                    e.target.value = '';
                    document.getElementById('fileName').classList.add('hidden');
                }

                // Validate file type
                const allowedTypes = ['.pdf', '.doc', '.docx', '.ppt', '.pptx', '.xls', '.xlsx', '.txt', '.zip'];
                const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
                if (!allowedTypes.includes(fileExtension)) {
                    alert('Format file tidak didukung!');
                    e.target.value = '';
                    document.getElementById('fileName').classList.add('hidden');
                }
            }
        });

        // Drag and drop functionality
        const fileUploadArea = document.getElementById('fileUploadArea');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            fileUploadArea.classList.add('border-blue-400', 'bg-blue-50');
        }

        function unhighlight() {
            fileUploadArea.classList.remove('border-blue-400', 'bg-blue-50');
        }

        fileUploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            document.getElementById('document_file').files = files;

            // Trigger change event
            const event = new Event('change');
            document.getElementById('document_file').dispatchEvent(event);
        }

        // Auto-hide flash messages
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const flashMessages = document.querySelectorAll('.bg-green-50, .bg-red-50');
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
