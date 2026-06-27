<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentPortalController;
use App\Livewire\StudentDirectory;
use App\Livewire\AcademicManagement;
use App\Livewire\GradesManagement;
use App\Livewire\InvoiceManagement;
use App\Livewire\SettingsManagement;
use App\Livewire\UserManagement;
use App\Livewire\UserProfile;

// ── Student Portal ────────────────────────────────────────────────────────
// Public QR scan entry point — no auth required, token authenticates the student
Route::get('/s/{token}', [StudentPortalController::class, 'loginViaQr'])->name('student.qr-login');

// Student-authenticated routes (uses 'student' guard)
Route::middleware('auth:student')->group(function () {
    Route::get('/student/dashboard', [StudentPortalController::class, 'dashboard'])->name('student.dashboard');
    Route::post('/student/logout', [StudentPortalController::class, 'logout'])->name('student.logout');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin-only academic routes
    Route::middleware('can:admin')->group(function () {
        Route::get('/students', StudentDirectory::class)->name('students');
        Route::get('/academic', AcademicManagement::class)->name('academic');
        Route::get('/students/report/pdf', [ReportController::class, 'printStudentReport'])->name('students.report');
        Route::get('/students/{id}/transcript', [ReportController::class, 'printTranscript'])->name('students.transcript');
        Route::get('/students/{id}/card', [ReportController::class, 'printStudentCard'])->name('students.card');
    });

    // Admin + teacher routes
    Route::middleware('can:staff')->group(function () {
        Route::get('/grades', GradesManagement::class)->name('grades');
    });

    // Admin + finance routes
    Route::middleware('can:cashier')->group(function () {
        Route::get('/invoices', InvoiceManagement::class)->name('invoices');
        Route::get('/invoices/report/pdf', [ReportController::class, 'printInvoiceReport'])->name('invoices.report');
        Route::get('/invoices/{id}/pdf', [ReportController::class, 'printInvoice'])->name('invoices.pdf');
    });

    // Admin-only routes
    Route::middleware('can:admin')->group(function () {
        Route::get('/settings', SettingsManagement::class)->name('settings');
        Route::get('/users', UserManagement::class)->name('users');
    });

    Route::get('/profile', UserProfile::class)->name('profile');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Future modules will go here
});
