@extends('layouts.superadmin')

@section('title', 'Gestion des Banques de Sang - BloodLink')
@section('description', 'Gestion des banques de sang de la plateforme')
@section('page-title', 'Banques de Sang')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête avec actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Banques de Sang</h1>
            <p class="mt-2 text-gray-600">Gestion des banques de sang de la plateforme</p>
        </div>
        <a href="{{ route('superadmin.banks.create') }}"
           class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Ajouter une Banque
        </a>
    </div>

    <!-- Filtres -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="Nom ou adresse...">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 mr-2">
                    <i class="fas fa-search mr-1"></i>Filtrer
                </button>
                <a href="{{ route('superadmin.banks.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                    <i class="fas fa-times mr-1"></i>Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des banques -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Banque
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Utilisateurs
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Activité
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($banks as $bank)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $bank->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $bank->address }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $bank->contact_phone }}</div>
                                <div class="text-sm text-gray-500">{{ $bank->contact_email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $bank->users_count }} utilisateurs</div>
                                <div class="text-sm text-gray-500">{{ $bank->admins_count }} admins</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $bank->appointments_count }} RDV</div>
                                <div class="text-sm text-gray-500">{{ $bank->donations_count }} dons</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($bank->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('superadmin.banks.show', $bank) }}"
                                       class="text-blue-600 hover:text-blue-900" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('superadmin.banks.edit', $bank) }}"
                                       class="text-green-600 hover:text-green-900" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('superadmin.banks.statistics', $bank) }}"
                                       class="text-purple-600 hover:text-purple-900" title="Statistiques">
                                        <i class="fas fa-chart-bar"></i>
                                    </a>
                                    <form method="POST" action="{{ route('superadmin.banks.toggle-status', $bank) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="text-yellow-600 hover:text-yellow-900"
                                                title="{{ $bank->status === 'active' ? 'Désactiver' : 'Activer' }}">
                                            <i class="fas fa-{{ $bank->status === 'active' ? 'ban' : 'check' }}"></i>
                                        </button>
                                    </form>
                                    @if($bank->users_count === 0 && $bank->appointments_count === 0 && $bank->donations_count === 0)
                                        <form method="POST" action="{{ route('superadmin.banks.destroy', $bank) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette banque ?')"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Aucune banque de sang trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($banks->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $banks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
