<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit SK - {{ $suratKeputusan->nomor_sk }} - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Edit Surat Keputusan</h1>
                        <p class="text-gray-600">{{ $suratKeputusan->nomor_sk }} -
                            {{ $suratKeputusan->mahasiswa->username }}</p>
                    </div>
                    <a href="{{ route('admin.surat-keputusan.show', $suratKeputusan) }}"
                        class="text-gray-600 hover:text-gray-800 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Detail
                    </a>
                </div>
            </div>

            <!-- Current Status -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user-graduate text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Mahasiswa</p>
                            <p class="font-medium">{{ $suratKeputusan->mahasiswa->username }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user-tie text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pembimbing 1</p>
                            <p class="font-medium">
                                @if ($existingPembimbing1)
                                    {{ $existingPembimbing1->dosen->username }}
                                @else
                                    <span class="text-red-500">Belum ditetapkan</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user-tie text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pembimbing 2</p>
                            <p class="font-medium">
                                @if ($existingPembimbing2)
                                    {{ $existingPembimbing2->dosen->username }}
                                @else
                                    <span class="text-red-500">Belum ditetapkan</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
                <form action="{{ route('admin.surat-keputusan.update', $suratKeputusan) }}" method="POST"
                    enctype="multipart/form-data" id="editSkForm">
                    @csrf
                    @method('PUT')

                    <!-- Info Admin & Mahasiswa -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Admin Pembuat:</p>
                                <p class="font-medium flex items-center">
                                    <i class="fas fa-user-shield mr-2"></i>
                                    {{ $suratKeputusan->admin->username }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Mahasiswa:</p>
                                <p class="font-medium flex items-center">
                                    <i class="fas fa-user-graduate mr-2"></i>
                                    {{ $suratKeputusan->mahasiswa->username }}
                                </p>
                                <input type="hidden" name="mahasiswa_id" value="{{ $suratKeputusan->mahasiswa_id }}">
                            </div>
                        </div>
                    </div>

                    <!-- Section 1: Informasi SK -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center pb-2 border-b">
                            <i class="fas fa-file-contract text-blue-600 mr-2"></i>
                            Informasi Surat Keputusan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Nomor SK -->
                            <div>
                                <label for="nomor_sk" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor SK *
                                </label>
                                <input type="text" name="nomor_sk" id="nomor_sk"
                                    value="{{ old('nomor_sk', $suratKeputusan->nomor_sk) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required placeholder="Contoh: 001/SK/TI/2024">
                                @error('nomor_sk')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal SK -->
                            <div>
                                <label for="tanggal_sk" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal SK *
                                </label>
                                <input type="date" name="tanggal_sk" id="tanggal_sk"
                                    value="{{ old('tanggal_sk', $suratKeputusan->tanggal_sk->format('Y-m-d')) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                @error('tanggal_sk')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tahun Akademik -->
                            <div>
                                <label for="tahun_akademik" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tahun Akademik *
                                </label>
                                <select name="tahun_akademik" id="tahun_akademik"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                    <option value="">Pilih Tahun Akademik</option>
                                    @foreach ($tahunAkademikOptions as $tahun)
                                        <option value="{{ $tahun }}"
                                            {{ old('tahun_akademik', $suratKeputusan->tahun_akademik) == $tahun ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tahun_akademik')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Semester -->
                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                                    Semester *
                                </label>
                                <select name="semester" id="semester"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                    <option value="">Pilih Semester</option>
                                    <option value="Ganjil"
                                        {{ old('semester', $suratKeputusan->semester) == 'Ganjil' ? 'selected' : '' }}>
                                        Semester Ganjil
                                    </option>
                                    <option value="Genap"
                                        {{ old('semester', $suratKeputusan->semester) == 'Genap' ? 'selected' : '' }}>
                                        Semester Genap
                                    </option>
                                </select>
                                @error('semester')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Dosen Pembimbing -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center pb-2 border-b">
                            <i class="fas fa-users text-purple-600 mr-2"></i>
                            Dosen Pembimbing (Edit Assignment)
                        </h3>

                        <!-- Pembimbing 1 -->
                        <div class="mb-6 p-4 border border-blue-200 bg-blue-50 rounded-lg">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-award text-blue-600"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800">Pembimbing Utama (Pembimbing 1)</h4>
                            </div>

                            <div>
                                <label for="pembimbing1_dosen_id"
                                    class="block text-sm font-medium text-gray-700 mb-2">
                                    Dosen Pembimbing 1 *
                                    <span class="text-xs text-gray-500">(Saat ini:
                                        @if ($existingPembimbing1)
                                            {{ $existingPembimbing1->dosen->username }}
                                        @else
                                            Belum ditetapkan
                                        @endif
                                        )
                                    </span>
                                </label>
                                <select name="pembimbing1_dosen_id" id="pembimbing1_dosen_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pembimbing-select"
                                    required>
                                    <option value="">Pilih Dosen Pembimbing 1</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->id }}"
                                            {{ old('pembimbing1_dosen_id', $existingPembimbing1->dosen_id ?? '') == $dosen->id ? 'selected' : '' }}
                                            data-email="{{ $dosen->email }}">
                                            {{ $dosen->username }} - {{ $dosen->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pembimbing1_dosen_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Pembimbing 2 -->
                        <div class="p-4 border border-green-200 bg-green-50 rounded-lg">
                            <div class="flex items-center mb-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-award text-green-600"></i>
                                </div>
                                <h4 class="font-semibold text-gray-800">Pembimbing Pendamping (Pembimbing 2)</h4>
                            </div>

                            <div>
                                <label for="pembimbing2_dosen_id"
                                    class="block text-sm font-medium text-gray-700 mb-2">
                                    Dosen Pembimbing 2 *
                                    <span class="text-xs text-gray-500">(Saat ini:
                                        @if ($existingPembimbing2)
                                            {{ $existingPembimbing2->dosen->username }}
                                        @else
                                            Belum ditetapkan
                                        @endif
                                        )
                                    </span>
                                </label>
                                <select name="pembimbing2_dosen_id" id="pembimbing2_dosen_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pembimbing-select"
                                    required>
                                    <option value="">Pilih Dosen Pembimbing 2</option>
                                    @foreach ($dosens as $dosen)
                                        <option value="{{ $dosen->id }}"
                                            {{ old('pembimbing2_dosen_id', $existingPembimbing2->dosen_id ?? '') == $dosen->id ? 'selected' : '' }}
                                            data-email="{{ $dosen->email }}">
                                            {{ $dosen->username }} - {{ $dosen->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pembimbing2_dosen_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Error message untuk pembimbing sama -->
                            <div id="pembimbingError"
                                class="mt-3 p-2 bg-red-50 border border-red-200 rounded text-sm text-red-700 hidden">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                <span>Pembimbing 1 dan Pembimbing 2 harus berbeda dosen!</span>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: File dan Keterangan -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center pb-2 border-b">
                            <i class="fas fa-paperclip text-gray-600 mr-2"></i>
                            Lampiran dan Keterangan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- File SK -->
                            <div>
                                <label for="file_sk" class="block text-sm font-medium text-gray-700 mb-2">
                                    File SK (PDF)
                                </label>

                                <!-- Current File Info -->
                                @if ($suratKeputusan->file_sk)
                                    <div class="mb-3 p-3 bg-gray-50 rounded-lg border">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">File saat ini</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ basename($suratKeputusan->file_sk) }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ route('admin.surat-keputusan.download', $suratKeputusan) }}"
                                                class="text-blue-600 hover:text-blue-800 text-sm flex items-center"
                                                title="Download file">
                                                <i class="fas fa-download mr-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors">
                                    <input type="file" name="file_sk" id="file_sk" accept=".pdf"
                                        class="hidden" onchange="updateFileName(this)">
                                    <label for="file_sk" class="cursor-pointer block">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                        <p class="text-sm text-gray-600">Klik untuk upload file PDF baru</p>
                                        <p class="text-xs text-gray-500">Maksimal 10MB</p>
                                    </label>
                                    <div id="fileName" class="mt-2 text-sm text-blue-600"></div>
                                </div>
                                @error('file_sk')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Keterangan Tambahan
                                </label>
                                <textarea name="keterangan" id="keterangan" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Catatan khusus, informasi tambahan, atau instruksi...">{{ old('keterangan', $suratKeputusan->keterangan) }}</textarea>
                                @error('keterangan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Validasi dan Submit -->
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-yellow-600 mt-1 mr-3 text-lg"></i>
                            <div>
                                <h4 class="font-medium text-yellow-800 mb-2">Validasi Perubahan</h4>
                                <ul class="text-sm text-yellow-700 space-y-1">
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        <span>Mahasiswa: <span
                                                class="font-medium">{{ $suratKeputusan->mahasiswa->username }}</span></span>
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        <span>Pembimbing 1: <span id="valPembimbing1" class="font-medium">
                                                @if ($existingPembimbing1)
                                                    {{ $existingPembimbing1->dosen->username }}
                                                @else
                                                    Belum dipilih
                                                @endif
                                            </span></span>
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        <span>Pembimbing 2: <span id="valPembimbing2" class="font-medium">
                                                @if ($existingPembimbing2)
                                                    {{ $existingPembimbing2->dosen->username }}
                                                @else
                                                    Belum dipilih
                                                @endif
                                            </span></span>
                                    </li>
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                        <span>Pembimbing berbeda: <span id="valPembimbingBeda" class="font-medium">
                                                @if ($existingPembimbing1 && $existingPembimbing2 && $existingPembimbing1->dosen_id != $existingPembimbing2->dosen_id)
                                                    ✓ Ya
                                                @elseif($existingPembimbing1 && $existingPembimbing2)
                                                    ✗ Sama!
                                                @else
                                                    Belum dicek
                                                @endif
                                            </span></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message if pembimbing sama -->
                    @if ($errors->has('pembimbing_sama'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                <p class="text-sm text-red-800">{{ $errors->first('pembimbing_sama') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Warning -->
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-red-600 mt-1 mr-2"></i>
                            <div>
                                <p class="text-sm text-red-800 font-medium mb-1">Perhatian!</p>
                                <p class="text-sm text-red-700">
                                    Mengedit SK akan memperbarui hubungan pembimbing. Pastikan data baru sudah benar.
                                    Semua meeting dan logbook berdasarkan SK ini akan mengikuti pembimbing baru.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <!-- Di dalam form EDIT -->
                    <div class="flex justify-between items-center pt-6 border-t">
                        <div>
                            <a href="{{ route('admin.surat-keputusan.show', $suratKeputusan) }}?confirm_delete=true"
                                class="px-5 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition duration-200 flex items-center">
                                <i class="fas fa-trash mr-2"></i>
                                Hapus SK
                            </a>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.surat-keputusan.show', $suratKeputusan) }}"
                                class="px-5 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 flex items-center">
                                <i class="fas fa-times mr-2"></i>
                                Batalkan
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center shadow-sm"
                                id="submitBtn">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pembimbing1Select = document.getElementById('pembimbing1_dosen_id');
            const pembimbing2Select = document.getElementById('pembimbing2_dosen_id');
            const pembimbingError = document.getElementById('pembimbingError');
            const form = document.getElementById('editSkForm');

            // Elements untuk validasi status
            const valPembimbing1 = document.getElementById('valPembimbing1');
            const valPembimbing2 = document.getElementById('valPembimbing2');
            const valPembimbingBeda = document.getElementById('valPembimbingBeda');
            const submitBtn = document.getElementById('submitBtn');

            // Update validasi status untuk pembimbing
            function updatePembimbingValidation() {
                const p1Selected = pembimbing1Select.value;
                const p2Selected = pembimbing2Select.value;

                // Update status pembimbing 1
                if (p1Selected) {
                    const p1Name = pembimbing1Select.options[pembimbing1Select.selectedIndex].text.split(' - ')[0];
                    valPembimbing1.textContent = p1Name;
                    valPembimbing1.classList.add('text-green-600');
                } else {
                    valPembimbing1.textContent = 'Belum dipilih';
                    valPembimbing1.classList.remove('text-green-600');
                }

                // Update status pembimbing 2
                if (p2Selected) {
                    const p2Name = pembimbing2Select.options[pembimbing2Select.selectedIndex].text.split(' - ')[0];
                    valPembimbing2.textContent = p2Name;
                    valPembimbing2.classList.add('text-green-600');
                } else {
                    valPembimbing2.textContent = 'Belum dipilih';
                    valPembimbing2.classList.remove('text-green-600');
                }

                // Check jika pembimbing sama
                if (p1Selected && p2Selected && p1Selected === p2Selected) {
                    pembimbingError.classList.remove('hidden');
                    valPembimbingBeda.textContent = '✗ Sama!';
                    valPembimbingBeda.classList.add('text-red-600');
                    valPembimbingBeda.classList.remove('text-green-600');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else if (p1Selected && p2Selected) {
                    pembimbingError.classList.add('hidden');
                    valPembimbingBeda.textContent = '✓ Berbeda';
                    valPembimbingBeda.classList.add('text-green-600');
                    valPembimbingBeda.classList.remove('text-red-600');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    pembimbingError.classList.add('hidden');
                    valPembimbingBeda.textContent = 'Belum dicek';
                    valPembimbingBeda.classList.remove('text-green-600', 'text-red-600');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            // Event listeners untuk pembimbing selects
            pembimbing1Select.addEventListener('change', updatePembimbingValidation);
            pembimbing2Select.addEventListener('change', updatePembimbingValidation);

            // Validasi form sebelum submit
            form.addEventListener('submit', function(event) {
                // Validasi pembimbing harus berbeda
                if (pembimbing1Select.value && pembimbing2Select.value &&
                    pembimbing1Select.value === pembimbing2Select.value) {
                    event.preventDefault();
                    pembimbingError.classList.remove('hidden');
                    pembimbing2Select.focus();

                    // Scroll ke error
                    pembimbingError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                    // Shake animation untuk error
                    pembimbingError.classList.add('animate-pulse');
                    setTimeout(() => {
                        pembimbingError.classList.remove('animate-pulse');
                    }, 1000);

                    return false;
                }
            });

            // Toggle file upload based on keep_file checkbox
            const keepFileCheckbox = document.getElementById('keep_file');
            const fileInput = document.getElementById('file_sk');

            if (keepFileCheckbox) {
                function toggleFileInput() {
                    if (keepFileCheckbox.checked) {
                        fileInput.disabled = true;
                        fileInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                    } else {
                        fileInput.disabled = false;
                        fileInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    }
                }

                keepFileCheckbox.addEventListener('change', toggleFileInput);
                toggleFileInput(); // Initial state
            }

            // Auto set tahun akademik based on date
            const dateInput = document.getElementById('tanggal_sk');
            const tahunAkademikSelect = document.getElementById('tahun_akademik');
            const semesterSelect = document.getElementById('semester');

            if (dateInput) {
                dateInput.addEventListener('change', function() {
                    const selectedDate = new Date(this.value);
                    const year = selectedDate.getFullYear();
                    const month = selectedDate.getMonth() + 1;

                    // Determine academic year
                    let academicYear;
                    if (month >= 8) { // August to December
                        academicYear = year + '/' + (year + 1);
                    } else { // January to July
                        academicYear = (year - 1) + '/' + year;
                    }

                    // Set semester
                    if (month >= 8 || month <= 1) { // August to January
                        semesterSelect.value = 'Ganjil';
                    } else { // February to July
                        semesterSelect.value = 'Genap';
                    }

                    // Try to find and select academic year
                    for (let option of tahunAkademikSelect.options) {
                        if (option.value === academicYear) {
                            option.selected = true;
                            break;
                        }
                    }
                });
            }

            // Initial validation check
            updatePembimbingValidation();
        });

        // Function untuk update nama file yang diupload
        function updateFileName(input) {
            const fileNameDiv = document.getElementById('fileName');
            if (input.files.length > 0) {
                fileNameDiv.textContent = 'File baru: ' + input.files[0].name;
                fileNameDiv.classList.add('text-green-600');
            } else {
                fileNameDiv.textContent = '';
                fileNameDiv.classList.remove('text-green-600');
            }
        }

        // Function untuk konfirmasi hapus
        function confirmDelete() {
            if (confirm(
                    'HAPUS Surat Keputusan ini?\n\nTindakan ini akan:\n• Menghapus semua data pembimbing terkait\n• Memutus hubungan dosen-mahasiswa\n• TIDAK dapat dikembalikan!\n\nTekan OK untuk menghapus atau Cancel untuk batal.'
                )) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>

    <style>
        /* Animasi untuk error */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .animate-pulse {
            animation: pulse 0.5s ease-in-out 2;
        }

        /* Style untuk select option yang sedang dipilih */
        .pembimbing-select option:checked {
            background-color: #e0f2fe;
            color: #0c4a6e;
        }

        /* Smooth transitions */
        select,
        input,
        textarea,
        button {
            transition: all 0.2s ease-in-out;
        }
    </style>
</body>

</html>
