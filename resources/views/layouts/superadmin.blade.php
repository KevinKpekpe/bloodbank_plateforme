<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BloodLink - Super Admin')</title>
    <meta name="description" content="@yield('description', 'Plateforme de gestion des banques de sang')">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-red-800 text-white w-64 flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold">BloodLink</h1>
                <p class="text-red-200 text-sm">Super Administration</p>
            </div>

            <nav class="mt-8">
                <div class="px-6 mb-4">
                    <h3 class="text-xs font-semibold text-red-300 uppercase tracking-wider">Navigation</h3>
                </div>

                <a href="{{ route('superadmin.dashboard') }}"
                   class="flex items-center px-6 py-3 text-red-100 hover:bg-red-700 {{ request()->routeIs('superadmin.dashboard') ? 'bg-red-700' : '' }}">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>

                <a href="{{ route('superadmin.banks.index') }}"
                   class="flex items-center px-6 py-3 text-red-100 hover:bg-red-700 {{ request()->routeIs('superadmin.banks.*') ? 'bg-red-700' : '' }}">
                    <i class="fas fa-hospital w-5"></i>
                    <span class="ml-3">Banques de Sang</span>
                </a>

                <a href="{{ route('superadmin.users.index') }}"
                   class="flex items-center px-6 py-3 text-red-100 hover:bg-red-700 {{ request()->routeIs('superadmin.users.*') ? 'bg-red-700' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span class="ml-3">Utilisateurs</span>
                </a>

                <a href="{{ route('superadmin.partnerships') }}"
                   class="flex items-center px-6 py-3 text-red-100 hover:bg-red-700 {{ request()->routeIs('superadmin.partnerships') ? 'bg-red-700' : '' }}">
                    <i class="fas fa-handshake w-5"></i>
                    <span class="ml-3">Partenariats</span>
                </a>

                <div class="px-6 mt-8 mb-4">
                    <h3 class="text-xs font-semibold text-red-300 uppercase tracking-wider">Rapports</h3>
                </div>

                <a href="{{ route('reports.index') }}" class="flex items-center px-6 py-3 text-red-100 hover:bg-red-700 {{ request()->routeIs('reports.*') ? 'bg-red-700' : '' }}">
                    <i class="fas fa-chart-line w-5"></i>
                    <span class="ml-3">Rapports</span>
                </a>

                <a href="#" class="flex items-center px-6 py-3 text-red-100 hover:bg-red-700">
                    <i class="fas fa-file-alt w-5"></i>
                    <span class="ml-3">Exports</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex justify-between items-center px-6 py-4">
                    <div class="flex items-center">
                        <h2 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bell"></i>
                        </button>

                        <!-- User Menu -->
                        <div class="relative">
                            <button class="flex items-center text-gray-700 hover:text-gray-900">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}&color=7F1D1D&background=EF4444" alt="{{ auth()->user()->name }}">
                                <span class="ml-2">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down ml-1"></i>
                            </button>

                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profil
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Paramètres
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative m-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative m-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        // Toggle user menu
        document.addEventListener('DOMContentLoaded', function() {
            const userButton = document.querySelector('.relative button');
            const userMenu = document.querySelector('.relative .absolute');

            if (userButton && userMenu) {
                userButton.addEventListener('click', function() {
                    userMenu.classList.toggle('hidden');
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userButton.contains(event.target) && !userMenu.contains(event.target)) {
                        userMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
