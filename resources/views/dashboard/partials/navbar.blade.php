<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center py-3">
            <!-- Logo & Brand -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <div class="bg-blue-600 text-white p-2 rounded-lg">
                        <i class="fas fa-graduation-cap text-sm"></i>
                    </div>
                    <span class="text-lg font-bold text-gray-800">AsistenTA</span>
                </a>
                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-medium capitalize">
                    {{ Auth::user()->role }}
                </span>
            </div>

            <!-- Navigation Menu -->
            <div class="flex items-center space-x-1">
                @if (Auth::user()->isDosen())
                    <!-- Menu untuk DOSEN -->
                    @php $hasMahasiswa = Auth::user()->hasMahasiswaBimbinganAktif(); @endphp

                    <!-- Menu Bimbingan (selalu aktif untuk dosen) -->
                    <a href="{{ route('meetings.index') }}"
                        class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                        <i class="fas fa-calendar-check mr-1"></i>Bimbingan
                    </a>

                    <!-- Menu Logbook (conditional) -->
                    @if ($hasMahasiswa)
                        <a href="{{ route('logs.index') }}"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-clipboard-list mr-1"></i>Logbook
                        </a>
                    @else
                        <span
                            class="text-gray-400 px-3 py-2 rounded-md text-sm font-medium cursor-not-allowed relative group">
                            <i class="fas fa-clipboard-list mr-1"></i>Logbook
                            <div
                                class="absolute hidden group-hover:block w-48 bg-gray-800 text-white text-xs rounded p-2 -bottom-10 left-0 z-50">
                                Butuh mahasiswa bimbingan
                            </div>
                        </span>
                    @endif

                    <!-- Menu Dokumen (conditional) -->
                    @if ($hasMahasiswa)
                        <a href="{{ route('documents.index') }}"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-file mr-1"></i>Dokumen
                        </a>
                    @else
                        <span
                            class="text-gray-400 px-3 py-2 rounded-md text-sm font-medium cursor-not-allowed relative group">
                            <i class="fas fa-file mr-1"></i>Dokumen
                            <div
                                class="absolute hidden group-hover:block w-48 bg-gray-800 text-white text-xs rounded p-2 -bottom-10 left-0 z-50">
                                Butuh mahasiswa bimbingan
                            </div>
                        </span>
                    @endif

                    <!-- Menu Jadwal (conditional) -->
                    @if ($hasMahasiswa)
                        <a href="{{ route('availability.index') }}"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-calendar-plus mr-1"></i>Jadwal
                        </a>
                    @else
                        <span
                            class="text-gray-400 px-3 py-2 rounded-md text-sm font-medium cursor-not-allowed relative group">
                            <i class="fas fa-calendar-plus mr-1"></i>Jadwal
                            <div
                                class="absolute hidden group-hover:block w-48 bg-gray-800 text-white text-xs rounded p-2 -bottom-10 left-0 z-50">
                                Butuh mahasiswa bimbingan
                            </div>
                        </span>
                    @endif
                @elseif (Auth::user()->isMahasiswa())
                    <!-- Menu untuk MAHASISWA -->
                    @php $hasActiveSK = Auth::user()->hasActiveSK(); @endphp

                    <!-- Menu Bimbingan (conditional) -->
                    @if ($hasActiveSK)
                        <a href="{{ route('meetings.index') }}"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-calendar-check mr-1"></i>Bimbingan
                        </a>
                    @else
                        <span
                            class="text-gray-400 px-3 py-2 rounded-md text-sm font-medium cursor-not-allowed relative group">
                            <i class="fas fa-calendar-check mr-1"></i>Bimbingan
                            <div
                                class="absolute hidden group-hover:block w-48 bg-gray-800 text-white text-xs rounded p-2 -bottom-10 left-0 z-50">
                                Butuh SK Pembimbing aktif
                            </div>
                        </span>
                    @endif

                    <!-- Menu Logbook (conditional) -->
                    @if ($hasActiveSK)
                        <a href="{{ route('logs.index') }}"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-clipboard-list mr-1"></i>Logbook
                        </a>
                    @else
                        <span
                            class="text-gray-400 px-3 py-2 rounded-md text-sm font-medium cursor-not-allowed relative group">
                            <i class="fas fa-clipboard-list mr-1"></i>Logbook
                            <div
                                class="absolute hidden group-hover:block w-48 bg-gray-800 text-white text-xs rounded p-2 -bottom-10 left-0 z-50">
                                Butuh SK Pembimbing aktif
                            </div>
                        </span>
                    @endif

                    <!-- Menu Dokumen (conditional) -->
                    @if ($hasActiveSK)
                        <a href="{{ route('documents.index') }}"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-file mr-1"></i>Dokumen
                        </a>
                    @else
                        <span
                            class="text-gray-400 px-3 py-2 rounded-md text-sm font-medium cursor-not-allowed relative group">
                            <i class="fas fa-file mr-1"></i>Dokumen
                            <div
                                class="absolute hidden group-hover:block w-48 bg-gray-800 text-white text-xs rounded p-2 -bottom-10 left-0 z-50">
                                Butuh SK Pembimbing aktif
                            </div>
                        </span>
                    @endif

                    <!-- Menu Ajukan Bimbingan (conditional) -->
                    @if ($hasActiveSK)
                        <a href="{{ route('available.slots') }}"
                            class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            <i class="fas fa-calendar-plus mr-1"></i>Ajukan Bimbingan
                        </a>
                    @else
                        <span
                            class="text-gray-400 px-3 py-2 rounded-md text-sm font-medium cursor-not-allowed relative group">
                            <i class="fas fa-calendar-plus mr-1"></i>Ajukan Bimbingan
                            <div
                                class="absolute hidden group-hover:block w-48 bg-gray-800 text-white text-xs rounded p-2 -bottom-10 left-0 z-50">
                                Butuh SK Pembimbing aktif
                            </div>
                        </span>
                    @endif
                @elseif (Auth::user()->isAdmin())
                    <!-- Menu untuk ADMIN (selalu aktif) -->
                    <a href="{{ route('admin.surat-keputusan.index') }}"
                        class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                        <i class="fas fa-cog mr-1"></i>Kelola SK
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                        <i class="fas fa-users mr-1"></i>Kelola Pengguna
                    </a>
                @endif
            </div>

            <!-- User Menu -->
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-600 hidden sm:block">
                    Halo, <span class="font-semibold">{{ Auth::user()->username }}</span>
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-red-600 text-white px-3 py-1.5 rounded text-sm hover:bg-red-700 font-medium transition duration-200 flex items-center">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
