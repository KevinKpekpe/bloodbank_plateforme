<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\EmailVerificationController;
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
use App\Http\Controllers\Admin\BankAdminController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\BloodBagController;
use App\Http\Controllers\SuperAdmin\BankController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
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

// Routes pour la page unifiée des banques de sang
Route::get('/blood-bank-map', [App\Http\Controllers\BloodBankMapController::class, 'index'])->name('blood-bank-map.index');
Route::get('/blood-bank-map/{bank}/details', [App\Http\Controllers\BloodBankMapController::class, 'bankDetails'])->name('blood-bank-map.details');
Route::get('/blood-bank-map/search', [App\Http\Controllers\BloodBankMapController::class, 'search'])->name('blood-bank-map.search');
Route::get('/blood-bank-map/nearby', [App\Http\Controllers\BloodBankMapController::class, 'nearby'])->name('blood-bank-map.nearby');
Route::get('/blood-bank-map/filter-by-blood-type', [App\Http\Controllers\BloodBankMapController::class, 'filterByBloodType'])->name('blood-bank-map.filter-by-blood-type');

/*
|--------------------------------------------------------------------------
| Routes des Rapports (Accessibles aux Super Admins)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'superadmin'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/general', [ReportController::class, 'general'])->name('general');
    Route::get('/banks', [ReportController::class, 'banks'])->name('banks');
    Route::get('/blood-types', [ReportController::class, 'bloodTypes'])->name('blood-types');
    Route::get('/appointments', [ReportController::class, 'appointments'])->name('appointments');
    Route::get('/donations', [ReportController::class, 'donations'])->name('donations');
    Route::get('/users', [ReportController::class, 'users'])->name('users');
    Route::post('/export', [ReportController::class, 'export'])->name('export');
});

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

    // Routes pour les notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/latest', [NotificationController::class, 'latest'])->name('notifications.latest');
});

/*
|--------------------------------------------------------------------------
| Routes de Vérification d'Email
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/code', [EmailVerificationController::class, 'showVerificationForm'])->name('verification.code');
    Route::post('/email/verify', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/verify/resend', [EmailVerificationController::class, 'resend'])->name('verification.resend');
});

/*
|--------------------------------------------------------------------------
| Routes Donneur
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'donor', 'verified.email'])->prefix('donor')->name('donor.')->group(function () {
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
    Route::get('/donations/statistics', [AdminDonationController::class, 'statistics'])->name('donations.statistics');
    Route::get('/donations/inventory', [AdminDonationController::class, 'inventory'])->name('donations.inventory');
    Route::get('/donations/{donation}', [AdminDonationController::class, 'show'])->name('donations.show');
    Route::post('/donations/{donation}/process', [AdminDonationController::class, 'process'])->name('donations.process');
    Route::post('/donations/{donation}/available', [AdminDonationController::class, 'makeAvailable'])->name('donations.available');
    Route::post('/donations/{donation}/expire', [AdminDonationController::class, 'expire'])->name('donations.expire');
    Route::post('/donations/{donation}/use', [AdminDonationController::class, 'use'])->name('donations.use');

    // Gestion des stocks de sang
    Route::resource('stocks', StockController::class);
    Route::get('/stocks/create/multiple', [StockController::class, 'createMultiple'])->name('stocks.create-multiple');
    Route::post('/stocks/store/multiple', [StockController::class, 'storeMultiple'])->name('stocks.store-multiple');

    // Gestion des poches de sang
    Route::get('/blood-bags', [BloodBagController::class, 'index'])->name('blood-bags.index');

    // Historique et rapports des poches (AVANT les routes avec paramètres)
    Route::get('/blood-bags/movements', [BloodBagController::class, 'movements'])->name('blood-bags.movements');
    Route::get('/blood-bags/reservations', [BloodBagController::class, 'reservations'])->name('blood-bags.reservations');
    Route::get('/blood-bags/expiring-soon', [BloodBagController::class, 'expiringSoon'])->name('blood-bags.expiring-soon');
    Route::get('/blood-bags/statistics', [BloodBagController::class, 'statistics'])->name('blood-bags.statistics');

    // Routes avec paramètres (APRÈS les routes spécifiques)
    Route::get('/blood-bags/{bloodBag}', [BloodBagController::class, 'show'])->name('blood-bags.show');
    Route::get('/blood-bags/{bloodBag}/reserve', [BloodBagController::class, 'reserve'])->name('blood-bags.reserve');
    Route::post('/blood-bags/{bloodBag}/reserve', [BloodBagController::class, 'storeReservation'])->name('blood-bags.store-reservation');
    Route::post('/blood-bags/{bloodBag}/transfuse', [BloodBagController::class, 'transfuse'])->name('blood-bags.transfuse');
    Route::post('/blood-bags/{bloodBag}/cancel-reservation', [BloodBagController::class, 'cancelReservation'])->name('blood-bags.cancel-reservation');
    Route::post('/blood-bags/{bloodBag}/discard', [BloodBagController::class, 'discard'])->name('blood-bags.discard');

    // Route pour annuler une réservation spécifique
    Route::post('/blood-bags/reservations/{reservation}/cancel', [BloodBagController::class, 'cancelSpecificReservation'])->name('blood-bags.reservations.cancel');

    // Route pour jeter en masse les poches expirées
    Route::post('/blood-bags/bulk-discard', [BloodBagController::class, 'bulkDiscard'])->name('blood-bags.bulk-discard');

    // Gestion des utilisateurs (donneurs de la banque)
    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');

    // Gestion des administrateurs de la banque
    Route::resource('bank-admins', BankAdminController::class)->parameters(['bank-admins' => 'bank_admin']);
    Route::post('/bank-admins/{bank_admin}/toggle-status', [BankAdminController::class, 'toggleStatus'])->name('bank-admins.toggle-status');
});

/*
|--------------------------------------------------------------------------
| Routes Super Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('superadmin.dashboard');
    })->name('dashboard');

    // Gestion des banques de sang
    Route::resource('banks', BankController::class);
    Route::get('/banks/{bank}/statistics', [BankController::class, 'statistics'])->name('banks.statistics');
    Route::post('/banks/{bank}/toggle-status', [BankController::class, 'toggleStatus'])->name('banks.toggle-status');

    // Gestion des utilisateurs
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Gestion des partenariats
    Route::get('/partnerships', function () {
        return view('superadmin.partnerships');
    })->name('partnerships');
});
