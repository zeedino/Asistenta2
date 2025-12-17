<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 min-h-screen">
    @include('dashboard.partials.navbar')

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
                <p class="text-gray-600">Selamat datang, {{ Auth::user()->username }}</p>
            </div>

            @if (session('success'))
                <div class="flash-message bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total Users</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</h3>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Active Users</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $activeUsers }}</h3>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Pending Users</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $pendingUsers }}</h3>
                        </div>
                        <div class="bg-yellow-50 p-3 rounded-lg">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total SK</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $totalSK ?? 0 }}</h3>
                        </div>
                        <div class="bg-purple-50 p-3 rounded-lg">
                            <i class="fas fa-file-contract text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('admin.surat-keputusan.create') }}"
                                class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                                <i class="fas fa-file-contract text-blue-600 mr-3"></i>
                                <span class="font-medium">Buat SK Baru</span>
                            </a>

                            <a href="{{ route('admin.surat-keputusan.index') }}"
                                class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                                <i class="fas fa-list text-green-600 mr-3"></i>
                                <span class="font-medium">Kelola SK</span>
                            </a>

                            <a href="{{ route('admin.users.create') }}"
                                class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                                <i class="fas fa-user-plus text-orange-600 mr-3"></i>
                                <span class="font-medium">Tambah User</span>
                            </a>

                            <a href="{{ route('admin.users.index') }}"
                                class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                                <i class="fas fa-users text-purple-600 mr-3"></i>
                                <span class="font-medium">Kelola Users</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Recent Users</h2>
                            <a href="{{ route('admin.users.index') }}"
                                class="text-blue-600 hover:text-blue-800 text-sm">
                                View All
                            </a>
                        </div>

                        @if ($recentUsers->count() > 0)
                            <div class="space-y-3">
                                @foreach ($recentUsers as $user)
                                    <div class="flex items-center justify-between py-2">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-800 truncate">{{ $user->username }}</p>
                                            <p class="text-sm text-gray-600 truncate">{{ $user->email }}</p>
                                        </div>
                                        <div class="ml-4">
                                            <span
                                                class="px-2 py-1 rounded text-xs font-medium
                                            {{ $user->role == 'admin'
                                                ? 'bg-purple-100 text-purple-800'
                                                : ($user->role == 'dosen'
                                                    ? 'bg-blue-100 text-blue-800'
                                                    : 'bg-green-100 text-green-800') }}">
                                                {{ $user->role }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No users registered</p>
                        @endif
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">System Status</h2>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Active Users:</span>
                                <span class="font-medium">{{ $activeUsers }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pending Users:</span>
                                <span class="font-medium text-yellow-600">{{ $pendingUsers }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Dosen:</span>
                                <span class="font-medium">{{ $totalDosen }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Mahasiswa:</span>
                                <span class="font-medium">{{ $totalMahasiswa }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
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
