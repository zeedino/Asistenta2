<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmailsController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\SuratKeputusanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', fn () => view('welcome'))->name('welcome');

    // ✅ REKOMENDASI: Gunakan nama 'login' agar kompatibel dengan default Laravel
    Route::get('/login', [AuthController::class, 'loginLihat'])->name('login.lihat');
    Route::post('/login/submit', [AuthController::class, 'loginSubmit'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'registerLihat'])->name('register.lihat');
    Route::post('/register', [AuthController::class, 'registerSubmit'])->name('register.submit');

    Route::get('/verify', [AuthController::class, 'verify'])->name('verify.email');
});

// Test email (Hapus saat production)
Route::get('/send-mail', [EmailsController::class, 'welcomeEmail']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/dosen/dashboard', [DashboardController::class, 'dosenDashboard'])->name('dosen.dashboard');
    Route::get('/mahasiswa/dashboard', [DashboardController::class, 'mahasiswaDashboard'])->name('mahasiswa.dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | AVAILABILITY (DOSEN)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['check.role:dosen'])->prefix('availability')->name('availability.')->group(function () {
        Route::get('/', [AvailabilityController::class, 'index'])->name('index');
        Route::get('/create', [AvailabilityController::class, 'create'])->name('create');
        Route::post('/', [AvailabilityController::class, 'store'])->name('store');
        Route::get('/{availability}', [AvailabilityController::class, 'show'])->name('show');
        Route::get('/{availability}/edit', [AvailabilityController::class, 'edit'])->name('edit');
        Route::put('/{availability}', [AvailabilityController::class, 'update'])->name('update');
        Route::delete('/{availability}', [AvailabilityController::class, 'destroy'])->name('destroy');
    });

    // Mahasiswa melihat slot dosen pembimbing
    Route::middleware(['check.role:mahasiswa', 'sk.mahasiswa'])->group(function () {
        Route::get('/available-slots', [AvailabilityController::class, 'availableSlots'])
            ->name('available.slots');
    });

    /*
    |--------------------------------------------------------------------------
    | MEETINGS
    |--------------------------------------------------------------------------
    */
    // View (semua role)
    Route::prefix('meetings')->name('meetings.')->group(function () {
        Route::get('/', [MeetingController::class, 'index'])->name('index');
        Route::get('/calendar', [MeetingController::class, 'calendar'])->name('calendar');
        Route::get('/{meeting}', [MeetingController::class, 'show'])->name('show');
    });

    // Mahasiswa (perlu SK aktif)
    Route::middleware(['check.role:mahasiswa', 'sk.mahasiswa'])->group(function () {
        Route::post('/meetings', [MeetingController::class, 'store'])->name('meetings.store');
        Route::post('/meetings/{meeting}/cancel', [MeetingController::class, 'cancel'])->name('meetings.cancel');
    });

    // Dosen (perlu relasi bimbingan)
    Route::middleware(['check.role:dosen', 'sk.dosen'])->group(function () {
        Route::post('/meetings/{meeting}/status', [MeetingController::class, 'updateStatus'])->name('meetings.updateStatus');
        Route::post('/meetings/{meeting}/complete', [MeetingController::class, 'complete'])->name('meetings.complete');
    });

    /*
    |--------------------------------------------------------------------------
    | LOGS
    |--------------------------------------------------------------------------
    */

    // 1️⃣ MAHASISWA (CREATE/STORE/EDIT) - WAJIB DI ATAS!
    Route::middleware(['check.role:mahasiswa', 'sk.mahasiswa'])->group(function () {
        Route::get('/logs/create', [LogController::class, 'create'])->name('logs.create');
        Route::post('/logs', [LogController::class, 'store'])->name('logs.store');
        Route::get('/logs/{log}/edit', [LogController::class, 'edit'])->name('logs.edit');
        Route::put('/logs/{log}', [LogController::class, 'update'])->name('logs.update');
        Route::post('/logs/{log}/submit', [LogController::class, 'submit'])->name('logs.submit');
    });

    // 2️⃣ DOSEN (VALIDATION) - Route Spesifik
    Route::middleware(['check.role:dosen', 'sk.dosen'])->group(function () {
        Route::get('/logs/validation/queue', [LogController::class, 'validationIndex'])
            ->name('logs.validation.index');
        Route::post('/logs/{log}/validate', [LogController::class, 'validateLog'])->name('logs.validate');
        Route::post('/logs/{log}/reject', [LogController::class, 'reject'])->name('logs.reject');
    });

    // 3️⃣ GLOBAL VIEW (INDEX & SHOW) - TARUH PALING BAWAH!
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('/logs/{log}', [LogController::class, 'show'])->name('logs.show');

    /*
    |--------------------------------------------------------------------------
    | DOCUMENTS (Fixed Order)
    |--------------------------------------------------------------------------
    */

    // 1️⃣ ROUTE CREATE & STORE
    Route::middleware(['check.role:mahasiswa,dosen', 'sk.any'])->group(function () {
        Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    });

    // 2️⃣ ROUTE DOWNLOAD
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');

    // 3️⃣ ROUTE REVIEW (DOSEN) - ⚠️ WAJIB DI ATAS ROUTE SHOW WILDCARD ⚠️
    // Supaya '/documents/review/queue' TIDAK dianggap sebagai ID 'review' oleh route {document}
    Route::middleware(['check.role:dosen', 'sk.dosen'])->group(function () {
        Route::get('/documents/review/queue', [DocumentController::class, 'reviewIndex'])
            ->name('documents.review.index'); // <-- INI YANG SAYA PINDAHKAN KE ATAS

        Route::post('/documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
        Route::post('/documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
    });

    // 4️⃣ ROUTE SHOW (WILDCARD) - ⚠️ TARUH DI BAWAH ⚠️
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');

    // 5️⃣ ROUTE EDIT & UPDATE (MAHASISWA)
    Route::middleware(['check.role:mahasiswa', 'sk.mahasiswa'])->group(function () {
        Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::post('/documents/{document}/submit', [DocumentController::class, 'submit'])->name('documents.submit');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');
        Route::get('/users/export/excel', [UserController::class, 'exportExcel'])->name('users.export.excel');

        Route::resource('surat-keputusan', SuratKeputusanController::class);
        Route::get('/surat-keputusan/{suratKeputusan}/download', [SuratKeputusanController::class, 'download'])
            ->name('surat-keputusan.download');
    });
});
