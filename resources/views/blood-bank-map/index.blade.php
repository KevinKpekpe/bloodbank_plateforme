@extends('layouts.app')

@section('title', 'Carte Interactive des Banques de Sang - BloodLink')
@section('description', 'Trouvez les banques de sang près de chez vous avec les stocks en temps réel')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 600px;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .search-container {
        position: absolute;
        top: 20px;
        left: 20px;
        z-index: 1000;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
        min-width: 350px;
        backdrop-filter: blur(10px);
    }

    .location-button {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background: white;
        border: none;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .location-button:hover {
        background: #f3f4f6;
        transform: scale(1.05);
    }

    .bank-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .bank-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #ef4444;
    }

    .bank-card.selected {
        border-color: #ef4444;
        background: #fef2f2;
    }

    .stock-indicator {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        margin: 2px;
    }

    .stock-critical { background: #fee2e2; color: #dc2626; }
    .stock-low { background: #fef3c7; color: #d97706; }
    .stock-normal { background: #d1fae5; color: #059669; }
    .stock-high { background: #dbeafe; color: #2563eb; }

    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .modal-overlay {
        backdrop-filter: blur(5px);
    }

    .stock-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
        margin-top: 16px;
    }

    .stock-item {
        background: white;
        border-radius: 8px;
        padding: 12px;
        text-align: center;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .stock-item:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .loading-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .filter-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 8px 16px;
        border-radius: 20px;
        border: 2px solid #e5e7eb;
        background: white;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 14px;
        font-weight: 500;
    }

    .filter-tab.active {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }

    .filter-tab:hover {
        border-color: #ef4444;
        color: #ef4444;
    }

    .filter-tab.active:hover {
        color: white;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-red-600 via-red-700 to-red-800 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-8">
                <i class="fas fa-map-marker-alt mr-6"></i>
                Carte Interactive des Banques de Sang
            </h1>
            <p class="text-xl text-red-100 max-w-4xl mx-auto leading-relaxed">
                Trouvez rapidement la banque de sang la plus proche avec les stocks en temps réel.
                Géolocalisation précise, recherche avancée et informations détaillées sur chaque groupe sanguin.
            </p>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Statistiques globales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="stats-card">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-hospital text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold mb-2">{{ $banks->count() }}</h3>
            <p class="text-red-100">Banques de Sang</p>
        </div>

        <div class="stats-card">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-tint text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold mb-2">{{ number_format($totalStocks / 1000, 1) }}</h3>
            <p class="text-red-100">Litres Disponibles</p>
        </div>

        <div class="stats-card">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold mb-2">{{ $criticalStocks }}</h3>
            <p class="text-red-100">Stocks Critiques</p>
        </div>

        <div class="stats-card">
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold mb-2">24h/24</h3>
            <p class="text-red-100">Disponible</p>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Carte -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <div class="mb-6">
                    <h2 class="text-3xl font-bold text-gray-900 mb-3">Carte Interactive</h2>
                    <p class="text-gray-600">Cliquez sur les marqueurs pour voir les détails et les stocks de chaque banque</p>
                </div>

                <div class="relative">
                    <!-- Barre de recherche -->
                    <div class="search-container">
                        <div class="flex space-x-3 mb-4">
                            <input type="text" id="searchInput"
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                   placeholder="Rechercher une banque...">
                            <button onclick="searchBanks()"
                                    class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                        <!-- Filtres par type de sang -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filtrer par type de sang:</label>
                            <select id="bloodTypeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                                <option value="">Tous les types</option>
                                @foreach($bloodTypes as $bloodType)
                                    <option value="{{ $bloodType->id }}">{{ $bloodType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="text-sm text-gray-600">Rayon de recherche:</label>
                            <select id="radiusSelect" class="px-3 py-1 border border-gray-300 rounded text-sm">
                                <option value="5">5 km</option>
                                <option value="10" selected>10 km</option>
                                <option value="20">20 km</option>
                                <option value="50">50 km</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bouton de localisation -->
                    <button onclick="getCurrentLocation()" class="location-button" title="Ma position">
                        <i class="fas fa-location-arrow text-gray-600 text-xl"></i>
                    </button>

                    <!-- Carte Leaflet -->
                    <div id="map"></div>
                </div>
            </div>
        </div>

        <!-- Liste des banques -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-xl">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Banques de Sang</h2>
                    <div class="text-sm text-gray-600" id="resultsCount">
                        {{ $banks->count() }} banque(s) trouvée(s)
                    </div>
                </div>

                <!-- Filtres rapides -->
                <div class="filter-tabs">
                    <div class="filter-tab active" onclick="filterByStatus('all')">Toutes</div>
                    <div class="filter-tab" onclick="filterByStatus('critical')">Critiques</div>
                    <div class="filter-tab" onclick="filterByStatus('available')">Disponibles</div>
                </div>

                <div class="space-y-4 max-h-96 overflow-y-auto" id="banksList">
                    @foreach($banks as $bank)
                        @php
                            $totalStock = $bank->bloodStocks->sum('quantity');
                            $totalStockL = $totalStock / 1000;
                            $criticalCount = $bank->bloodStocks->filter(function($stock) { return $stock->isCritical(); })->count();
                        @endphp
                        <div class="bank-card border border-gray-200 rounded-xl p-4 hover:shadow-lg transition-all"
                             data-bank-id="{{ $bank->id }}"
                             onclick="selectBank({{ $bank->id }}, {{ $bank->latitude }}, {{ $bank->longitude }})">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="font-bold text-gray-900 text-lg">{{ $bank->name }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $criticalCount > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $criticalCount > 0 ? 'Critique' : 'Disponible' }}
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 mb-3">{{ $bank->address }}</p>

                            <div class="text-sm text-gray-500 space-y-1 mb-3">
                                <p><i class="fas fa-phone mr-2"></i>{{ $bank->contact_phone }}</p>
                                <p><i class="fas fa-envelope mr-2"></i>{{ $bank->contact_email }}</p>
                            </div>

                            <div class="flex justify-between items-center">
                                <div class="text-sm">
                                    <span class="font-medium text-gray-900">{{ number_format($totalStock) }}</span>
                                    <span class="text-gray-500">ml ({{ number_format($totalStockL, 1) }} L)</span>
                                </div>
                                <button class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    <i class="fas fa-map-marker-alt mr-1"></i>Voir détails
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les détails de la banque -->
<div id="bankModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden modal-overlay">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-3xl font-bold text-gray-900" id="modalBankName"></h3>
                <button onclick="closeBankModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div id="modalContent" class="space-y-6">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map;
let markers = [];
let currentLocationMarker;
let selectedBankId = null;

// Initialiser la carte
document.addEventListener('DOMContentLoaded', function() {
    // Coordonnées de Kinshasa par défaut
    const defaultLat = -4.4419;
    const defaultLng = 15.2663;

    map = L.map('map').setView([defaultLat, defaultLng], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Ajouter les marqueurs des banques
    addBankMarkers();

    // Écouter les changements de filtre
    document.getElementById('bloodTypeFilter').addEventListener('change', function() {
        filterByBloodType(this.value);
    });
});

// Ajouter les marqueurs des banques
function addBankMarkers() {
    const banks = @json($banks);

    banks.forEach(bank => {
        if (bank.latitude && bank.longitude) {
            const criticalCount = bank.blood_stocks.filter(stock => stock.is_critical).length;
            const totalStock = bank.blood_stocks.reduce((sum, stock) => sum + stock.quantity, 0);

            // Créer une icône personnalisée basée sur le statut
            const iconColor = criticalCount > 0 ? '#dc2626' : '#059669';
            const icon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: ${iconColor}; width: 25px; height: 25px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">${criticalCount > 0 ? '!' : '✓'}</div>`,
                iconSize: [25, 25],
                iconAnchor: [12, 12]
            });

            const marker = L.marker([bank.latitude, bank.longitude], { icon })
                .addTo(map)
                .bindPopup(`
                    <div class="p-4 min-w-[300px]">
                        <h3 class="font-bold text-gray-900 text-lg mb-2">${bank.name}</h3>
                        <p class="text-sm text-gray-600 mb-2">${bank.address}</p>
                        <p class="text-sm text-gray-500 mb-1">📞 ${bank.contact_phone}</p>
                        <p class="text-sm text-gray-500 mb-3">✉️ ${bank.contact_email}</p>
                        <div class="mb-3">
                            <span class="text-sm font-medium">Stock total: ${totalStock.toLocaleString()} ml (${(totalStock/1000).toFixed(1)} L)</span>
                            ${criticalCount > 0 ? `<span class="ml-2 text-red-600 text-sm">⚠️ ${criticalCount} stock(s) critique(s)</span>` : ''}
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="showBankDetails(${bank.id})"
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
                                Voir détails
                            </button>
                            <button onclick="getDirections(${bank.latitude}, ${bank.longitude})"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                Itinéraire
                            </button>
                        </div>
                    </div>
                `);

            markers.push(marker);
        }
    });
}

// Sélectionner une banque
function selectBank(bankId, lat, lng) {
    // Retirer la sélection précédente
    document.querySelectorAll('.bank-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Sélectionner la nouvelle banque
    const card = document.querySelector(`[data-bank-id="${bankId}"]`);
    if (card) {
        card.classList.add('selected');
    }

    selectedBankId = bankId;

    // Centrer la carte sur la banque
    map.setView([lat, lng], 15);

    // Ouvrir le popup du marqueur
    const marker = markers.find(m => m.getLatLng().lat === lat && m.getLatLng().lng === lng);
    if (marker) {
        marker.openPopup();
    }
}

// Afficher les détails d'une banque
function showBankDetails(bankId) {
    // Masquer la carte et la liste des banques
    document.querySelector('.lg\\:col-span-2').style.display = 'none';
    document.querySelector('.lg\\:col-span-1').style.display = 'none';

    fetch(`/blood-bank-map/${bankId}/details`)
        .then(response => response.json())
        .then(bank => {
            document.getElementById('modalBankName').textContent = bank.name;

            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = `
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Informations de contact</h4>
                        <div class="space-y-3">
                            <p class="flex items-center text-gray-700">
                                <i class="fas fa-map-marker-alt w-5 text-red-500"></i>
                                <span class="ml-3">${bank.address}</span>
                            </p>
                            <p class="flex items-center text-gray-700">
                                <i class="fas fa-phone w-5 text-red-500"></i>
                                <span class="ml-3">${bank.contact_phone}</span>
                            </p>
                            <p class="flex items-center text-gray-700">
                                <i class="fas fa-envelope w-5 text-red-500"></i>
                                <span class="ml-3">${bank.contact_email}</span>
                            </p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-semibold text-gray-900">Stocks de sang</h4>
                            <div class="text-sm text-gray-600">
                                Total: <span class="font-semibold">${bank.total_stocks.toLocaleString()} ml</span>
                                (${bank.total_stocks_l.toFixed(1)} L)
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            ${bank.stocks.map(stock => `
                                <div class="stock-item ${getStockClass(stock.status)}">
                                    <div class="text-2xl font-bold text-gray-900">${stock.blood_type}</div>
                                    <div class="text-lg font-semibold ${getStockTextClass(stock.status)}">${stock.quantity.toLocaleString()}</div>
                                    <div class="text-xs text-gray-500">ml (${stock.quantity_l.toFixed(1)} L)</div>
                                    ${stock.is_critical ? '<div class="text-xs text-red-600 font-medium mt-1">CRITIQUE</div>' : ''}
                                    <div class="text-xs text-gray-400 mt-1">Seuil: ${stock.critical_level} ml</div>
                                </div>
                            `).join('')}
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button onclick="getDirections(${bank.latitude}, ${bank.longitude})"
                                class="flex-1 bg-red-600 text-white py-3 px-6 rounded-xl font-medium hover:bg-red-700 transition-colors">
                            <i class="fas fa-route mr-2"></i>Obtenir l'itinéraire
                        </button>
                        <button onclick="closeBankModal()"
                                class="flex-1 bg-gray-200 text-gray-700 py-3 px-6 rounded-xl font-medium hover:bg-gray-300 transition-colors">
                            Fermer
                        </button>
                    </div>
                </div>
            `;

            document.getElementById('bankModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des détails de la banque');
        });
}

// Fermer le modal
function closeBankModal() {
    document.getElementById('bankModal').classList.add('hidden');

    // Réafficher la carte et la liste des banques
    document.querySelector('.lg\\:col-span-2').style.display = 'block';
    document.querySelector('.lg\\:col-span-1').style.display = 'block';
}

// Obtenir les classes CSS pour les stocks
function getStockClass(status) {
    switch(status) {
        case 'critical': return 'border-red-200 bg-red-50';
        case 'low': return 'border-yellow-200 bg-yellow-50';
        case 'high': return 'border-blue-200 bg-blue-50';
        default: return 'border-green-200 bg-green-50';
    }
}

function getStockTextClass(status) {
    switch(status) {
        case 'critical': return 'text-red-600';
        case 'low': return 'text-yellow-600';
        case 'high': return 'text-blue-600';
        default: return 'text-green-600';
    }
}

// Centrer la carte sur une banque
function centerOnBank(lat, lng) {
    map.setView([lat, lng], 15);
}

// Obtenir l'itinéraire
function getDirections(lat, lng) {
    const url = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;
    window.open(url, '_blank');
}

// Obtenir la position actuelle
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Supprimer l'ancien marqueur de position
                if (currentLocationMarker) {
                    map.removeLayer(currentLocationMarker);
                }

                // Ajouter le nouveau marqueur
                currentLocationMarker = L.marker([lat, lng], {
                    icon: L.divIcon({
                        className: 'custom-div-icon',
                        html: '<div style="background-color: #3B82F6; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>',
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    })
                }).addTo(map);

                // Centrer la carte sur la position
                map.setView([lat, lng], 13);

                // Rechercher les banques proches
                searchNearbyBanks(lat, lng);
            },
            function(error) {
                alert('Impossible d\'obtenir votre position: ' + error.message);
            }
        );
    } else {
        alert('La géolocalisation n\'est pas supportée par votre navigateur.');
    }
}

// Rechercher les banques proches
function searchNearbyBanks(lat, lng) {
    const radius = document.getElementById('radiusSelect').value;

    fetch(`/blood-bank-map/nearby?latitude=${lat}&longitude=${lng}&radius=${radius}`)
        .then(response => response.json())
        .then(banks => {
            updateBanksList(banks);
        });
}

// Rechercher des banques par nom
function searchBanks() {
    const query = document.getElementById('searchInput').value;

    if (query.length < 2) {
        alert('Veuillez saisir au moins 2 caractères');
        return;
    }

    fetch(`/blood-bank-map/search?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(banks => {
            updateBanksList(banks);
        });
}

// Filtrer par type de sang
function filterByBloodType(bloodTypeId) {
    if (!bloodTypeId) {
        // Recharger toutes les banques
        window.location.reload();
        return;
    }

    fetch(`/blood-bank-map/filter-by-blood-type?blood_type_id=${bloodTypeId}`)
        .then(response => response.json())
        .then(banks => {
            updateBanksList(banks);
        });
}

// Filtrer par statut
function filterByStatus(status) {
    const tabs = document.querySelectorAll('.filter-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');

    const cards = document.querySelectorAll('.bank-card');
    cards.forEach(card => {
        const criticalBadge = card.querySelector('.bg-red-100');
        const isCritical = criticalBadge && criticalBadge.textContent.includes('Critique');

        switch(status) {
            case 'critical':
                card.style.display = isCritical ? 'block' : 'none';
                break;
            case 'available':
                card.style.display = !isCritical ? 'block' : 'none';
                break;
            default:
                card.style.display = 'block';
        }
    });
}

// Mettre à jour la liste des banques
function updateBanksList(banks) {
    const container = document.getElementById('banksList');
    const countElement = document.getElementById('resultsCount');

    countElement.textContent = `${banks.length} banque(s) trouvée(s)`;

    if (banks.length === 0) {
        container.innerHTML = '<div class="text-center text-gray-500 py-8"><i class="fas fa-search text-4xl mb-4"></i><p>Aucune banque trouvée</p></div>';
        return;
    }

    container.innerHTML = banks.map(bank => {
        const totalStock = bank.total_stocks || 0;
        const totalStockL = totalStock / 1000;
        const criticalCount = bank.critical_stocks || 0;

        return `
            <div class="bank-card border border-gray-200 rounded-xl p-4 hover:shadow-lg transition-all"
                 data-bank-id="${bank.id}"
                 onclick="selectBank(${bank.id}, ${bank.latitude}, ${bank.longitude})">
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-bold text-gray-900 text-lg">${bank.name}</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        ${criticalCount > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }">
                        ${criticalCount > 0 ? 'Critique' : 'Disponible'}
                    </span>
                </div>

                <p class="text-sm text-gray-600 mb-3">${bank.address}</p>

                <div class="text-sm text-gray-500 space-y-1 mb-3">
                    <p><i class="fas fa-phone mr-2"></i>${bank.contact_phone}</p>
                    <p><i class="fas fa-envelope mr-2"></i>${bank.contact_email}</p>
                    ${bank.distance ? `<p><i class="fas fa-map-marker-alt mr-2"></i>${bank.distance.toFixed(1)} km</p>` : ''}
                </div>

                <div class="flex justify-between items-center">
                    <div class="text-sm">
                        <span class="font-medium text-gray-900">${totalStock.toLocaleString()}</span>
                        <span class="text-gray-500">ml (${totalStockL.toFixed(1)} L)</span>
                    </div>
                    <button class="text-red-600 hover:text-red-800 text-sm font-medium">
                        <i class="fas fa-map-marker-alt mr-1"></i>Voir détails
                    </button>
                </div>
            </div>
        `;
    }).join('');
}
</script>
@endpush
@endsection
