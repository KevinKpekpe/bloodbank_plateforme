@extends('layouts.admin')

@section('title', 'Poches Expirant Bientôt - BloodLink')
@section('description', 'Gestion des poches de sang expirant bientôt')
@section('page-title', 'Poches Expirant Bientôt')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Poches Expirant Bientôt</h1>
                <p class="mt-2 text-gray-600">Gestion des poches de sang expirant dans les 7 prochains jours</p>
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

    <!-- Alertes -->
    @php
        $expiringStats = \App\Helpers\StockHelper::getExpiringSoonStatistics($bank);
    @endphp

    @if($expiringStats['total_expiring'] > 0)
        <div class="mb-8">
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-orange-800">Attention : Poches expirant bientôt</h3>
                        <div class="mt-2 text-orange-700">
                            <p>{{ $expiringStats['total_expiring'] }} poche(s) expire(nt) dans les 7 prochains jours.</p>
                            <p class="mt-1">Volume total concerné : {{ number_format($expiringStats['total_volume_l'], 1) }}L</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistiques par période -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Aujourd'hui</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $expiringStats['expiring_today'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500">{{ number_format($expiringStats['volume_today_l'] ?? 0, 1) }}L</p>
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
                    <h3 class="text-lg font-semibold text-gray-900">Demain</h3>
                    <p class="text-2xl font-bold text-orange-600">{{ $expiringStats['expiring_tomorrow'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500">{{ number_format($expiringStats['volume_tomorrow_l'] ?? 0, 1) }}L</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Cette semaine</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $expiringStats['expiring_this_week'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500">{{ number_format($expiringStats['volume_this_week_l'] ?? 0, 1) }}L</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Total</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $expiringStats['total_expiring'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500">{{ number_format($expiringStats['total_volume_l'] ?? 0, 1) }}L</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white p-6 rounded-lg shadow-sm border mb-6">
        <form method="GET" action="{{ route('admin.blood-bags.expiring-soon') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="Numéro poche, donneur..."
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
            </div>
            <div>
                <label for="expiry_period" class="block text-sm font-medium text-gray-700 mb-1">Période d'Expiration</label>
                <select name="expiry_period" id="expiry_period" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">Toutes les périodes</option>
                    <option value="today" {{ request('expiry_period') === 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="tomorrow" {{ request('expiry_period') === 'tomorrow' ? 'selected' : '' }}>Demain</option>
                    <option value="week" {{ request('expiry_period') === 'week' ? 'selected' : '' }}>Cette semaine</option>
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

    <!-- Liste des poches expirant bientôt -->
    <div class="bg-white shadow-sm border rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Poches Expirant Bientôt ({{ $expiringBags->total() }})</h3>
        </div>

        @if($expiringBags->count() > 0)
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
                                Date d'Expiration
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jours Restants
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
                        @foreach($expiringBags as $bloodBag)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $bloodBag->bag_number }}</div>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $bloodBag->expiry_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $daysLeft = $bloodBag->getDaysUntilExpiry();
                                        $urgencyColor = $daysLeft <= 1 ? 'text-red-600' : ($daysLeft <= 3 ? 'text-orange-600' : 'text-yellow-600');
                                        $urgencyBg = $daysLeft <= 1 ? 'bg-red-100' : ($daysLeft <= 3 ? 'bg-orange-100' : 'bg-yellow-100');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $urgencyBg }} {{ $urgencyColor }}">
                                        {{ $daysLeft }} jour(s)
                                    </span>
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
                                        @if($bloodBag->isAvailable() || $bloodBag->isReserved())
                                            <button type="button"
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="showDiscardModal({{ $bloodBag->id }})">
                                                Jeter
                                            </button>
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
                {{ $expiringBags->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune poche n'expire bientôt</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Aucune poche n'expire dans les 7 prochains jours.
                </p>
            </div>
        @endif
    </div>

    <!-- Actions en lot -->
    @if($expiringBags->count() > 0)
        <div class="mt-8 bg-white p-6 rounded-lg shadow-sm border">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions en Lot</h3>
            <div class="flex space-x-4">
                <button type="button"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                        onclick="showBulkDiscardModal()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Jeter toutes les poches expirées
                </button>
                <button type="button"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        onclick="exportExpiringBags()">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exporter la liste
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Modal pour jeter une poche -->
<div id="discardModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Jeter la poche</h3>
            <form id="discardForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="discard_reason" class="block text-sm font-medium text-gray-700 mb-2">Raison de la destruction</label>
                    <textarea name="reason" id="discard_reason" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideDiscardModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour jeter en lot -->
<div id="bulkDiscardModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Jeter les poches expirées</h3>
            <p class="text-sm text-gray-600 mb-4">Cette action jettera toutes les poches qui ont expiré aujourd'hui.</p>
            <form method="POST" action="{{ route('admin.blood-bags.bulk-discard') }}">
                @csrf
                <div class="mb-4">
                    <label for="bulk_discard_reason" class="block text-sm font-medium text-gray-700 mb-2">Raison de la destruction</label>
                    <textarea name="reason" id="bulk_discard_reason" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideBulkDiscardModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showDiscardModal(bagId) {
    document.getElementById('discardForm').action = `/admin/blood-bags/${bagId}/discard`;
    document.getElementById('discardModal').classList.remove('hidden');
}

function hideDiscardModal() {
    document.getElementById('discardModal').classList.add('hidden');
    document.getElementById('discard_reason').value = '';
}

function showBulkDiscardModal() {
    document.getElementById('bulkDiscardModal').classList.remove('hidden');
}

function hideBulkDiscardModal() {
    document.getElementById('bulkDiscardModal').classList.add('hidden');
    document.getElementById('bulk_discard_reason').value = '';
}

function exportExpiringBags() {
    // Implémenter l'export selon les besoins
    alert('Fonctionnalité d\'export à implémenter');
}
</script>
@endsection
