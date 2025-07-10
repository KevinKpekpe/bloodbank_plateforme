@extends('layouts.app')

@section('title', 'À Propos - BloodLink')
@section('description', 'Découvrez BloodLink, la plateforme de gestion et géolocalisation des banques de sang à Kinshasa')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-red-600 to-red-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">À Propos de BloodLink</h1>
            <p class="text-xl md:text-2xl text-red-100 max-w-3xl mx-auto">
                Connecter les donneurs aux banques de sang pour sauver des vies à Kinshasa
            </p>
        </div>
    </div>
</div>

<!-- Mission Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Notre Mission</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                BloodLink vise à révolutionner la gestion des dons de sang à Kinshasa en créant une plateforme
                innovante qui connecte efficacement les donneurs aux banques de sang.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heart text-2xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Sauver des Vies</h3>
                <p class="text-gray-600">
                    Faciliter l'accès aux dons de sang pour sauver des vies en situation d'urgence.
                </p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-2xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Géolocalisation</h3>
                <p class="text-gray-600">
                    Localiser rapidement les banques de sang les plus proches grâce à notre carte interactive.
                </p>
            </div>

            <div class="text-center p-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-2xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Communauté</h3>
                <p class="text-gray-600">
                    Créer une communauté de donneurs engagés pour répondre aux besoins en sang.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Vision Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Notre Vision</h2>
                <p class="text-lg text-gray-600 mb-6">
                    Nous imaginons un Kinshasa où chaque personne en besoin de sang peut rapidement
                    trouver une banque de sang avec les stocks nécessaires, et où chaque donneur
                    peut facilement contribuer à sauver des vies.
                </p>
                <p class="text-lg text-gray-600 mb-6">
                    BloodLink utilise la technologie moderne pour créer un écosystème connecté
                    entre les donneurs, les banques de sang et les patients, garantissant une
                    gestion efficace et transparente des dons de sang.
                </p>
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span class="text-gray-700">Gestion en temps réel</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span class="text-gray-700">Transparence totale</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span class="text-gray-700">Accès facile</span>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="bg-red-600 rounded-lg p-8 text-white">
                    <h3 class="text-2xl font-bold mb-4">Impact Chiffré</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span>Banques de sang connectées</span>
                            <span class="text-2xl font-bold">50+</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Donneurs enregistrés</span>
                            <span class="text-2xl font-bold">1000+</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Vies sauvées</span>
                            <span class="text-2xl font-bold">500+</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Dons collectés</span>
                            <span class="text-2xl font-bold">2000+</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Équipe Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Notre Équipe</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Une équipe passionnée de professionnels de la santé et de développeurs
                dédiés à l'amélioration du système de don de sang à Kinshasa.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-user-md text-3xl text-gray-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Dr. Marie K.</h3>
                <p class="text-red-600 mb-2">Directrice Médicale</p>
                <p class="text-sm text-gray-600">
                    Spécialiste en transfusion sanguine avec 15 ans d'expérience.
                </p>
            </div>

            <div class="text-center">
                <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-laptop-code text-3xl text-gray-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Jean P.</h3>
                <p class="text-red-600 mb-2">Lead Développeur</p>
                <p class="text-sm text-gray-600">
                    Expert en développement web et applications mobiles.
                </p>
            </div>

            <div class="text-center">
                <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-chart-line text-3xl text-gray-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Sarah M.</h3>
                <p class="text-red-600 mb-2">Analyste de Données</p>
                <p class="text-sm text-gray-600">
                    Spécialiste en analyse de données médicales et statistiques.
                </p>
            </div>

            <div class="text-center">
                <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-hands-helping text-3xl text-gray-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Pierre L.</h3>
                <p class="text-red-600 mb-2">Coordinateur Partenariats</p>
                <p class="text-sm text-gray-600">
                    Gestionnaire des relations avec les banques de sang partenaires.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Partenaires Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Nos Partenaires</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Nous collaborons avec les principales institutions médicales et banques de sang de Kinshasa.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="w-16 h-16 bg-red-100 rounded-lg mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-hospital text-2xl text-red-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Centre Hospitalier</h3>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="w-16 h-16 bg-red-100 rounded-lg mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-clinic-medical text-2xl text-red-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Cliniques Privées</h3>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="w-16 h-16 bg-red-100 rounded-lg mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-university text-2xl text-red-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Universités</h3>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="w-16 h-16 bg-red-100 rounded-lg mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-government text-2xl text-red-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Ministère de la Santé</h3>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="py-16 bg-red-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Rejoignez BloodLink</h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto">
            Ensemble, nous pouvons sauver plus de vies. Devenez donneur ou partenaire aujourd'hui.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-red-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                Devenir Donneur
            </a>
            <a href="{{ route('partnership') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-600 transition duration-300">
                Devenir Partenaire
            </a>
        </div>
    </div>
</div>
@endsection
