<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Dokumen - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Detail Dokumen</h1>
                    <p class="text-gray-600">Review informasi dan status dokumen</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('documents.index') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
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

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Document Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Document Information Card -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Informasi Dokumen</h2>
                            <span
                                class="px-3 py-1 rounded-full text-sm font-medium
                                {{ $document->status == 'draft'
                                    ? 'bg-gray-100 text-gray-800'
                                    : ($document->status == 'submitted'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : ($document->status == 'approved'
                                            ? 'bg-green-100 text-green-800'
                                            : ($document->status == 'rejected'
                                                ? 'bg-red-100 text-red-800'
                                                : 'bg-gray-100 text-gray-800'))) }}">
                                {{ $document->getCategoryText() }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            <!-- Document Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Dokumen</label>
                                <p class="text-gray-900 font-medium">{{ $document->title }}</p>
                            </div>

                            <!-- Document Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <p class="text-gray-900">{{ $document->getCategoryText() }}</p>
                            </div>

                            <!-- Description -->
                            @if ($document->description)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <p class="text-gray-900 whitespace-pre-line">{{ $document->description }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- File Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama File</label>
                                    <p class="text-gray-900">{{ $document->file_name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Ukuran File</label>
                                    <p class="text-gray-900">{{ $document->getFileSizeFormatted() }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe File</label>
                                    <p class="text-gray-900">{{ $document->file_type }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                                    <p class="text-gray-900 uppercase">
                                        {{ pathinfo($document->file_name, PATHINFO_EXTENSION) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Meeting Information -->
                    @if ($document->meeting)
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>Informasi Bimbingan Terkait
                            </h2>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Judul Bimbingan:</span>
                                    <span class="font-medium">{{ $document->meeting->title }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">
                                        @if (Auth::user()->isMahasiswa())
                                            Dosen:
                                        @else
                                            Mahasiswa:
                                        @endif
                                    </span>
                                    <span class="font-medium">
                                        @if (Auth::user()->isMahasiswa())
                                            {{ $document->meeting->dosen->username }}
                                        @else
                                            {{ $document->meeting->mahasiswa->username }}
                                        @endif
                                    </span>
                                </div>
                                @if ($document->meeting->meeting_date)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Tanggal Bimbingan:</span>
                                        <span
                                            class="font-medium">{{ \Carbon\Carbon::parse($document->meeting->meeting_date)->format('d M Y H:i') }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status Bimbingan:</span>
                                    <span class="font-medium capitalize">{{ $document->meeting->status }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Dosen Feedback -->
                    @if ($document->dosen_feedback)
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-comment-alt text-blue-600 mr-2"></i>Feedback Dosen
                            </h2>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-gray-900 whitespace-pre-line">{{ $document->dosen_feedback }}</p>
                                @if ($document->reviewed_at)
                                    <p class="text-sm text-gray-600 mt-2">
                                        Direview pada: {{ $document->reviewed_at->format('d M Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column - Actions & Info -->
                <div class="space-y-6">
                    <!-- Action Buttons -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h2>

                        <!-- Download Button -->
                        <a href="{{ route('documents.download', $document) }}"
                            class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium transition duration-200 flex items-center justify-center mb-3">
                            <i class="fas fa-download mr-2"></i>Download File
                        </a>

                        <!-- Dosen Review Actions -->
                        @if (Auth::user()->isDosen() && $document->canReview())
                            <div class="space-y-3 mt-4">
                                <form action="{{ route('documents.approve', $document) }}" method="POST"
                                    class="space-y-3">
                                    @csrf
                                    <div>
                                        <label for="approve_feedback"
                                            class="block text-sm font-medium text-gray-700 mb-1">
                                            Feedback (Opsional)
                                        </label>
                                        <textarea name="feedback" id="approve_feedback" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                            placeholder="Berikan feedback atau catatan positif..."></textarea>
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium transition duration-200 flex items-center justify-center">
                                        <i class="fas fa-check mr-2"></i>Setujui Dokumen
                                    </button>
                                </form>

                                <form action="{{ route('documents.reject', $document) }}" method="POST"
                                    class="space-y-3">
                                    @csrf
                                    <div>
                                        <label for="reject_feedback"
                                            class="block text-sm font-medium text-gray-700 mb-1">
                                            Feedback (Wajib untuk penolakan)
                                        </label>
                                        <textarea name="dosen_feedback" id="reject_feedback" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                            placeholder="Jelaskan alasan penolakan dan saran perbaikan..." required></textarea>
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium transition duration-200 flex items-center justify-center">
                                        <i class="fas fa-times mr-2"></i>Tolak Dokumen
                                    </button>
                                </form>
                            </div>
                        @endif

                        <!-- Mahasiswa Actions -->
                        @if (Auth::user()->isMahasiswa() && $document->canEdit())
                            <form action="{{ route('documents.submit', $document) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 font-medium transition duration-200 flex items-center justify-center mt-3"
                                    onclick="return confirm('Submit dokumen untuk review dosen?')">
                                    <i class="fas fa-paper-plane mr-2"></i>Submit untuk Review
                                </button>
                            </form>
                        @endif

                        <!-- Status Info -->
                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 text-center">
                                @if ($document->status == 'draft')
                                    <i class="fas fa-edit mr-1"></i>Dokumen dalam status draft
                                @elseif($document->status == 'submitted')
                                    <i class="fas fa-clock mr-1"></i>Menunggu review dosen
                                @elseif($document->status == 'approved')
                                    <i class="fas fa-check-circle mr-1"></i>Dokumen telah disetujui
                                @elseif($document->status == 'rejected')
                                    <i class="fas fa-times-circle mr-1"></i>Dokumen ditolak - perlu revisi
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Document Information -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Diupload oleh:</span>
                                <span class="font-medium">{{ $document->user->username }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal Upload:</span>
                                <span class="font-medium">{{ $document->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Terakhir Diupdate:</span>
                                <span class="font-medium">{{ $document->updated_at->format('d M Y H:i') }}</span>
                            </div>
                            @if ($document->reviewed_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Direview pada:</span>
                                    <span class="font-medium">{{ $document->reviewed_at->format('d M Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
