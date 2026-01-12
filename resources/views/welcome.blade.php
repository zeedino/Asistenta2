<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AsistenTA - Sistem Manajemen Bimbingan Skripsi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-2">
                    <div class="bg-blue-600 text-white p-2 rounded-lg">
                        <i class="fas fa-graduation-cap text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-800">AsistenTA</span>
                </div>

                <div class="flex items-center space-x-6">
                    <a href="{{ route('login.lihat') }}"
                        class="text-gray-600 hover:text-blue-600 font-medium transition duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="{{ route('register.lihat') }}"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium transition duration-200">
                        <i class="fas fa-user-plus mr-2"></i>Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="mb-8">
                <h1 class="text-5xl font-bold text-gray-800 mb-6">
                    Sistem Manajemen Bimbingan TA
                    <span class="block text-blue-600 text-4xl mt-2">Terintegrasi Data SK & Logbook</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Platform digital yang menghubungkan Mahasiswa dan Dosen Pembimbing secara valid berdasarkan Surat
                    Keputusan (SK).
                    Kelola jadwal, dokumen, dan progres skripsi dalam satu pintu.
                </p>
            </div>

            <div class="flex justify-center space-x-4 mb-12">
                <a href="{{ route('register.lihat') }}"
                    class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-semibold text-lg transition duration-200 shadow-lg">
                    <i class="fas fa-rocket mr-2"></i>Mulai Bimbingan
                </a>
                <a href="#features"
                    class="border border-blue-600 text-blue-600 px-8 py-3 rounded-lg hover:bg-blue-50 font-semibold text-lg transition duration-200">
                    <i class="fas fa-info-circle mr-2"></i>Pelajari Alur
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-check text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Booking Jadwal</h3>
                        <p class="text-gray-600 text-sm mt-2">Berdasarkan slot Availability Dosen</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Validasi Logbook</h3>
                        <p class="text-gray-600 text-sm mt-2">Rekam jejak bimbingan yang sah</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-file-upload text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Review Dokumen</h3>
                        <p class="text-gray-600 text-sm mt-2">Kelola Draft & Submission Revisi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Fitur Sistem</h2>
                <p class="text-xl text-gray-600">Dirancang sesuai prosedur akademik Tugas Akhir</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition duration-300">
                    <div class="bg-blue-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-clock text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Sistem Availability</h3>
                    <p class="text-gray-600">
                        Dosen membuka slot waktu (Available), mahasiswa memilih jadwal tersebut. Menghindari bentrok
                        jadwal bimbingan.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition duration-300">
                    <div class="bg-green-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-tasks text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Logbook & Validasi</h3>
                    <p class="text-gray-600">
                        Mahasiswa mencatat hasil bimbingan setelah pertemuan selesai. Dosen melakukan validasi logbook
                        sebagai syarat kelulusan.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition duration-300">
                    <div class="bg-purple-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-file-pdf text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Kontrol Dokumen</h3>
                    <p class="text-gray-600">
                        Upload dokumen sebagai <strong>Draft</strong> (pribadi) atau <strong>Submit</strong> untuk
                        direview dosen. Aman dan terorganisir.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition duration-300">
                    <div class="bg-orange-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-orange-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Berbasis SK Aktif</h3>
                    <p class="text-gray-600">
                        Akses fitur terkunci otomatis. Mahasiswa hanya bisa bimbingan dengan Dosen yang tertera di Surat
                        Keputusan (SK) aktif.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition duration-300">
                    <div class="bg-red-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Status Real-time</h3>
                    <p class="text-gray-600">
                        Pantau status dokumen (Approved/Rejected) dan status jadwal bimbingan (Confirmed/Pending)
                        langsung dari dashboard.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition duration-300">
                    <div class="bg-indigo-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-history text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Riwayat Lengkap</h3>
                    <p class="text-gray-600">
                        Semua riwayat bimbingan, revisi dokumen, dan catatan feedback dosen tersimpan rapi dan tidak
                        akan hilang.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">Ekosistem Akademik</h2>
                <p class="text-xl opacity-90">Satu sistem untuk kolaborasi yang lebih baik</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white bg-opacity-10 rounded-2xl p-8 backdrop-blur-sm">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-400 w-14 h-14 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user-graduate text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold">Mahasiswa</h3>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-300 mr-3"></i>
                            Pilih jadwal bimbingan sesuai slot dosen
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-300 mr-3"></i>
                            Upload & kelola revisi dokumen skripsi
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-300 mr-3"></i>
                            Isi logbook bimbingan untuk divalidasi
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-300 mr-3"></i>
                            Lihat status SK dan Pembimbing Aktif
                        </li>
                    </ul>
                </div>

                <div class="bg-white bg-opacity-10 rounded-2xl p-8 backdrop-blur-sm">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-400 w-14 h-14 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-chalkboard-teacher text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold">Dosen Pembimbing</h3>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-blue-300 mr-3"></i>
                            Atur ketersediaan waktu (Availability)
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-blue-300 mr-3"></i>
                            Review dokumen dan berikan feedback/revisi
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-blue-300 mr-3"></i>
                            Validasi logbook mahasiswa bimbingan
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-blue-300 mr-3"></i>
                            Akses daftar mahasiswa sesuai SK
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-gray-800 mb-6">Siap Menyelesaikan Skripsi?</h2>
            <p class="text-xl text-gray-600 mb-8">
                Pastikan data Anda terdaftar. Segera login untuk mengecek status SK dan memulai bimbingan.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('register.lihat') }}"
                    class="bg-blue-600 text-white px-10 py-4 rounded-lg hover:bg-blue-700 font-semibold text-lg transition duration-200 shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Akun
                </a>
                <a href="{{ route('login.lihat') }}"
                    class="border border-blue-600 text-blue-600 px-10 py-4 rounded-lg hover:bg-blue-50 font-semibold text-lg transition duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk Aplikasi
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="bg-blue-600 text-white p-2 rounded-lg">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <span class="text-xl font-bold">AsistenTA</span>
                    </div>
                    <p class="text-gray-400">
                        Platform e-Logbook & Manajemen Tugas Akhir Digital. Mendukung proses bimbingan yang transparan
                        dan terstruktur.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Akses</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('login.lihat') }}"
                                class="hover:text-white transition duration-200">Login User</a></li>
                        <li><a href="{{ route('register.lihat') }}"
                                class="hover:text-white transition duration-200">Registrasi Mahasiswa</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Fitur Utama</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Jadwal & Availability</li>
                        <li>Validasi Logbook</li>
                        <li>Review Dokumen</li>
                        <li>Integrasi SK</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            admin@kampus.ac.id
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-building mr-2"></i>
                            Fakultas Ilmu Komputer
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} AsistenTA - Sistem Informasi Bimbingan TA. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>
