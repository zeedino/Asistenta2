<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Akun</h1>
            <p class="text-gray-600">Buat akun untuk mulai menggunakan AsistenTA</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-medium mb-2">
                    Username *
                </label>
                <input type="text" id="username" name="username"
                       value="{{ old('username') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">
                    Email *
                </label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>

            <!-- TAMBAH ROLE SELECTION -->
            <div class="mb-4">
                <label for="role" class="block text-gray-700 text-sm font-medium mb-2">
                    Daftar Sebagai *
                </label>
                <select id="role" name="role"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                    <option value="">Pilih Role</option>
                    <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Pilih peran Anda dalam sistem</p>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-medium mb-2">
                    Password *
                </label>
                <input type="password" id="password" name="password"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">
                    Konfirmasi Password *
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                Daftar
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login.lihat') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Login di sini
                </a>
            </p>
        </div>
    </div>
</body>
</html>
