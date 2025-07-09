@extends('layouts.admin')

@section('title', 'Gestion des Administrateurs - BloodLink')
@section('description', 'Gestion des administrateurs de la banque')
@section('page-title', 'Administrateurs de la Banque')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Administrateurs de la Banque</h1>
                <p class="mt-2 text-gray-600">{{ $bank->name }}</p>
            </div>
            <a href="{{ route('admin.bank-admins.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Ajouter un administrateur
            </a>
        </div>
    </div>

        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tableau des administrateurs -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Administrateur
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rôle
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($admins as $bank_admin)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full"
                                                 src="https://ui-avatars.com/api/?name={{ urlencode($bank_admin->name) }}&color=7F1D1D&background=EF4444"
                                                 alt="{{ $bank_admin->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $bank_admin->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $bank_admin->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $bank_admin->phone_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($bank_admin->status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"></circle>
                                            </svg>
                                            Actif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"></circle>
                                            </svg>
                                            Inactif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($bank_admin->id === $bank->admin_id)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-crown mr-1"></i>Principal
                                        </span>
                                    @elseif($bank_admin->created_by === $bank->admin_id)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-user-shield mr-1"></i>Admin
                                        </span>
                                    @elseif($bank_admin->created_by === null)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-user mr-1"></i>Existant
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-user mr-1"></i>Autre
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.bank-admins.show', $bank_admin) }}"
                                           class="text-blue-600 hover:text-blue-900" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.bank-admins.edit', $bank_admin) }}"
                                           class="text-green-600 hover:text-green-900" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($bank_admin->id !== $bank->admin_id && ($bank_admin->created_by === $bank->admin_id || $bank_admin->created_by === null))
                                            <form method="POST" action="{{ route('admin.bank-admins.toggle-status', $bank_admin) }}" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="text-yellow-600 hover:text-yellow-900"
                                                        title="{{ $bank_admin->status === 'active' ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas fa-{{ $bank_admin->status === 'active' ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.bank-admins.destroy', $bank_admin) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?')"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @elseif($bank_admin->id === $bank->admin_id)
                                            <span class="text-gray-400" title="Actions non disponibles pour l'admin principal">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        @else
                                            <span class="text-gray-400" title="Actions non disponibles pour cet admin">
                                                <i class="fas fa-ban"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    Aucun administrateur trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
