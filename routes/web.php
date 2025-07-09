<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\PartnershipController;
use App\Http\Controllers\Public\BloodBankController;
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
    Route::get('/dashboard', function () {
        return view('donor.dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('donor.profile');
    })->name('profile');

    Route::get('/appointments', function () {
        return view('donor.appointments');
    })->name('appointments');

    Route::get('/donations', function () {
        return view('donor.donations');
    })->name('donations');
});

/*
|--------------------------------------------------------------------------
| Routes Admin Banque
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/appointments', function () {
        return view('admin.appointments');
    })->name('appointments');

    Route::get('/donations', function () {
        return view('admin.donations');
    })->name('donations');

    Route::get('/stocks', function () {
        return view('admin.stocks');
    })->name('stocks');

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
