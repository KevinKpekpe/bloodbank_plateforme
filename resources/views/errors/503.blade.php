@extends('layouts.app')

@section('title', 'Service indisponible - BloodLink')
@section('description', 'Le service est temporairement indisponible. Veuillez réessayer plus tard.')

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
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-purple-100 mb-6">
                    <svg class="h-12 w-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>

                <!-- Error Code -->
                <h1 class="text-6xl font-bold text-purple-600 mb-4">503</h1>

                <!-- Error Title -->
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Service indisponible</h2>

                <!-- Error Message -->
                <p class="text-gray-600 mb-8 text-lg">
                    Nous effectuons actuellement une maintenance planifiée. Le service sera bientôt de retour.
                </p>

                <!-- Maintenance Status -->
                <div class="mb-8 p-4 bg-purple-50 border border-purple-200 rounded-lg">
                    <h3 class="text-sm font-medium text-purple-800 mb-2">Statut de la maintenance</h3>
                    <div class="flex items-center justify-center space-x-2">
                        <div class="w-3 h-3 bg-yellow-400 rounded-full animate-pulse"></div>
                        <span class="text-sm text-purple-700">Maintenance en cours</span>
                    </div>
                    <p class="text-xs text-purple-600 mt-2">Temps estimé : 30-60 minutes</p>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
                    <button onclick="window.location.reload()"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Vérifier le statut
                    </button>

                    <a href="{{ route('home') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Retour à l'accueil
                    </a>
                </div>

                <!-- Maintenance Info -->
                <div class="mt-8 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Que se passe-t-il ?</h3>
                    <p class="text-sm text-gray-600">
                        Nous améliorons nos systèmes pour vous offrir un meilleur service. Cette maintenance est nécessaire pour assurer la stabilité et les performances de la plateforme.
                    </p>
                </div>

                <!-- Emergency Contact -->
                <div class="mt-8 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <h3 class="text-sm font-medium text-red-800 mb-2">Urgence médicale ?</h3>
                    <p class="text-sm text-red-700 mb-2">
                        Si vous avez besoin d'une banque de sang en urgence, contactez directement :
                    </p>
                    <div class="text-sm text-red-600">
                        <p><i class="fas fa-phone mr-2"></i>+243 123 456 789</p>
                        <p><i class="fas fa-envelope mr-2"></i>urgence@bloodlink.cd</p>
                    </div>
                </div>

                <!-- Helpful Links -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500 mb-4">Informations utiles :</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <a href="{{ route('contact') }}" class="text-red-600 hover:text-red-500 flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            Support technique
                        </a>
                        <a href="{{ route('about') }}" class="text-red-600 hover:text-red-500 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            À propos
                        </a>
                        <a href="mailto:contact@bloodlink.cd" class="text-red-600 hover:text-red-500 flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            Nous contacter
                        </a>
                        <a href="tel:+243123456789" class="text-red-600 hover:text-red-500 flex items-center">
                            <i class="fas fa-phone mr-2"></i>
                            Appel d'urgence
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
