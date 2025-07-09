@extends('layouts.superadmin')

@section('title', 'Statistiques - ' . $bank->name . ' - BloodLink')
@section('description', 'Statistiques détaillées de la banque de sang ' . $bank->name)
@section('page-title', 'Statistiques - ' . $bank->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Statistiques - {{ $bank->name }}</h1>
            <p class="mt-2 text-gray-600">Analyse détaillée des performances de la banque</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('superadmin.banks.show', $bank) }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
            <a href="{{ route('superadmin.banks.edit', $bank) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Rendez-vous</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_appointments'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-tint text-red-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Dons</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_donations'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-orange-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">RDV en Attente</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_appointments'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Dons Disponibles</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['available_donations'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et analyses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Évolution des dons par mois -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Évolution des Dons (6 derniers mois)</h2>
            @if(count($monthlyStats) > 0)
                <div class="space-y-3">
                    @foreach($monthlyStats as $stat)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::createFromFormat('Y-m', $stat->month)->format('F Y') }}</p>
                                <p class="text-sm text-gray-500">{{ $stat->count }} dons</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">{{ number_format($stat->total_volume, 2) }} L</p>
                                <p class="text-sm text-gray-500">Volume total</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-chart-line text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500">Aucune donnée disponible</p>
                </div>
            @endif
        </div>

        <!-- Répartition par groupe sanguin -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Répartition par Groupe Sanguin</h2>
            @if(count($bloodTypeStats) > 0)
                <div class="space-y-3">
                    @foreach($bloodTypeStats as $stat)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-500 rounded-full mr-3"></div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $stat->bloodType->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $stat->count }} dons</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">{{ number_format($stat->total_volume, 2) }} L</p>
                                <p class="text-sm text-gray-500">Volume total</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-chart-pie text-gray-300 text-4xl mb-4"></i>
                    <p class="text-gray-500">Aucune donnée disponible</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Dons récents -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Dons du Jour</h2>
        @if(count($todayDonations) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Donneur
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
                                Heure
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($todayDonations as $donation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $donation->donor->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $donation->donor->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $donation->bloodType->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($donation->volume, 2) }} L
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($donation->status === 'collected')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Collecté
                                        </span>
                                    @elseif($donation->status === 'processed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Traité
                                        </span>
                                    @elseif($donation->status === 'available')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Disponible
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $donation->donation_date->format('H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-calendar-day text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500">Aucun don aujourd'hui</p>
            </div>
        @endif
    </div>

    <!-- Actions -->
    <div class="flex justify-center">
        <a href="{{ route('superadmin.banks.show', $bank) }}"
           class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour aux détails
        </a>
    </div>
</div>
@endsection
