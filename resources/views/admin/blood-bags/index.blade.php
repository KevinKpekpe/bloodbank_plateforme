@extends('layouts.admin')

@section('title', 'Gestion des Poches de Sang - BloodLink')
@section('description', 'Gestion complète des poches de sang de la banque')
@section('page-title', 'Gestion des Poches de Sang')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête avec statistiques rapides -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Poches de Sang</h1>
                <p class="mt-2 text-gray-600">Gestion complète des poches de sang de {{ $bank->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.blood-bags.statistics') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Statistiques
                </a>
            </div>
        </div>

        <!-- Statistiques rapides -->
        @php
            $statistics = \App\Helpers\StockHelper::getDashboardStatistics($bank);
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Total</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $statistics['total_bags'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Disponibles</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $statistics['available_bags'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Réservées</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $statistics['reserved_bags'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500">Expirent bientôt</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $statistics['expiring_soon_bags'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white p-6 rounded-lg shadow-sm border mb-6">
        <form method="GET" action="{{ route('admin.blood-bags.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="Numéro poche, patient, hôpital..."
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">Tous les statuts</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Disponible</option>
                    <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Réservée</option>
                    <option value="transfused" {{ request('status') === 'transfused' ? 'selected' : '' }}>Transfusée</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expirée</option>
                    <option value="discarded" {{ request('status') === 'discarded' ? 'selected' : '' }}>Jetée</option>
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

    <!-- Liste des poches -->
    <div class="bg-white shadow-sm border rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Liste des Poches ({{ $bloodBags->total() }})</h3>
        </div>

        @if($bloodBags->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Poche
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Groupe Sanguin
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Volume
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Expiration
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Réservation
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bloodBags as $bloodBag)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $bloodBag->bag_number }}
                                        </div>
                                        @if($bloodBag->isExpiringSoon())
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                Expire bientôt
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Donneur: {{ $bloodBag->donor->firstname }} {{ $bloodBag->donor->surname }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $bloodBag->bloodType->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $bloodBag->volume_ml }}ml
                                    <span class="text-gray-500">({{ $bloodBag->getVolumeInLiters() }}L)</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'available' => 'bg-green-100 text-green-800',
                                            'reserved' => 'bg-yellow-100 text-yellow-800',
                                            'transfused' => 'bg-blue-100 text-blue-800',
                                            'expired' => 'bg-red-100 text-red-800',
                                            'discarded' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $statusLabels = [
                                            'available' => 'Disponible',
                                            'reserved' => 'Réservée',
                                            'transfused' => 'Transfusée',
                                            'expired' => 'Expirée',
                                            'discarded' => 'Jetée'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$bloodBag->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$bloodBag->status] ?? $bloodBag->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $bloodBag->expiry_date->format('d/m/Y') }}
                                    <div class="text-xs text-gray-500">
                                        {{ $bloodBag->getDaysUntilExpiry() }} jours restants
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($bloodBag->isReserved() && $bloodBag->activeReservation)
                                        <div>
                                            <div class="font-medium">{{ $bloodBag->activeReservation->patient_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $bloodBag->activeReservation->hospital_name }}</div>
                                            <div class="text-xs text-gray-500">
                                                Expire: {{ $bloodBag->activeReservation->expiry_date->format('H:i') }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.blood-bags.show', $bloodBag) }}"
                                           class="text-blue-600 hover:text-blue-900">
                                            Voir
                                        </a>
                                        @if($bloodBag->isAvailable())
                                            <a href="{{ route('admin.blood-bags.reserve', $bloodBag) }}"
                                               class="text-green-600 hover:text-green-900">
                                                Réserver
                                            </a>
                                        @endif
                                        @if($bloodBag->isReserved() && $bloodBag->activeReservation)
                                            <form method="POST" action="{{ route('admin.blood-bags.transfuse', $bloodBag) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-purple-600 hover:text-purple-900"
                                                        onclick="return confirm('Confirmer la transfusion ?')">
                                                    Transfuser
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
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $bloodBags->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune poche trouvée</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Aucune poche ne correspond aux critères de recherche.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
