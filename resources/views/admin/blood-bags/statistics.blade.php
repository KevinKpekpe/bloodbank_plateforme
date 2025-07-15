@extends('layouts.admin')

@section('title', 'Statistiques des Poches - BloodLink')
@section('description', 'Statistiques détaillées des poches de sang')
@section('page-title', 'Statistiques des Poches')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Statistiques des Poches</h1>
                <p class="mt-2 text-gray-600">Vue d'ensemble complète des poches de sang de {{ $bank->name }}</p>
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

    @php
        $statistics = \App\Helpers\StockHelper::getDashboardStatistics($bank);
        $detailedStats = \App\Helpers\StockHelper::getDetailedStatistics($bank);
    @endphp

    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total Poches</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $statistics['total_bags'] }}</p>
                    <p class="text-sm text-gray-500">{{ number_format($statistics['total_volume_l'], 1) }}L</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Disponibles</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $statistics['available_bags'] }}</p>
                    <p class="text-sm text-gray-500">{{ number_format($statistics['available_volume_l'], 1) }}L</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Réservées</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $statistics['reserved_bags'] }}</p>
                    <p class="text-sm text-gray-500">{{ number_format($statistics['reserved_volume_l'], 1) }}L</p>
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
                    <h3 class="text-lg font-semibold text-gray-900">Expirent bientôt</h3>
                    <p class="text-2xl font-bold text-orange-600">{{ $statistics['expiring_soon_bags'] }}</p>
                    <p class="text-sm text-gray-500">7 prochains jours</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Répartition par groupe sanguin -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition par Groupe Sanguin</h3>
            <div class="space-y-4">
                @foreach($detailedStats['by_blood_type'] as $bloodType => $data)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-gray-900">{{ $bloodType }}</span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-900">{{ $data['count'] }} poches</div>
                            <div class="text-xs text-gray-500">{{ number_format($data['volume_l'], 1) }}L</div>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Répartition par statut -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Répartition par Statut</h3>
            <div class="space-y-4">
                @php
                    $statusData = [
                        'available' => ['label' => 'Disponibles', 'color' => 'bg-green-500', 'count' => $statistics['available_bags']],
                        'reserved' => ['label' => 'Réservées', 'color' => 'bg-yellow-500', 'count' => $statistics['reserved_bags']],
                        'transfused' => ['label' => 'Transfusées', 'color' => 'bg-blue-500', 'count' => $detailedStats['transfused_count'] ?? 0],
                        'expired' => ['label' => 'Expirées', 'color' => 'bg-red-500', 'count' => $detailedStats['expired_count'] ?? 0],
                        'discarded' => ['label' => 'Jetées', 'color' => 'bg-gray-500', 'count' => $detailedStats['discarded_count'] ?? 0]
                    ];
                @endphp
                @foreach($statusData as $status => $data)
                    @if($data['count'] > 0)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-4 h-4 {{ $data['color'] }} rounded-full mr-3"></div>
                                <span class="text-sm font-medium text-gray-900">{{ $data['label'] }}</span>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-gray-900">{{ $data['count'] }} poches</div>
                                <div class="text-xs text-gray-500">
                                    {{ $statistics['total_bags'] > 0 ? round(($data['count'] / $statistics['total_bags']) * 100, 1) : 0 }}%
                                </div>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="{{ $data['color'] }} h-2 rounded-full"
                                 style="width: {{ $statistics['total_bags'] > 0 ? ($data['count'] / $statistics['total_bags']) * 100 : 0 }}%"></div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Statistiques détaillées -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Mouvements récents -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Mouvements Récents</h3>
            @php
                $recentMovements = \App\Models\BloodBagMovement::where('bank_id', $bank->id)
                    ->with(['bloodBag', 'bloodBag.bloodType'])
                    ->orderBy('movement_date', 'desc')
                    ->limit(10)
                    ->get();
            @endphp
            @if($recentMovements->count() > 0)
                <div class="space-y-3">
                    @foreach($recentMovements as $movement)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $movement->getMovementTypeLabel() }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $movement->bloodBag->bag_number }} - {{ $movement->bloodBag->bloodType->name }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-gray-500">
                                    {{ $movement->movement_date->format('d/m H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucun mouvement récent</p>
            @endif
        </div>

        <!-- Poches expirant bientôt -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Expiration Proche</h3>
            @php
                $expiringBags = \App\Models\BloodBag::where('bank_id', $bank->id)
                    ->where('status', 'available')
                    ->where('expiry_date', '<=', now()->addDays(7))
                    ->where('expiry_date', '>', now())
                    ->with(['bloodType'])
                    ->orderBy('expiry_date')
                    ->limit(10)
                    ->get();
            @endphp
            @if($expiringBags->count() > 0)
                <div class="space-y-3">
                    @foreach($expiringBags as $bag)
                        <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $bag->bag_number }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $bag->bloodType->name }} - {{ $bag->volume_ml }}ml
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-medium text-orange-600">
                                    {{ $bag->getDaysUntilExpiry() }} jours
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $bag->expiry_date->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucune poche n'expire bientôt</p>
            @endif
        </div>

        <!-- Réservations actives -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Réservations Actives</h3>
            @php
                $activeReservations = \App\Models\BloodBagReservation::where('bank_id', $bank->id)
                    ->where('status', 'active')
                    ->where('expiry_date', '>', now())
                    ->with(['bloodBag', 'bloodBag.bloodType'])
                    ->orderBy('expiry_date')
                    ->limit(10)
                    ->get();
            @endphp
            @if($activeReservations->count() > 0)
                <div class="space-y-3">
                    @foreach($activeReservations as $reservation)
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $reservation->patient_name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $reservation->bloodBag->bag_number }} - {{ $reservation->bloodBag->bloodType->name }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-medium text-yellow-600">
                                    {{ $reservation->getUrgencyLevelLabel() }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Expire: {{ $reservation->expiry_date->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Aucune réservation active</p>
            @endif
        </div>
    </div>

    <!-- Tableau des statistiques par groupe sanguin -->
    <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Statistiques Détaillées par Groupe Sanguin</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Groupe Sanguin
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Disponibles
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Réservées
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Transfusées
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Expirées
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Volume Total (L)
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($detailedStats['by_blood_type'] as $bloodType => $data)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $bloodType }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $data['count'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $data['available'] ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $data['reserved'] ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $data['transfused'] ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $data['expired'] ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($data['volume_l'], 1) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
