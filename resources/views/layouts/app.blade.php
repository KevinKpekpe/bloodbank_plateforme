<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'BloodLink - Plateforme de Don de Sang')</title>
    <meta name="description" content="@yield('description', 'Plateforme de gestion et géolocalisation des banques de sang à Kinshasa')">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center">
                            <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center mr-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <span class="text-xl font-bold text-gray-900">BloodLink</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('home') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium hover:border-red-500 hover:text-red-600">
                            Accueil
                        </a>
                        <a href="{{ route('blood-banks') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium hover:border-red-500 hover:text-red-600">
                            Banques de Sang
                        </a>
                        <a href="{{ route('blood-bank-map.index') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium hover:border-red-500 hover:text-red-600">
                            <i class="fas fa-map-marker-alt mr-1"></i>Carte
                        </a>
                        <a href="{{ route('about') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium hover:border-red-500 hover:text-red-600">
                            À Propos
                        </a>
                        <a href="{{ route('contact') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium hover:border-red-500 hover:text-red-600">
                            Contact
                        </a>
                    </div>
                </div>

                <!-- Right side -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @auth
                        <!-- User menu -->
                        <div class="ml-3 relative">
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-700">
                                    Bonjour, {{ Auth::user()->name }}
                                </span>

                                <!-- Dashboard link based on role -->
                                @if(Auth::user()->role === 'superadmin')
                                    <a href="{{ route('superadmin.dashboard') }}" class="text-red-600 hover:text-red-500 text-sm font-medium">
                                        Dashboard Super Admin
                                    </a>
                                @elseif(Auth::user()->role === 'admin_banque')
                                    <a href="{{ route('admin.dashboard') }}" class="text-red-600 hover:text-red-500 text-sm font-medium">
                                        Dashboard Admin
                                    </a>
                                @else
                                    <a href="{{ route('donor.dashboard') }}" class="text-red-600 hover:text-red-500 text-sm font-medium">
                                        Mon Dashboard
                                    </a>
                                @endif

                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-500 hover:text-red-600 text-sm font-medium">
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Guest links -->
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-red-600 text-sm font-medium">
                                Connexion
                            </a>
                            <a href="{{ route('register') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Devenir Donneur
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden">
                    <button type="button" class="text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">BloodLink</h3>
                    <p class="text-gray-300 text-sm">
                        Plateforme de gestion et géolocalisation des banques de sang à Kinshasa.
                    </p>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Liens Rapides</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="{{ route('home') }}" class="hover:text-white">Accueil</a></li>
                        <li><a href="{{ route('blood-banks') }}" class="hover:text-white">Banques de Sang</a></li>
                        <li><a href="{{ route('blood-bank-map.index') }}" class="hover:text-white">Carte Interactive</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white">À Propos</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Don de Sang</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="{{ route('register') }}" class="hover:text-white">Devenir Donneur</a></li>
                        <li><a href="{{ route('partnership') }}" class="hover:text-white">Partenariat</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><i class="fas fa-phone mr-2"></i>+243 123 456 789</li>
                        <li><i class="fas fa-envelope mr-2"></i>contact@bloodlink.cd</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>Kinshasa, RDC</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-300">
                <p>&copy; {{ date('Y') }} BloodLink. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
