<?php

use App\Http\Controllers\Admin\AssignmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\MentorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Internship\DashboardController as InternshipDashboardController;
use App\Http\Controllers\Internship\LogbookController;
use App\Http\Controllers\Pembimbing\DashboardController as PembimbingDashboardController;
use App\Http\Controllers\Pembimbing\LogbookController as PembimbingLogbookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// =============================================
// Landing page (guest) & redirect (authenticated)
// =============================================
Route::get('/', function () {
    if (auth()->check()) {
        $roleName = optional(auth()->user())->role?->name;
        return match ($roleName) {
            'Admin' => redirect()->route('admin.dashboard'),
            'Internship' => redirect()->route('internship.dashboard'),
            'Pembimbing' => redirect()->route('pembimbing.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return view('landing');
})->name('home');

// =============================================
// Dashboard routes — auth saja, role check di controller
// =============================================
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'prevent.back',
])->group(function () {
    // Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index'])->name('admin.dashboard');

    // User Management (Admin only - handled in controller)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');

        // Division Management
        Route::resource('divisions', DivisionController::class)->except(['show', 'destroy']);
        Route::patch('divisions/{division}/toggle', [DivisionController::class, 'toggle'])->name('divisions.toggle');

        // Mentor Management
        Route::get('mentors', [MentorController::class, 'index'])->name('mentors.index');
        Route::get('mentors/{mentor}/edit', [MentorController::class, 'edit'])->name('mentors.edit');
        Route::put('mentors/{mentor}', [MentorController::class, 'update'])->name('mentors.update');
        Route::get('mentors/by-division', [MentorController::class, 'getByDivision'])->name('mentors.by-division');

        // Assignment Management
        Route::resource('assignments', AssignmentController::class)->except(['show', 'destroy']);
        Route::patch('assignments/{assignment}/toggle', [AssignmentController::class, 'toggle'])->name('assignments.toggle');
        Route::get('assignments/mentors-by-division', [AssignmentController::class, 'getMentorsByDivision'])->name('assignments.mentors-by-division');
    });

    // Internship
    Route::get('/internship', [InternshipDashboardController::class, 'index'])->name('internship.dashboard');

    // Logbook Internship (dashboards dipakai dari route /internship di atas)
    Route::prefix('internship')->name('internship.')->group(function () {
        Route::post('/clock-in', [LogbookController::class, 'clockIn'])->name('logbook.clock-in');
        Route::post('/clock-out', [LogbookController::class, 'clockOut'])->name('logbook.clock-out');
        Route::post('/overtime/start', [LogbookController::class, 'startOvertime'])->name('logbook.overtime.start');
        Route::post('/overtime/end', [LogbookController::class, 'endOvertime'])->name('logbook.overtime.end');
        Route::get('/logbook/{logbook}/create', [LogbookController::class, 'create'])->name('logbook.create');
        Route::post('/logbook/{logbook}/store', [LogbookController::class, 'store'])->name('logbook.store');
        Route::get('/logbook/{logbook}/edit', [LogbookController::class, 'edit'])->name('logbook.edit');
        Route::put('/logbook/{logbook}/update', [LogbookController::class, 'update'])->name('logbook.update');
        Route::get('/logbook/history', [LogbookController::class, 'history'])->name('logbook.history');
        Route::get('/logbook/recap-absen', [LogbookController::class, 'exportAbsen'])->name('logbook.recap-absen');
        Route::get('/logbook/recap-logbook', [LogbookController::class, 'exportLogbook'])->name('logbook.recap-logbook');
    });

    // Pembimbing
    Route::get('/pembimbing', [PembimbingDashboardController::class, 'index'])->name('pembimbing.dashboard');
    Route::prefix('pembimbing')->name('pembimbing.')->group(function () {
        Route::get('/peserta', [PembimbingLogbookController::class, 'peserta'])->name('peserta');
        Route::get('/logbook', [PembimbingLogbookController::class, 'index'])->name('logbook.index');
        Route::get('/logbook/history', [PembimbingLogbookController::class, 'riwayat'])->name('logbook.riwayat');
        Route::get('/logbook/{logbook}', [PembimbingLogbookController::class, 'show'])->name('logbook.show');
        Route::post('/logbook/{logbook}/approve', [PembimbingLogbookController::class, 'approve'])->name('logbook.approve');
        Route::post('/logbook/{logbook}/reject', [PembimbingLogbookController::class, 'reject'])->name('logbook.reject');
        Route::get('/riwayat', [PembimbingLogbookController::class, 'riwayat'])->name('riwayat');
    });
});
