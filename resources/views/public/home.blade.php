@extends('layouts.app')

@section('title', 'Accueil - BloodLink')
@section('description', 'Plateforme de gestion et géolocalisation des banques de sang à Kinshasa')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-red-600 to-red-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">
                    BloodLink
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-xl text-red-100">
                    Plateforme de gestion et géolocalisation des banques de sang à Kinshasa
                </p>
                <div class="mt-10 flex justify-center space-x-4">
                    @guest
                        <a href="{{ route('register') }}" class="bg-white text-red-600 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                            Devenir Donneur
                        </a>
                        <a href="{{ route('login') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-medium hover:bg-white hover:text-red-600 transition-colors">
                            Se Connecter
                        </a>
                    @else
                        @if(Auth::user()->role === 'superadmin')
                            <a href="{{ route('superadmin.dashboard') }}" class="bg-white text-red-600 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                                Dashboard Super Admin
                            </a>
                        @elseif(Auth::user()->role === 'admin_banque')
                            <a href="{{ route('admin.dashboard') }}" class="bg-white text-red-600 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                                Dashboard Admin
                            </a>
                        @else
                            <a href="{{ route('donor.dashboard') }}" class="bg-white text-red-600 px-8 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                                Mon Dashboard
                            </a>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Fonctionnalités Principales
                </h2>
                <p class="mt-4 text-lg text-gray-600">
                    Découvrez comment BloodLink facilite la gestion des dons de sang
                </p>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Feature 1 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Géolocalisation</h3>
                    <p class="text-gray-600">Trouvez facilement les banques de sang les plus proches de chez vous.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Gestion des Stocks</h3>
                    <p class="text-gray-600">Suivez en temps réel les stocks de sang disponibles dans chaque banque.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Rendez-vous</h3>
                    <p class="text-gray-600">Prenez rendez-vous facilement pour vos dons de sang.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    @if(isset($totalBanks) && isset($totalStocks))
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Statistiques
                </h2>
            </div>

            <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <div class="text-center">
                    <div class="text-4xl font-bold text-red-600">{{ $totalBanks }}</div>
                    <div class="text-lg text-gray-600">Banques de Sang</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-red-600">{{ $totalStocks }}</div>
                    <div class="text-lg text-gray-600">Unités de Sang</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-red-600">24/7</div>
                    <div class="text-lg text-gray-600">Disponibilité</div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
