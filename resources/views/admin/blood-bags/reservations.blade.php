@extends('layouts.admin')

@section('title', 'Réservations - BloodLink')
@section('description', 'Gestion des réservations de poches de sang')
@section('page-title', 'Réservations')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Réservations</h1>
                <p class="mt-2 text-gray-600">Gestion des réservations de poches de sang de {{ $bank->name }}</p>
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

    <!-- Statistiques des réservations -->
    @php
        $reservationStats = \App\Helpers\StockHelper::getReservationStatistics($bank);
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Réservations Actives</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ $reservationStats['active_count'] ?? 0 }}</p>
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
                    <h3 class="text-lg font-semibold text-gray-900">Complétées</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $reservationStats['completed_count'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Annulées</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $reservationStats['cancelled_count'] ?? 0 }}</p>
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
                    <h3 class="text-lg font-semibold text-gray-900">Expirées</h3>
                    <p class="text-2xl font-bold text-orange-600">{{ $reservationStats['expired_count'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Onglets -->
    <div class="mb-6">
        <nav class="flex space-x-8" aria-label="Tabs">
            <a href="#active"
               class="border-red-500 text-red-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm active-tab"
               onclick="showTab('active')">
                Réservations Actives
            </a>
            <a href="#completed"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
               onclick="showTab('completed')">
                Complétées
            </a>
            <a href="#cancelled"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
               onclick="showTab('cancelled')">
                Annulées
            </a>
            <a href="#expired"
               class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
               onclick="showTab('expired')">
                Expirées
            </a>
        </nav>
    </div>

    <!-- Filtres -->
    <div class="bg-white p-6 rounded-lg shadow-sm border mb-6">
        <form method="GET" action="{{ route('admin.blood-bags.reservations') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="Patient, hôpital, médecin..."
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
            </div>
            <div>
                <label for="urgency_level" class="block text-sm font-medium text-gray-700 mb-1">Niveau d'Urgence</label>
                <select name="urgency_level" id="urgency_level" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option value="">Tous les niveaux</option>
                    <option value="normal" {{ request('urgency_level') === 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="urgent" {{ request('urgency_level') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="critical" {{ request('urgency_level') === 'critical' ? 'selected' : '' }}>Critique</option>
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

    <!-- Contenu des onglets -->
    <div id="active-tab" class="tab-content">
        <div class="bg-white shadow-sm border rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Réservations Actives ({{ $activeReservations->total() }})</h3>
            </div>

            @if($activeReservations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Poche
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Urgence
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hôpital
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Expire le
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($activeReservations as $reservation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $reservation->patient_name }}</div>
                                        @if($reservation->patient_id)
                                            <div class="text-sm text-gray-500">ID: {{ $reservation->patient_id }}</div>
                                        @endif
                                        @if($reservation->doctor_name)
                                            <div class="text-sm text-gray-500">Dr. {{ $reservation->doctor_name }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $reservation->bloodBag->bag_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $reservation->bloodBag->bloodType->name }} - {{ $reservation->bloodBag->volume_ml }}ml</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $reservation->getUrgencyColor() === 'red' ? 'bg-red-100 text-red-800' : ($reservation->getUrgencyColor() === 'yellow' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                            {{ $reservation->getUrgencyLevelLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reservation->hospital_name ?? 'Non spécifié' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="font-medium">{{ $reservation->expiry_date->format('d/m/Y') }}</div>
                                        <div class="text-gray-500">{{ $reservation->expiry_date->format('H:i') }}</div>
                                        @if($reservation->isExpiringSoon())
                                            <div class="text-xs text-orange-600 font-medium">Expire bientôt</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.blood-bags.show', $reservation->bloodBag) }}"
                                               class="text-blue-600 hover:text-blue-900">
                                                Voir poche
                                            </a>
                                            <form method="POST" action="{{ route('admin.blood-bags.transfuse', $reservation->bloodBag) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-purple-600 hover:text-purple-900"
                                                        onclick="return confirm('Confirmer la transfusion ?')">
                                                    Transfuser
                                                </button>
                                            </form>
                                            <button type="button" class="text-red-600 hover:text-red-900"
                                                    onclick="showCancelModal({{ $reservation->id }})">
                                                Annuler
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $activeReservations->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 6h8" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune réservation active</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Aucune réservation active ne correspond aux critères de recherche.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Autres onglets (à implémenter selon les besoins) -->
    <div id="completed-tab" class="tab-content hidden">
        <div class="bg-white shadow-sm border rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Réservations Complétées</h3>
            <p class="text-gray-500">Fonctionnalité à implémenter selon les besoins.</p>
        </div>
    </div>

    <div id="cancelled-tab" class="tab-content hidden">
        <div class="bg-white shadow-sm border rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Réservations Annulées</h3>
            <p class="text-gray-500">Fonctionnalité à implémenter selon les besoins.</p>
        </div>
    </div>

    <div id="expired-tab" class="tab-content hidden">
        <div class="bg-white shadow-sm border rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Réservations Expirées</h3>
            <p class="text-gray-500">Fonctionnalité à implémenter selon les besoins.</p>
        </div>
    </div>
</div>

<!-- Modal pour annuler une réservation -->
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Annuler la réservation</h3>
            <form id="cancelForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="cancel_reason" class="block text-sm font-medium text-gray-700 mb-2">Raison de l'annulation</label>
                    <textarea name="reason" id="cancel_reason" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideCancelModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
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
function showTab(tabName) {
    // Masquer tous les contenus d'onglets
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });

    // Afficher l'onglet sélectionné
    document.getElementById(tabName + '-tab').classList.remove('hidden');

    // Mettre à jour les styles des onglets
    document.querySelectorAll('nav a').forEach(link => {
        link.classList.remove('border-red-500', 'text-red-600');
        link.classList.add('border-transparent', 'text-gray-500');
    });

    // Activer l'onglet sélectionné
    event.target.classList.remove('border-transparent', 'text-gray-500');
    event.target.classList.add('border-red-500', 'text-red-600');
}

function showCancelModal(reservationId) {
    document.getElementById('cancelForm').action = `/admin/blood-bags/reservations/${reservationId}/cancel`;
    document.getElementById('cancelModal').classList.remove('hidden');
}

function hideCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.getElementById('cancel_reason').value = '';
}
</script>
@endsection
