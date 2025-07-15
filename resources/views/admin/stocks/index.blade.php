@extends('layouts.admin')

@section('title', 'Gestion des Stocks - BloodLink')
@section('description', 'Gérez les stocks de sang de votre banque')
@section('page-title', 'Gestion des Stocks')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec actions -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Stocks de Sang</h2>
                <p class="text-gray-600">Gérez les stocks de sang par groupe sanguin</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.stocks.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Ajouter un stock
                </a>
                <a href="{{ route('admin.stocks.create-multiple') }}"
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Ajouter plusieurs stocks
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Tableau des stocks -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">État des Stocks</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Groupe Sanguin
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Quantité (ml)
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Seuil Critique
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dernière Mise à Jour
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($stockSummary as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item['blood_type']->name }}
                                    </div>
                                    @if(!$item['has_stock'])
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Aucun stock
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($item['has_stock'])
                                        {{ number_format($item['volume_ml']) }} ml
                                        <span class="text-gray-500">({{ number_format($item['volume_l'], 1) }} L)</span>
                                        <div class="text-xs text-gray-500">
                                            {{ $item['total_bags'] }} poches total
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($item['has_stock'])
                                        {{ number_format($item['critical_level_ml']) }} ml
                                        <div class="text-xs text-gray-500">
                                            ({{ number_format($item['critical_level_bags'], 1) }} poches)
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item['has_stock'])
                                    @if($item['available_bags'] == 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Rupture
                                        </span>
                                    @elseif($item['is_critical'])
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Critique
                                        </span>
                                    @elseif($item['is_low'])
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Faible
                                        </span>
                                    @elseif($item['is_high'])
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Élevé
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Normal
                                        </span>
                                    @endif
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $item['available_bags'] }} poches disponibles
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Rupture
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($item['has_stock'] && $item['stock']->last_updated)
                                    {{ $item['stock']->last_updated->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($item['has_stock'])
                                    <a href="{{ route('admin.stocks.edit', $item['stock']->id) }}"
                                       class="text-blue-600 hover:text-blue-900 mr-3">
                                        Modifier
                                    </a>
                                    @if($item['available_bags'] == 0)
                                        <form action="{{ route('admin.stocks.destroy', $item['stock']->id) }}"
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce stock ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('admin.stocks.create') }}?blood_type_id={{ $item['blood_type']->id }}"
                                       class="text-green-600 hover:text-green-900">
                                        Créer
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Résumé des alertes -->
    @php
        $criticalStocks = collect($stockSummary)->filter(function($item) {
            return $item['has_stock'] && $item['is_critical'] && $item['available_bags'] > 0;
        });
        $lowStocks = collect($stockSummary)->filter(function($item) {
            return $item['has_stock'] && $item['is_low'] && !$item['is_critical'] && $item['available_bags'] > 0;
        });
        $noStocks = collect($stockSummary)->filter(function($item) {
            return !$item['has_stock'] || ($item['has_stock'] && $item['available_bags'] == 0);
        });
        $highStocks = collect($stockSummary)->filter(function($item) {
            return $item['has_stock'] && $item['is_high'];
        });
    @endphp

    @if($criticalStocks->count() > 0 || $lowStocks->count() > 0 || $noStocks->count() > 0 || $highStocks->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Alertes</h3>
            <div class="space-y-3">
                @if($criticalStocks->count() > 0)
                    <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                        <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-red-800">
                                {{ $criticalStocks->count() }} stock(s) en niveau critique
                            </p>
                            <p class="text-sm text-red-600">
                                {{ $criticalStocks->pluck('blood_type.name')->implode(', ') }}
                            </p>
                        </div>
                    </div>
                @endif

                @if($lowStocks->count() > 0)
                    <div class="flex items-center p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">
                                {{ $lowStocks->count() }} stock(s) en niveau faible
                            </p>
                            <p class="text-sm text-yellow-600">
                                {{ $lowStocks->pluck('blood_type.name')->implode(', ') }}
                            </p>
                        </div>
                    </div>
                @endif

                @if($noStocks->count() > 0)
                    <div class="flex items-center p-3 bg-orange-50 border border-orange-200 rounded-lg">
                        <svg class="w-5 h-5 text-orange-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-orange-800">
                                {{ $noStocks->count() }} type(s) de sang sans stock
                            </p>
                            <p class="text-sm text-orange-600">
                                {{ $noStocks->pluck('blood_type.name')->implode(', ') }}
                            </p>
                        </div>
                    </div>
                @endif

                @if($highStocks->count() > 0)
                    <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <svg class="w-5 h-5 text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">
                                {{ $highStocks->count() }} stock(s) en niveau élevé
                            </p>
                            <p class="text-sm text-blue-600">
                                {{ $highStocks->pluck('blood_type.name')->implode(', ') }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
