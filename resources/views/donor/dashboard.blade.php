@extends('layouts.app')

@section('title', 'Dashboard Donneur - BloodLink')
@section('description', 'Tableau de bord du donneur')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Donneur</h1>
        <p class="mt-2 text-gray-600">Bienvenue, {{ Auth::user()->name }} !</p>
    </div>

    @if($donor)
        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Groupe Sanguin</h3>
                        <p class="text-2xl font-bold text-red-600">{{ $donor->bloodType->name }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Dons Totaux</h3>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['total_donations'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Volume Total</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_volume'], 1) }}L</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Rendez-vous</h3>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['upcoming_appointments'] + $stats['pending_appointments'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('donor.appointments.create') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Prendre Rendez-vous</h3>
                        <p class="text-gray-600">Planifier un nouveau don de sang</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('donor.appointments.index') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Mes Rendez-vous</h3>
                        <p class="text-gray-600">Voir et gérer mes rendez-vous</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('donor.donations.index') }}" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Historique</h3>
                        <p class="text-gray-600">Voir mes dons précédents</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Informations personnelles -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations Personnelles</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nom complet</p>
                    <p class="font-medium">{{ $donor->firstname }} {{ $donor->surname }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Date de naissance</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($donor->birthdate)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Genre</p>
                    <p class="font-medium">{{ ucfirst($donor->gender) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Dernier don</p>
                    <p class="font-medium">
                        @if($stats['last_donation'])
                            {{ \Carbon\Carbon::parse($stats['last_donation']->donation_date)->format('d/m/Y') }}
                        @else
                            Aucun don encore
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Rendez-vous à venir -->
        @if($stats['upcoming_appointments'] > 0 || $stats['pending_appointments'] > 0)
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Rendez-vous à venir</h2>
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
                <p>Vous avez {{ $stats['upcoming_appointments'] }} rendez-vous confirmés et {{ $stats['pending_appointments'] }} en attente de confirmation.</p>
                <a href="{{ route('donor.appointments.index') }}" class="text-yellow-800 underline font-medium">Voir mes rendez-vous</a>
            </div>
        </div>
        @endif
    @else
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
            <p>Vos informations de donneur ne sont pas encore complètes. Veuillez contacter l'administrateur.</p>
        </div>
    @endif
</div>
@endsection
