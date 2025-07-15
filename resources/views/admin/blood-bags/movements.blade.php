@extends('layouts.admin')

@section('title', 'Historique des Mouvements - BloodLink')
@section('description', 'Historique complet des mouvements des poches de sang')
@section('page-title', 'Historique des Mouvements')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Historique des Mouvements</h1>
                <p class="mt-2 text-gray-600">Suivi complet des mouvements des poches de sang de {{ $bank->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.blood-bags.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Retour aux poches
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white p-6 rounded-lg shadow-sm border mb-6">
        <form method="GET" action="{{ route('admin.blood-bags.movements') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="Numéro poche, patient, raison..."
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
            </div>
            <div>
                <label for="movement_type" class="block text-sm font-medium text-gray-700 mb-1">Type de Mouvement</label>
                <select name="movement_type" id="movement_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">Tous les types</option>
                    <option value="inbound" {{ request('movement_type') === 'inbound' ? 'selected' : '' }}>Entrée</option>
                    <option value="outbound" {{ request('movement_type') === 'outbound' ? 'selected' : '' }}>Sortie</option>
                    <option value="reservation" {{ request('movement_type') === 'reservation' ? 'selected' : '' }}>Réservation</option>
                    <option value="cancellation" {{ request('movement_type') === 'cancellation' ? 'selected' : '' }}>Annulation</option>
                    <option value="transfusion" {{ request('movement_type') === 'transfusion' ? 'selected' : '' }}>Transfusion</option>
                    <option value="expiration" {{ request('movement_type') === 'expiration' ? 'selected' : '' }}>Expiration</option>
                    <option value="discard" {{ request('movement_type') === 'discard' ? 'selected' : '' }}>Destruction</option>
                </select>
            </div>
            <div>
                <label for="blood_type_id" class="block text-sm font-medium text-gray-700 mb-1">Groupe sanguin</label>
                <select name="blood_type_id" id="blood_type_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">Tous les groupes</option>
                    @foreach($bloodTypes as $bloodType)
                        <option value="{{ $bloodType->id }}" {{ request('blood_type_id') == $bloodType->id ? 'selected' : '' }}>
                            {{ $bloodType->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des mouvements -->
    <div class="bg-white shadow-sm border rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Historique des Mouvements ({{ $movements->total() }})</h3>
        </div>

        @if($movements->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date/Heure
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Poche
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Groupe Sanguin
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Détails
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Raison
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($movements as $movement)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="font-medium">{{ $movement->movement_date->format('d/m/Y') }}</div>
                                    <div class="text-gray-500">{{ $movement->movement_date->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $typeColors = [
                                            'inbound' => 'bg-green-100 text-green-800',
                                            'outbound' => 'bg-red-100 text-red-800',
                                            'reservation' => 'bg-yellow-100 text-yellow-800',
                                            'cancellation' => 'bg-gray-100 text-gray-800',
                                            'transfusion' => 'bg-blue-100 text-blue-800',
                                            'expiration' => 'bg-orange-100 text-orange-800',
                                            'discard' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeColors[$movement->movement_type] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $movement->getMovementTypeLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $movement->bloodBag->bag_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $movement->bloodBag->volume_ml }}ml</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $movement->bloodBag->bloodType->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($movement->recipient_name)
                                        <div class="font-medium">{{ $movement->recipient_name }}</div>
                                        @if($movement->hospital_name)
                                            <div class="text-gray-500">{{ $movement->hospital_name }}</div>
                                        @endif
                                        @if($movement->doctor_name)
                                            <div class="text-gray-500">Dr. {{ $movement->doctor_name }}</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($movement->reason)
                                        <div class="max-w-xs truncate" title="{{ $movement->reason }}">
                                            {{ $movement->reason }}
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $movements->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun mouvement trouvé</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Aucun mouvement ne correspond aux critères de recherche.
                </p>
            </div>
        @endif
    </div>

    <!-- Statistiques des mouvements -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $movementStats = \App\Helpers\StockHelper::getMovementStatistics($bank);
        @endphp

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Entrées</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $movementStats['inbound_count'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Sorties</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $movementStats['outbound_count'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Transfusions</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $movementStats['transfusion_count'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Expirations</h3>
                    <p class="text-2xl font-bold text-orange-600">{{ $movementStats['expiration_count'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
