<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail SK - {{ $suratKeputusan->nomor_sk }} - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Detail Surat Keputusan</h1>
                        <p class="text-gray-600">{{ $suratKeputusan->nomor_sk }} - {{ $suratKeputusan->tahun_akademik }}
                            {{ $suratKeputusan->semester }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.surat-keputusan.index') }}"
                            class="text-gray-600 hover:text-gray-800 flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user-graduate text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Mahasiswa</p>
                            <p class="font-medium text-gray-900">{{ $suratKeputusan->mahasiswa->username }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user-tie text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pembimbing 1</p>
                            <p class="font-medium text-gray-900">
                                @if ($suratKeputusan->pembimbing1())
                                    {{ $suratKeputusan->pembimbing1()->username }}
                                @else
                                    <span class="text-red-500">Belum</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user-tie text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pembimbing 2</p>
                            <p class="font-medium text-gray-900">
                                @if ($suratKeputusan->pembimbing2())
                                    {{ $suratKeputusan->pembimbing2()->username }}
                                @else
                                    <span class="text-red-500">Belum</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                    <div class="flex items-center">
                        <div
                            class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-file-contract text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="font-medium text-gray-900">
                                @if ($suratKeputusan->hasCompletePembimbing())
                                    <span class="text-green-600">Lengkap</span>
                                @else
                                    <span class="text-yellow-600">Incomplete</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Card 1: Informasi SK -->
                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm lg:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center pb-2 border-b">
                        <i class="fas fa-file-contract text-blue-600 mr-2"></i>
                        Informasi Surat Keputusan
                    </h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Nomor SK</p>
                                <p class="font-medium text-gray-900">{{ $suratKeputusan->nomor_sk }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Tanggal SK</p>
                                <p class="font-medium text-gray-900">{{ $suratKeputusan->getFormattedTanggal() }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Tahun Akademik</p>
                                <p class="font-medium text-gray-900">{{ $suratKeputusan->tahun_akademik }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Semester</p>
                                <p class="font-medium text-gray-900">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $suratKeputusan->semester == 'Ganjil' ? 'bg-purple-100 text-purple-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ $suratKeputusan->semester }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        @if ($suratKeputusan->keterangan)
                            <div>
                                <p class="text-sm text-gray-600">Keterangan</p>
                                <div class="mt-1 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                    <p class="text-gray-700">{{ $suratKeputusan->keterangan }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t">
                            <div>
                                <p class="text-sm text-gray-600">Dibuat oleh Admin</p>
                                <p class="font-medium text-gray-900 flex items-center">
                                    <i class="fas fa-user-shield mr-2 text-blue-500"></i>
                                    {{ $suratKeputusan->admin->username }}
                                </p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Dibuat pada</p>
                                <p class="font-medium text-gray-900">
                                    {{ $suratKeputusan->created_at->format('d F Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: File SK -->
                <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center pb-2 border-b">
                        <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                        File Surat Keputusan
                    </h3>

                    @if ($suratKeputusan->file_sk)
                        <div
                            class="text-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-400 transition-colors">
                            <i class="fas fa-file-pdf text-5xl text-red-500 mb-3"></i>
                            <p class="text-sm font-medium text-gray-900 mb-1">File SK tersedia</p>
                            <p class="text-xs text-gray-500 mb-3">{{ basename($suratKeputusan->file_sk) }}</p>

                            <div class="flex flex-col space-y-2">
                                <a href="{{ route('admin.surat-keputusan.download', $suratKeputusan) }}"
                                    class="px-4 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-download mr-2"></i>
                                    Download File
                                </a>

                                <a href="{{ route('admin.surat-keputusan.edit', $suratKeputusan) }}?tab=file"
                                    class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-edit mr-2"></i>
                                    Ganti File
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center p-6 border-2 border-dashed border-gray-300 rounded-lg">
                            <i class="fas fa-file text-4xl text-gray-400 mb-3"></i>
                            <p class="text-sm text-gray-500 mb-3">Tidak ada file SK diupload</p>
                            <a href="{{ route('admin.surat-keputusan.edit', $suratKeputusan) }}?tab=file"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-200">
                                <i class="fas fa-upload mr-2"></i>
                                Upload File
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card 3: Detail Mahasiswa dan Pembimbing -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center pb-2 border-b">
                    <i class="fas fa-users text-purple-600 mr-2"></i>
                    Detail Mahasiswa dan Dosen Pembimbing
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Mahasiswa Card -->
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user-graduate text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Mahasiswa</h4>
                                <p class="text-xs text-gray-500">Yang dibimbing</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div>
                                <p class="text-sm text-gray-600">Nama</p>
                                <p class="font-medium">{{ $suratKeputusan->mahasiswa->username }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium">{{ $suratKeputusan->mahasiswa->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">ID</p>
                                <p class="font-medium text-gray-500">{{ $suratKeputusan->mahasiswa_id }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pembimbing 1 Card -->
                    <div class="border rounded-lg p-4 bg-blue-50 border-blue-200">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user-tie text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Pembimbing 1</h4>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Utama
                                </span>
                            </div>
                        </div>

                        @if ($suratKeputusan->pembimbing1())
                            <div class="space-y-2">
                                <div>
                                    <p class="text-sm text-gray-600">Nama Dosen</p>
                                    <p class="font-medium">{{ $suratKeputusan->pembimbing1()->username }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Email</p>
                                    <p class="font-medium">{{ $suratKeputusan->pembimbing1()->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">ID</p>
                                    <p class="font-medium text-gray-500">{{ $suratKeputusan->pembimbing1()->id }}</p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <i class="fas fa-user-slash text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500">Belum ditetapkan</p>
                            </div>
                        @endif
                    </div>

                    <!-- Pembimbing 2 Card -->
                    <div class="border rounded-lg p-4 bg-green-50 border-green-200">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-user-tie text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Pembimbing 2</h4>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Pendamping
                                </span>
                            </div>
                        </div>

                        @if ($suratKeputusan->pembimbing2())
                            <div class="space-y-2">
                                <div>
                                    <p class="text-sm text-gray-600">Nama Dosen</p>
                                    <p class="font-medium">{{ $suratKeputusan->pembimbing2()->username }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Email</p>
                                    <p class="font-medium">{{ $suratKeputusan->pembimbing2()->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">ID</p>
                                    <p class="font-medium text-gray-500">{{ $suratKeputusan->pembimbing2()->id }}</p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <i class="fas fa-user-slash text-3xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500">Belum ditetapkan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-6 border-t">
                <div>
                    @if ($suratKeputusan->file_sk)
                        <a href="{{ route('admin.surat-keputusan.download', $suratKeputusan) }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            <i class="fas fa-download mr-2"></i>
                            Download PDF
                        </a>
                    @endif
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('admin.surat-keputusan.index') }}"
                        class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 flex items-center">
                        <i class="fas fa-list mr-2"></i>
                        Daftar SK
                    </a>
                    <a href="{{ route('admin.surat-keputusan.edit', $suratKeputusan) }}"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center shadow-sm">
                        <i class="fas fa-edit mr-2"></i>
                        Edit SK
                    </a>
                </div>
            </div>

            <!-- Quick Stats Footer -->
            @if ($suratKeputusan->hasCompletePembimbing())
                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                        <div>
                            <h4 class="font-medium text-green-800">SK Lengkap dan Aktif</h4>
                            <p class="text-sm text-green-700">
                                Mahasiswa {{ $suratKeputusan->mahasiswa->username }} telah memiliki 2 dosen pembimbing
                                yang lengkap.
                                SK dapat digunakan untuk meeting, logbook, dan dokumentasi bimbingan.
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mr-3"></i>
                        <div>
                            <h4 class="font-medium text-yellow-800">SK Belum Lengkap</h4>
                            <p class="text-sm text-yellow-700">
                                SK ini belum memiliki 2 dosen pembimbing. Silakan edit untuk melengkapi data pembimbing.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>

</html>
