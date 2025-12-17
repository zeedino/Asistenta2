<!DOCTYPE html>
{{-- resources/views/login.blade.php --}}
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - AsistenTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Login ke AsistenTA</h1>
            <p class="text-gray-600">Masuk ke akun Anda</p>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('failed'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('failed') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded mb-4">
                {{ session('warning') }}
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">
                    Email *
                </label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-medium mb-2">
                    Password *
                </label>
                <input type="password" id="password" name="password"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                Login
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="text-gray-600">
                Belum punya akun?
                <a href="{{ route('register.lihat') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>
</body>
</html>
