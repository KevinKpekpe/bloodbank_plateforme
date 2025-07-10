@extends('layouts.app')

@section('title', 'Contact - BloodLink')
@section('description', 'Contactez BloodLink pour toute question concernant les dons de sang à Kinshasa')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-red-600 to-red-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Contactez-Nous</h1>
            <p class="text-xl md:text-2xl text-red-100 max-w-3xl mx-auto">
                Nous sommes là pour vous aider. N'hésitez pas à nous contacter pour toute question.
            </p>
        </div>
    </div>
</div>

<!-- Contact Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Formulaire de Contact -->
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Envoyez-nous un Message</h2>
                <p class="text-lg text-gray-600 mb-8">
                    Remplissez le formulaire ci-dessous et nous vous répondrons dans les plus brefs délais.
                </p>

                @auth
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-user-check text-green-600 mr-3"></i>
                        <div>
                            <h3 class="text-green-800 font-semibold">Connecté en tant que {{ Auth::user()->name }}</h3>
                            <p class="text-green-700 text-sm">Vos informations personnelles seront automatiquement remplies.</p>
                        </div>
                    </div>
                </div>
                @endauth

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom complet <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ Auth::check() ? Auth::user()->name : old('name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-300 {{ Auth::check() ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   {{ Auth::check() ? 'readonly' : '' }}
                                   required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ Auth::check() ? Auth::user()->email : old('email') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-300 {{ Auth::check() ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                   {{ Auth::check() ? 'readonly' : '' }}
                                   required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            Sujet <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="subject"
                               name="subject"
                               value="{{ old('subject') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-300"
                               placeholder="Ex: Question sur le don de sang, Problème technique, etc."
                               required>
                        @error('subject')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Message <span class="text-red-500">*</span>
                        </label>
                        <textarea id="message"
                                  name="message"
                                  rows="6"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition duration-300 resize-none"
                                  placeholder="Décrivez votre question ou demande..."
                                  required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Envoyer le Message
                        </button>
                    </div>
                </form>
            </div>

            <!-- Informations de Contact -->
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Informations de Contact</h2>
                <p class="text-lg text-gray-600 mb-8">
                    Vous pouvez également nous contacter directement via les moyens suivants.
                </p>

                <div class="space-y-6">
                    <!-- Adresse -->
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-xl text-red-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Adresse</h3>
                            <p class="text-gray-600">
                                123 Avenue de la Santé<br>
                                Commune de Limete<br>
                                Kinshasa, République Démocratique du Congo
                            </p>
                        </div>
                    </div>

                    <!-- Téléphone -->
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-phone text-xl text-red-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Téléphone</h3>
                            <p class="text-gray-600">
                                <a href="tel:+243123456789" class="hover:text-red-600 transition duration-300">
                                    +243 123 456 789
                                </a><br>
                                <a href="tel:+243987654321" class="hover:text-red-600 transition duration-300">
                                    +243 987 654 321
                                </a>
                            </p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-envelope text-xl text-red-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Email</h3>
                            <p class="text-gray-600">
                                <a href="mailto:contact@bloodlink.cd" class="hover:text-red-600 transition duration-300">
                                    contact@bloodlink.cd
                                </a><br>
                                <a href="mailto:support@bloodlink.cd" class="hover:text-red-600 transition duration-300">
                                    support@bloodlink.cd
                                </a>
                            </p>
                        </div>
                    </div>

                    <!-- Horaires -->
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-clock text-xl text-red-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Horaires d'Ouverture</h3>
                            <p class="text-gray-600">
                                Lundi - Vendredi: 8h00 - 18h00<br>
                                Samedi: 9h00 - 16h00<br>
                                Dimanche: Fermé
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Réseaux Sociaux -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Suivez-nous</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="w-12 h-12 bg-red-600 hover:bg-red-700 rounded-lg flex items-center justify-center text-white transition duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-red-600 hover:bg-red-700 rounded-lg flex items-center justify-center text-white transition duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-red-600 hover:bg-red-700 rounded-lg flex items-center justify-center text-white transition duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-red-600 hover:bg-red-700 rounded-lg flex items-center justify-center text-white transition duration-300">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Questions Fréquentes</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Trouvez rapidement des réponses aux questions les plus courantes.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    <i class="fas fa-question-circle text-red-600 mr-2"></i>
                    Comment devenir donneur de sang ?
                </h3>
                <p class="text-gray-600">
                    Inscrivez-vous sur notre plateforme, remplissez votre profil et prenez rendez-vous
                    dans une banque de sang partenaire. Nos équipes vous accompagneront tout au long du processus.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    <i class="fas fa-question-circle text-red-600 mr-2"></i>
                    Quels sont les critères pour donner du sang ?
                </h3>
                <p class="text-gray-600">
                    Vous devez avoir entre 18 et 65 ans, peser au moins 50kg, être en bonne santé
                    et ne pas avoir de contre-indications médicales. Un questionnaire de santé sera à remplir.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    <i class="fas fa-question-circle text-red-600 mr-2"></i>
                    Comment localiser une banque de sang ?
                </h3>
                <p class="text-gray-600">
                    Utilisez notre carte interactive pour trouver les banques de sang les plus proches
                    de votre position. Vous pouvez également filtrer par type de sang et disponibilité.
                </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">
                    <i class="fas fa-question-circle text-red-600 mr-2"></i>
                    Combien de temps dure un don de sang ?
                </h3>
                <p class="text-gray-600">
                    Le prélèvement lui-même dure environ 10-15 minutes. Comptez 1 heure au total
                    avec l'accueil, l'entretien médical et la collation après le don.
                </p>
            </div>
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('home') }}" class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour à l'accueil
            </a>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="py-16 bg-red-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Besoin d'Aide Immédiate ?</h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto">
            Pour les urgences médicales, contactez directement les services d'urgence ou
            appelez notre ligne d'urgence disponible 24h/24.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="tel:+243123456789" class="bg-white text-red-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                <i class="fas fa-phone mr-2"></i>
                Urgence: +243 123 456 789
            </a>
            <a href="{{ route('blood-banks') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-600 transition duration-300">
                <i class="fas fa-map-marker-alt mr-2"></i>
                Trouver une Banque de Sang
            </a>
        </div>
    </div>
</div>
@endsection
