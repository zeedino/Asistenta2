<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Jadwal - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Detail Jadwal Bimbingan</h1>
                <p class="text-gray-600">Informasi lengkap jadwal bimbingan</p>
            </div>

            <!-- Detail Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $availability->date->format('d F Y') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Waktu</label>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            {{ $availability->status == 'available' ? 'bg-green-100 text-green-800' :
                               ($availability->status == 'booked' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($availability->status) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dibuat Pada</label>
                        <p class="text-gray-800">{{ $availability->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                @if($availability->notes)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <p class="text-gray-800 bg-gray-50 p-4 rounded-lg">{{ $availability->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center">
                <a href="{{ route('availability.index') }}"
                   class="text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                </a>

                <div class="flex space-x-3">
                    <a href="{{ route('availability.edit', $availability) }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
