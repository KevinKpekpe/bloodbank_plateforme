@extends('layouts.app')

@section('title', 'Erreur serveur - BloodLink')
@section('description', 'Une erreur interne s\'est produite. Veuillez réessayer plus tard.')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <a href="{{ route('home') }}" class="flex items-center">
                <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <span class="text-2xl font-bold text-gray-900">BloodLink</span>
            </a>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
        <div class="bg-white py-8 px-4 shadow-xl rounded-lg sm:px-10">
            <div class="text-center">
                <!-- Error Icon -->
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-100 mb-6">
                    <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <!-- Error Code -->
                <h1 class="text-6xl font-bold text-red-600 mb-4">500</h1>

                <!-- Error Title -->
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Erreur serveur</h2>

                <!-- Error Message -->
                <p class="text-gray-600 mb-8 text-lg">
                    Désolé, une erreur interne s'est produite. Nos équipes techniques ont été notifiées et travaillent à résoudre le problème.
                </p>

                <!-- Action Buttons -->
                <div class="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
                    <button onclick="window.location.reload()"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Réessayer
                    </button>

                    <a href="{{ route('home') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Retour à l'accueil
                    </a>
                </div>

                <!-- Status Information -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Informations techniques</h3>
                    <div class="text-xs text-gray-600 space-y-1">
                        <p>Erreur ID: {{ uniqid() }}</p>
                        <p>Timestamp: {{ now()->format('d/m/Y H:i:s') }}</p>
                        <p>URL: {{ request()->url() }}</p>
                    </div>
                </div>

                <!-- Helpful Links -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500 mb-4">En attendant, vous pouvez :</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <a href="{{ route('blood-banks') }}" class="text-red-600 hover:text-red-500 flex items-center">
                            <i class="fas fa-hospital mr-2"></i>
                            Consulter les banques
                        </a>
                        <a href="{{ route('geolocation.index') }}" class="text-red-600 hover:text-red-500 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Voir la carte
                        </a>
                        <a href="{{ route('contact') }}" class="text-red-600 hover:text-red-500 flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            Nous contacter
                        </a>
                        <a href="{{ route('about') }}" class="text-red-600 hover:text-red-500 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            À propos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
