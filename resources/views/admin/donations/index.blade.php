@extends('layouts.admin')

@section('title', 'Gestion des Dons - BloodLink')
@section('description', 'Gestion des dons de sang de la banque')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Dons</h1>
            <p class="mt-2 text-gray-600">Suivi et gestion des dons de sang collectés</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.donations.statistics') }}"
               class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                Statistiques
            </a>
            <a href="{{ route('admin.donations.inventory') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Inventaire
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filtres</h2>
        <form method="GET" action="{{ route('admin.donations.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Tous les statuts</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="blood_type_id" class="block text-sm font-medium text-gray-700 mb-1">Groupe sanguin</label>
                <select id="blood_type_id" name="blood_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    <option value="">Tous</option>
                    @foreach($bloodTypes as $type)
                        <option value="{{ $type->id }}" {{ request('blood_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date début</label>
                <input type="date" id="date_from" name="date_from"
                       value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date fin</label>
                <input type="date" id="date_to" name="date_to"
                       value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div class="flex items-end space-x-4">
                <a href="{{ route('admin.donations.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Réinitialiser
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des dons -->
    @if($donations->count() > 0)
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Donneur
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Groupe
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Volume
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
                        @foreach($donations as $donation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($donation->donation_date)->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $donation->donor->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $donation->donor->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $donation->bloodType->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $donation->volume }}L
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($donation->status)
                                        @case('collected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Collecté
                                            </span>
                                            @break
                                        @case('processed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Traité
                                            </span>
                                            @break
                                        @case('available')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Disponible
                                            </span>
                                            @break
                                        @case('expired')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Expiré
                                            </span>
                                            @break
                                        @case('used')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Utilisé
                                            </span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($donation->status) }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.donations.show', $donation->id) }}"
                                           class="text-blue-600 hover:text-blue-900">
                                            Voir
                                        </a>
                                        @if($donation->status === 'collected')
                                            <form method="POST" action="{{ route('admin.donations.process', $donation->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-yellow-700 hover:text-yellow-900">
                                                    Traiter
                                                </button>
                                            </form>
                                        @endif
                                        @if($donation->status === 'processed')
                                            <form method="POST" action="{{ route('admin.donations.available', $donation->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-700 hover:text-green-900">
                                                    Rendre disponible
                                                </button>
                                            </form>
                                        @endif
                                        @if($donation->status === 'available')
                                            <form method="POST" action="{{ route('admin.donations.expire', $donation->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-700 hover:text-red-900">
                                                    Expirer
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.donations.use', $donation->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-blue-700 hover:text-blue-900">
                                                    Utiliser
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $donations->links() }}
            </div>
        </div>
    @else
        <div class="bg-white p-8 rounded-lg shadow-md text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun don trouvé</h3>
            <p class="text-gray-600">Aucun don ne correspond aux critères de recherche.</p>
        </div>
    @endif
</div>
@endsection
