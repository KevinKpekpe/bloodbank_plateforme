@php
    $currentRoute = request()->route()->getName();
    $userRole = Auth::user()->role;
@endphp

<!-- Dashboard -->
<a href="{{ $userRole === 'superadmin' ? route('superadmin.dashboard') : route('admin.dashboard') }}"
   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ str_starts_with($currentRoute, $userRole === 'superadmin' ? 'superadmin.' : 'admin.') && $currentRoute === ($userRole === 'superadmin' ? 'superadmin.dashboard' : 'admin.dashboard') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
    <svg class="mr-3 h-6 w-6 {{ str_starts_with($currentRoute, $userRole === 'superadmin' ? 'superadmin.' : 'admin.') && $currentRoute === ($userRole === 'superadmin' ? 'superadmin.dashboard' : 'admin.dashboard') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
    </svg>
    Dashboard
</a>

@if($userRole === 'superadmin')
    <!-- Super Admin Menu -->

    <!-- Banques de Sang -->
    <a href="{{ route('superadmin.banks.index') }}"
       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ str_starts_with($currentRoute, 'superadmin.banks') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-6 w-6 {{ str_starts_with($currentRoute, 'superadmin.banks') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        Banques de Sang
    </a>

    <!-- Utilisateurs -->
    <a href="{{ route('superadmin.users.index') }}"
       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ str_starts_with($currentRoute, 'superadmin.users') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-6 w-6 {{ str_starts_with($currentRoute, 'superadmin.users') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
        </svg>
        Utilisateurs
    </a>

    <!-- Partenariats -->
    <a href="{{ route('superadmin.partnerships') }}"
       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ $currentRoute === 'superadmin.partnerships' ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-6 w-6 {{ $currentRoute === 'superadmin.partnerships' ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        Partenariats
    </a>

@else
    <!-- Admin Banque Menu -->

    <!-- Rendez-vous -->
    <a href="{{ route('admin.appointments.index') }}"
       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ str_starts_with($currentRoute, 'admin.appointments') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-6 w-6 {{ str_starts_with($currentRoute, 'admin.appointments') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
        </svg>
        Rendez-vous
    </a>

    <!-- Dons -->
    <a href="{{ route('admin.donations.index') }}"
       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ str_starts_with($currentRoute, 'admin.donations') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-6 w-6 {{ str_starts_with($currentRoute, 'admin.donations') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        Dons
    </a>

    <!-- Stocks -->
    <a href="{{ route('admin.stocks.index') }}"
       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ str_starts_with($currentRoute, 'admin.stocks') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-6 w-6 {{ str_starts_with($currentRoute, 'admin.stocks') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Stocks
    </a>

    <!-- Poches de Sang -->
    <div class="space-y-1">
        <a href="{{ route('admin.blood-bags.index') }}"
           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ str_starts_with($currentRoute, 'admin.blood-bags') && !str_contains($currentRoute, 'movements') && !str_contains($currentRoute, 'reservations') && !str_contains($currentRoute, 'expiring') && !str_contains($currentRoute, 'statistics') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-6 w-6 {{ str_starts_with($currentRoute, 'admin.blood-bags') && !str_contains($currentRoute, 'movements') && !str_contains($currentRoute, 'reservations') && !str_contains($currentRoute, 'expiring') && !str_contains($currentRoute, 'statistics') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            Poches de Sang
        </a>

        <!-- Sous-menu des poches -->
        <div class="ml-8 space-y-1">
            <a href="{{ route('admin.blood-bags.reservations') }}"
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ str_contains($currentRoute, 'admin.blood-bags.reservations') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                <svg class="mr-2 h-4 w-4 {{ str_contains($currentRoute, 'admin.blood-bags.reservations') ? 'text-red-400' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
                </svg>
                Réservations
            </a>

            <a href="{{ route('admin.blood-bags.movements') }}"
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ str_contains($currentRoute, 'admin.blood-bags.movements') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                <svg class="mr-2 h-4 w-4 {{ str_contains($currentRoute, 'admin.blood-bags.movements') ? 'text-red-400' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                Mouvements
            </a>

            <a href="{{ route('admin.blood-bags.expiring-soon') }}"
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ str_contains($currentRoute, 'admin.blood-bags.expiring') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                <svg class="mr-2 h-4 w-4 {{ str_contains($currentRoute, 'admin.blood-bags.expiring') ? 'text-red-400' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Expiration
            </a>

            <a href="{{ route('admin.blood-bags.statistics') }}"
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ str_contains($currentRoute, 'admin.blood-bags.statistics') ? 'bg-red-50 text-red-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                <svg class="mr-2 h-4 w-4 {{ str_contains($currentRoute, 'admin.blood-bags.statistics') ? 'text-red-400' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Statistiques
            </a>
        </div>
    </div>

    <!-- Utilisateurs -->
    <a href="{{ route('admin.users') }}"
       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ $currentRoute === 'admin.users' ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-6 w-6 {{ $currentRoute === 'admin.users' ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
        </svg>
        Utilisateurs
    </a>

    <!-- Administrateurs -->
    <a href="{{ route('admin.bank-admins.index') }}"
       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ str_starts_with($currentRoute, 'admin.bank-admins') ? 'bg-red-100 text-red-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <svg class="mr-3 h-6 w-6 {{ str_starts_with($currentRoute, 'admin.bank-admins') ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        Administrateurs
    </a>
@endif

<!-- Divider -->
<div class="border-t border-gray-200 my-4"></div>

<!-- Retour au site -->
<a href="{{ route('home') }}"
   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
    <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
    </svg>
    Retour au site
</a>
