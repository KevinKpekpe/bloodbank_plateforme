@extends('layouts.admin')

@section('title', 'Dashboard Super Admin - BloodLink')
@section('description', 'Tableau de bord super administrateur')
@section('page-title', 'Dashboard Super Administrateur')

@section('content')
<div class="mb-8">
    <p class="text-gray-600">Bienvenue, {{ Auth::user()->name }} !</p>
</div>

@php
    $totalBanks = \App\Models\Bank::count();
    $totalUsers = \App\Models\User::count();
    $totalDonors = \App\Models\Donor::count();
    $totalStocks = \App\Models\BloodStock::sum('quantity');
@endphp

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Banques de Sang</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $totalBanks }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Utilisateurs</h3>
                <p class="text-2xl font-bold text-green-600">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Donneurs</h3>
                <p class="text-2xl font-bold text-red-600">{{ $totalDonors }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Stocks Totaux</h3>
                <p class="text-2xl font-bold text-purple-600">{{ $totalStocks }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Banques récentes -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Banques Récentes</h2>
        @php
            $recentBanks = \App\Models\Bank::latest()->take(5)->get();
        @endphp
        @if($recentBanks->count() > 0)
            <div class="space-y-3">
                @foreach($recentBanks as $bank)
                    <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $bank->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $bank->address }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $bank->status }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Aucune banque enregistrée</p>
        @endif
    </div>

    <!-- Utilisateurs par rôle -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Utilisateurs par Rôle</h2>
        @php
            $usersByRole = \App\Models\User::selectRaw('role, count(*) as count')->groupBy('role')->get();
        @endphp
        <div class="space-y-3">
            @foreach($usersByRole as $role)
                <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg">
                    <span class="font-medium text-gray-900">
                        @switch($role->role)
                            @case('superadmin')
                                Super Administrateur
                                @break
                            @case('admin_banque')
                                Administrateur Banque
                                @break
                            @case('donor')
                                Donneur
                                @break
                            @default
                                {{ ucfirst($role->role) }}
                        @endswitch
                    </span>
                    <span class="text-lg font-bold text-blue-600">{{ $role->count }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
