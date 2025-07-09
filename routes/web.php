<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\PartnershipController;
use App\Http\Controllers\Public\BloodBankController;
use App\Http\Controllers\Donor\DonorController;
use App\Http\Controllers\Donor\AppointmentController;
use App\Http\Controllers\Donor\DonationController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/partnership', [PartnershipController::class, 'index'])->name('partnership');
Route::post('/partnership', [PartnershipController::class, 'store'])->name('partnership.store');
Route::get('/blood-banks', [BloodBankController::class, 'index'])->name('blood-banks');

/*
|--------------------------------------------------------------------------
| Routes d'Authentification
|--------------------------------------------------------------------------
*/

// Routes pour visiteurs non connectés
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Route de déconnexion (accessible aux utilisateurs connectés)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Routes Donneur
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'donor'])->prefix('donor')->name('donor.')->group(function () {
    // Dashboard et profil
    Route::get('/dashboard', [DonorController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [DonorController::class, 'profile'])->name('profile');
    Route::put('/profile', [DonorController::class, 'updateProfile'])->name('profile.update');
    Route::get('/statistics', [DonorController::class, 'statistics'])->name('statistics');

    // Rendez-vous
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::delete('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::get('/appointments/calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');

    // Dons (historique)
    Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
    Route::get('/donations/{id}', [DonationController::class, 'show'])->name('donations.show');
    Route::get('/donations/{id}/certificate', [DonationController::class, 'certificate'])->name('donations.certificate');
    Route::get('/donations/statistics', [DonationController::class, 'statistics'])->name('donations.statistics');
});

/*
|--------------------------------------------------------------------------
| Routes Admin Banque
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Gestion des rendez-vous
    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
    Route::post('/appointments/{appointment}/confirm', [AdminAppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::post('/appointments/{appointment}/reject', [AdminAppointmentController::class, 'reject'])->name('appointments.reject');
    Route::post('/appointments/{appointment}/complete', [AdminAppointmentController::class, 'complete'])->name('appointments.complete');
    Route::get('/appointments/calendar', [AdminAppointmentController::class, 'calendar'])->name('appointments.calendar');
    Route::get('/appointments/statistics', [AdminAppointmentController::class, 'statistics'])->name('appointments.statistics');

    // Gestion des dons
    Route::get('/donations', [AdminDonationController::class, 'index'])->name('donations.index');
    Route::get('/donations/{donation}', [AdminDonationController::class, 'show'])->name('donations.show');
    Route::post('/donations/{donation}/process', [AdminDonationController::class, 'process'])->name('donations.process');
    Route::post('/donations/{donation}/available', [AdminDonationController::class, 'makeAvailable'])->name('donations.available');
    Route::post('/donations/{donation}/expire', [AdminDonationController::class, 'expire'])->name('donations.expire');
    Route::post('/donations/{donation}/use', [AdminDonationController::class, 'use'])->name('donations.use');
    Route::get('/donations/statistics', [AdminDonationController::class, 'statistics'])->name('donations.statistics');
    Route::get('/donations/inventory', [AdminDonationController::class, 'inventory'])->name('donations.inventory');

    // Gestion des utilisateurs (donneurs de la banque)
    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');
});

/*
|--------------------------------------------------------------------------
| Routes Super Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('superadmin.dashboard');
    })->name('dashboard');

    Route::get('/banks', function () {
        return view('superadmin.banks');
    })->name('banks');

    Route::get('/users', function () {
        return view('superadmin.users');
    })->name('users');

    Route::get('/partnerships', function () {
        return view('superadmin.partnerships');
    })->name('partnerships');
});
