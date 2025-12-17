<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keputusan - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Surat Keputusan Pembimbing</h1>
                        <p class="text-gray-600">Kelola SK penugasan dosen pembimbing untuk mahasiswa</p>
                    </div>
                    <a href="{{ route('admin.surat-keputusan.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                        <i class="fas fa-file-contract mr-2"></i>
                        Buat SK Baru
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div
                    class="flash-message bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    class="flash-message bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No. SK & Tanggal
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mahasiswa
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pembimbing 1
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pembimbing 2
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Periode
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    File
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($sks as $sk)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $sk->nomor_sk }}</div>
                                        <div class="text-xs text-gray-500">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $sk->getFormattedTanggal() }}
                                        </div>
                                        @if (!$sk->hasCompletePembimbing())
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Incomplete
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user-graduate text-green-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $sk->mahasiswa->username }}</div>
                                                <div class="text-xs text-gray-500">{{ $sk->mahasiswa->email }}</div>
                                                <div class="text-xs text-gray-400">ID: {{ $sk->mahasiswa_id }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($sk->pembimbing1())
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user-tie text-blue-600 text-sm"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $sk->pembimbing1()->username }}</div>
                                                    <div class="text-xs text-gray-500">{{ $sk->pembimbing1()->email }}
                                                    </div>
                                                </div>
                                            </div>
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                                <i class="fas fa-award mr-1"></i>
                                                Pembimbing 1
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400 italic">Belum ditetapkan</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($sk->pembimbing2())
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user-tie text-green-600 text-sm"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $sk->pembimbing2()->username }}</div>
                                                    <div class="text-xs text-gray-500">{{ $sk->pembimbing2()->email }}
                                                    </div>
                                                </div>
                                            </div>
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                <i class="fas fa-award mr-1"></i>
                                                Pembimbing 2
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400 italic">Belum ditetapkan</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $sk->tahun_akademik }}</div>
                                        <div class="text-xs text-gray-500">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                {{ $sk->semester == 'Ganjil' ? 'bg-purple-100 text-purple-800' : 'bg-pink-100 text-pink-800' }}">
                                                <i class="fas fa-graduation-cap mr-1"></i>
                                                {{ $sk->semester }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($sk->file_sk)
                                            <a href="{{ route('admin.surat-keputusan.download', $sk) }}"
                                                class="inline-flex items-center text-purple-600 hover:text-purple-800 bg-purple-50 px-3 py-1 rounded-lg text-sm"
                                                title="Download SK PDF">
                                                <i class="fas fa-file-pdf mr-1"></i>
                                                Download
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-sm flex items-center">
                                                <i class="fas fa-file mr-1"></i>
                                                No file
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('admin.surat-keputusan.show', $sk) }}"
                                                class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-2 rounded-lg text-sm flex items-center"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye mr-1"></i>
                                                View
                                            </a>
                                            <a href="{{ route('admin.surat-keputusan.edit', $sk) }}"
                                                class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 px-3 py-2 rounded-lg text-sm flex items-center"
                                                title="Edit">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.surat-keputusan.destroy', $sk) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-2 rounded-lg text-sm flex items-center"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus SK ini? Data pembimbing juga akan terhapus.')"
                                                    title="Hapus">
                                                    <i class="fas fa-trash mr-1"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-gray-400 mb-4">
                                            <i class="fas fa-file-contract text-5xl"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada Surat Keputusan
                                        </h3>
                                        <p class="text-gray-600 mb-4">Mulai dengan membuat SK untuk menetapkan dosen
                                            pembimbing</p>
                                        <a href="{{ route('admin.surat-keputusan.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                            <i class="fas fa-plus mr-2"></i>
                                            Buat SK Pertama
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($sks->count() > 0)
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-center">
                        <p class="text-sm text-gray-600">
                            Total: {{ $sks->count() }} Surat Keputusan
                            @if ($sks->count() > 20)
                                <span class="text-yellow-600 ml-2">
                                    <i class="fas fa-info-circle"></i> Data banyak, pertimbangkan pagination untuk
                                    production
                                </span>
                            @endif
                        </p>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-contract text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total SK</p>
                            <p class="text-xl font-semibold text-gray-900">{{ $sks->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-graduate text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Mahasiswa dengan SK</p>
                            <p class="text-xl font-semibold text-gray-900">
                                {{ $sks->unique('mahasiswa_id')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-tie text-purple-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Dosen Pembimbing Aktif</p>
                            <p class="text-xl font-semibold text-gray-900">
                                {{ $sks->flatMap->dosenPembimbing->unique('id')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-pdf text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">SK dengan File</p>
                            <p class="text-xl font-semibold text-gray-900">
                                {{ $sks->whereNotNull('file_sk')->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex items-center text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Dashboard Admin
                </a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide flash messages
            setTimeout(() => {
                // PERBAIKAN 3: Ganti selector menjadi .flash-message
                // Ini mencegah tombol Delete (yang pakai bg-red-50) ikut menghilang
                const flashMessages = document.querySelectorAll('.flash-message');
                flashMessages.forEach(message => {
                    message.style.transition = 'opacity 0.5s ease';
                    message.style.opacity = '0';
                    setTimeout(() => message.remove(), 500);
                });
            }, 5000);

            // Confirm sebelum hapus (kode asli Anda tetap aman)
            const deleteForms = document.querySelectorAll('form[action*="destroy"]');
            deleteForms.forEach(form => {
                const button = form.querySelector('button[type="submit"]');
                button.addEventListener('click', function(e) {
                    if (!confirm(
                            'Hapus SK ini? Semua data pembimbing terkait juga akan dihapus.')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>

</html>
