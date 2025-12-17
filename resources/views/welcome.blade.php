<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AsistenTA - e-Logbook Bimbingan Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Navigation -->
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

    <!-- Hero Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="mb-8">
                <h1 class="text-5xl font-bold text-gray-800 mb-6">
                    e-Logbook Bimbingan Digital
                    <span class="block text-blue-600 text-4xl mt-2">Untuk Akademik yang Lebih Terstruktur</span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Tingkatkan produktivitas bimbingan akademik dengan sistem digital yang efisien.
                    Gantikan komunikasi tidak terstruktur dengan platform terpadu untuk mahasiswa dan dosen.
                </p>
            </div>

            <div class="flex justify-center space-x-4 mb-12">
                <a href="{{ route('register.lihat') }}"
                   class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-semibold text-lg transition duration-200 shadow-lg">
                    <i class="fas fa-rocket mr-2"></i>Mulai Sekarang
                </a>
                <a href="#features"
                   class="border border-blue-600 text-blue-600 px-8 py-3 rounded-lg hover:bg-blue-50 font-semibold text-lg transition duration-200">
                    <i class="fas fa-info-circle mr-2"></i>Pelajari Fitur
                </a>
            </div>

            <!-- Hero Image/Illustration -->
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-check text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Jadwal Terstruktur</h3>
                        <p class="text-gray-600 text-sm mt-2">Atur bimbingan dengan mudah</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book text-blue-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Logbook Digital</h3>
                        <p class="text-gray-600 text-sm mt-2">Catat progress secara real-time</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-file-upload text-purple-600 text-2xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-800">Dokumen Terkelola</h3>
                        <p class="text-gray-600 text-sm mt-2">Upload dan review dengan mudah</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Fitur Unggulan</h2>
                <p class="text-xl text-gray-600">Semua yang Anda butuhkan untuk bimbingan akademik yang efektif</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition duration-300">
                    <div class="bg-blue-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-clock text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Manajemen Jadwal</h3>
                    <p class="text-gray-600">
                        Dosen dapat mengatur slot waktu bimbingan, mahasiswa dapat memilih dan mengajukan jadwal dengan mudah.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition duration-300">
                    <div class="bg-green-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-tasks text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Logbook Digital</h3>
                    <p class="text-gray-600">
                        Catat setiap sesi bimbingan, progress penelitian, dan dapatkan validasi langsung dari dosen pembimbing.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition duration-300">
                    <div class="bg-purple-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-file-pdf text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Manajemen Dokumen</h3>
                    <p class="text-gray-600">
                        Unggah proposal, draft, dan revisi. Dosen dapat memberikan feedback langsung pada dokumen.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition duration-300">
                    <div class="bg-orange-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-comments text-orange-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Feedback Real-time</h3>
                    <p class="text-gray-600">
                        Sistem komunikasi terpadu untuk memberikan masukan dan komentar pada setiap progress.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition duration-300">
                    <div class="bg-red-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Tracking Progress</h3>
                    <p class="text-gray-600">
                        Pantau perkembangan bimbingan dari waktu ke waktu dengan dashboard yang informatif.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition duration-300">
                    <div class="bg-indigo-100 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-bell text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Notifikasi</h3>
                    <p class="text-gray-600">
                        Dapatkan pemberitahuan untuk jadwal bimbingan, validasi logbook, dan feedback baru.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- For Whom Section -->
    <section class="py-16 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">Untuk Siapa Kami?</h2>
                <p class="text-xl opacity-90">Platform yang dirancang khusus untuk komunitas akademik</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- For Students -->
                <div class="bg-white bg-opacity-10 rounded-2xl p-8 backdrop-blur-sm">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-400 w-14 h-14 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user-graduate text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold">Untuk Mahasiswa</h3>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-300 mr-3"></i>
                            Ajukan jadwal bimbingan dengan mudah
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-300 mr-3"></i>
                            Catat progress penelitian secara terstruktur
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-300 mr-3"></i>
                            Upload dokumen dan dapatkan feedback
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-green-300 mr-3"></i>
                            Pantau perkembangan bimbingan
                        </li>
                    </ul>
                </div>

                <!-- For Lecturers -->
                <div class="bg-white bg-opacity-10 rounded-2xl p-8 backdrop-blur-sm">
                    <div class="flex items-center mb-6">
                        <div class="bg-blue-400 w-14 h-14 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-chalkboard-teacher text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold">Untuk Dosen</h3>
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-blue-300 mr-3"></i>
                            Kelola jadwal bimbingan secara efisien
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-blue-300 mr-3"></i>
                            Validasi logbook mahasiswa dengan mudah
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-blue-300 mr-3"></i>
                            Berikan feedback terstruktur pada dokumen
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-blue-300 mr-3"></i>
                            Pantau progress semua mahasiswa bimbingan
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-gray-800 mb-6">Siap Mengubah Cara Bimbingan Anda?</h2>
            <p class="text-xl text-gray-600 mb-8">
                Bergabung dengan ratusan mahasiswa dan dosen yang sudah merasakan kemudahan bimbingan digital.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('register.lihat') }}"
                   class="bg-blue-600 text-white px-10 py-4 rounded-lg hover:bg-blue-700 font-semibold text-lg transition duration-200 shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                </a>
                <a href="{{ route('login.lihat') }}"
                   class="border border-blue-600 text-blue-600 px-10 py-4 rounded-lg hover:bg-blue-50 font-semibold text-lg transition duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
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
                        Platform e-Logbook Bimbingan Digital untuk mendukung produktivitas akademik.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('login.lihat') }}" class="hover:text-white transition duration-200">Login</a></li>
                        <li><a href="{{ route('register.lihat') }}" class="hover:text-white transition duration-200">Daftar</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Fitur</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Manajemen Jadwal</li>
                        <li>Logbook Digital</li>
                        <li>Upload Dokumen</li>
                        <li>Tracking Progress</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            support@asistenta.ac.id
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-2"></i>
                            +62 21 1234 5678
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 AsistenTA - e-Logbook Bimbingan Digital. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
