<!DOCTYPE html>
{{-- resources/views/emails/welcomeMail.blade.php --}}
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - AsistenTA</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8fafc;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Selamat Datang di AsistenTA! 🎓</h1>
    </div>

    <div class="content">
        <h2>Halo!</h2>
        <p>Terima kasih telah mendaftar di aplikasi <strong>AsistenTA</strong>. Untuk mulai menggunakan akun Anda, silakan verifikasi alamat email Anda dengan klik tombol di bawah:</p>

        <div style="text-align: center;">
            <a href="{{ url('/verify?email=' . $userEmail . '&token=' . $verificationToken) }}"
               class="button">
                Verifikasi Email Saya
            </a>
        </div>

        <p>Atau copy dan paste link berikut di browser Anda:</p>
        <p style="background: #e2e8f0; padding: 10px; border-radius: 5px; word-break: break-all;">
            {{ url('/verify?email=' . $userEmail . '&token=' . $verificationToken) }}
        </p>

        <p><strong>Penting:</strong> Link verifikasi akan kedaluwarsa dalam 24 jam.</p>

        <p>Jika Anda tidak merasa mendaftar di AsistenTA, abaikan email ini.</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} AsistenTA. All rights reserved.</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
