@extends('layouts.app')

@section('title', 'Non autorisé - BloodLink')
@section('description', 'Vous devez être connecté pour accéder à cette page.')

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
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-indigo-100 mb-6">
                    <svg class="h-12 w-12 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>

                <!-- Error Code -->
                <h1 class="text-6xl font-bold text-indigo-600 mb-4">401</h1>

                <!-- Error Title -->
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Non autorisé</h2>

                <!-- Error Message -->
                <p class="text-gray-600 mb-8 text-lg">
                    Vous devez être connecté pour accéder à cette page. Veuillez vous connecter ou créer un compte.
                </p>

                <!-- Action Buttons -->
                <div class="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
                    <a href="{{ route('login') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Se connecter
                    </a>

                    <a href="{{ route('register') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Créer un compte
                    </a>
                </div>

                <!-- Benefits Section -->
                <div class="mt-8 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                    <h3 class="text-sm font-medium text-indigo-800 mb-3">Pourquoi créer un compte ?</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-indigo-700">
                        <div class="flex items-center">
                            <i class="fas fa-heart mr-2"></i>
                            <span>Suivre vos dons</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-bell mr-2"></i>
                            <span>Notifications</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-2"></i>
                            <span>Rendez-vous</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-history mr-2"></i>
                            <span>Historique</span>
                        </div>
                    </div>
                </div>

                <!-- Helpful Links -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500 mb-4">Vous pouvez aussi :</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <a href="{{ route('blood-banks') }}" class="text-red-600 hover:text-red-500 flex items-center">
                            <i class="fas fa-hospital mr-2"></i>
                            Voir les banques
                        </a>
                        <a href="{{ route('geolocation.index') }}" class="text-red-600 hover:text-red-500 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Carte interactive
                        </a>
                        <a href="{{ route('about') }}" class="text-red-600 hover:text-red-500 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            À propos
                        </a>
                        <a href="{{ route('contact') }}" class="text-red-600 hover:text-red-500 flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            Contact
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
