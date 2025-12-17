<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Dokumen - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        @if (Auth::user()->isMahasiswa())
                            Dokumen Saya
                        @elseif(Auth::user()->isDosen())
                            Dokumen Bimbingan
                        @else
                            Manajemen Dokumen
                        @endif
                    </h1>
                    <p class="text-gray-600">
                        @if (Auth::user()->isMahasiswa())
                            Kelola dokumen bimbingan Anda
                        @elseif(Auth::user()->isDosen())
                            Kelola dokumen bimbingan mahasiswa dan materi ajar
                        @else
                            Kelola semua dokumen sistem
                        @endif
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('dashboard') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 font-medium transition duration-200">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>

                    @if (Auth::user()->isMahasiswa() || Auth::user()->isDosen())
                        <a href="{{ route('documents.create') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                            <i class="fas fa-upload mr-2"></i>Upload Dokumen
                        </a>
                    @endif

                    @if (Auth::user()->isDosen())
                        <a href="{{ route('documents.review.index') }}"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium transition duration-200">
                            <i class="fas fa-check-circle mr-2"></i>Review Dokumen
                        </a>
                    @endif
                </div>
            </div>

            @if (session('success'))
                <div class="flash-message bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="flash-message bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                @php
                    $stats = [
                        'total' => $documents->count(),
                        'draft' => $documents->where('status', 'draft')->count(),
                        'submitted' => $documents->where('status', 'submitted')->count(),
                        'approved' => $documents->where('status', 'approved')->count(),
                    ];
                @endphp
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Dokumen</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <i class="fas fa-file text-blue-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Draft</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['draft'] }}</h3>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <i class="fas fa-edit text-gray-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Menunggu Review</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['submitted'] }}</h3>
                        </div>
                        <div class="bg-yellow-50 p-3 rounded-lg">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Disetujui</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $stats['approved'] }}</h3>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Daftar Dokumen</h2>
                </div>

                @if ($documents->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach ($documents as $document)
                            <div class="px-6 py-4 hover:bg-gray-50 transition duration-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-file text-blue-600 text-lg"></i>
                                                </div>
                                            </div>

                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-800 text-lg">{{ $document->title }}
                                                </h3>
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mt-2">
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <i class="fas fa-tag mr-2"></i>
                                                        {{ $document->getCategoryText() }}
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <i class="fas fa-user mr-2"></i>
                                                        @if (Auth::user()->isMahasiswa())
                                                            @if ($document->user->isDosen())
                                                                <span class="text-blue-600 font-medium">Materi dari
                                                                    Dosen: {{ $document->user->username }}</span>
                                                            @else
                                                                Dokumen Saya
                                                            @endif
                                                        @else
                                                            @if ($document->user->isDosen())
                                                                Dosen: {{ $document->user->username }}
                                                            @else
                                                                Mahasiswa: {{ $document->user->username }}
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <i class="fas fa-calendar mr-2"></i>
                                                        {{ $document->created_at->format('d M Y H:i') }}
                                                    </div>
                                                </div>
                                                @if ($document->description)
                                                    <p class="text-sm text-gray-600 mt-2">
                                                        {{ Str::limit($document->description, 100) }}</p>
                                                @endif
                                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                                    <span><i class="fas fa-file-alt mr-1"></i>
                                                        {{ $document->file_name }}</span>
                                                    <span><i class="fas fa-weight-hanging mr-1"></i>
                                                        {{ $document->getFileSizeFormatted() }}</span>
                                                    <span><i class="fas fa-code mr-1"></i>
                                                        {{ strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $document->status == 'draft'
                                        ? 'bg-gray-100 text-gray-800'
                                        : ($document->status == 'submitted'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : ($document->status == 'approved'
                                                ? 'bg-green-100 text-green-800'
                                                : ($document->status == 'rejected'
                                                    ? 'bg-red-100 text-red-800'
                                                    : 'bg-gray-100 text-gray-800'))) }}">
                                            {{ $document->getStatusText() }}
                                        </span>

                                        <a href="{{ route('documents.download', $document) }}"
                                            class="text-green-600 hover:text-green-800 transition duration-200"
                                            title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>

                                        @if (Auth::user()->isMahasiswa() && $document->canEdit())
                                            <a href="{{ route('documents.edit', $document) }}"
                                                class="text-blue-600 hover:text-blue-800 transition duration-200"
                                                title="Edit Dokumen">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if (Auth::user()->isMahasiswa() && $document->canEdit())
                                            <form action="{{ route('documents.submit', $document) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Submit dokumen untuk review dosen?')">
                                                @csrf
                                                <button type="submit"
                                                    class="text-yellow-600 hover:text-yellow-800 transition duration-200"
                                                    title="Submit untuk Review">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if ($document->user->isDosen())
                                            <span class="text-xs text-blue-600 font-medium" title="Dokumen dari dosen">
                                                <i class="fas fa-chalkboard-teacher"></i>
                                            </span>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-file-upload text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-600 mb-2">
                            @if (Auth::user()->isMahasiswa())
                                Belum ada dokumen
                            @else
                                Belum ada dokumen mahasiswa
                            @endif
                        </h3>
                        <p class="text-gray-500 mb-4">
                            @if (Auth::user()->isMahasiswa())
                                Mulai dengan mengupload dokumen pertama Anda
                            @else
                                Mahasiswa belum mengupload dokumen
                            @endif
                        </p>
                        @if (Auth::user()->isMahasiswa() || Auth::user()->isDosen())
                            <a href="{{ route('documents.create') }}"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                                <i class="fas fa-upload mr-2"></i>Upload Dokumen Pertama
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Auto-hide flash messages
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                // PERBAIKAN 3: Gunakan selector spesifik '.flash-message'
                const flashMessages = document.querySelectorAll('.flash-message');
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
