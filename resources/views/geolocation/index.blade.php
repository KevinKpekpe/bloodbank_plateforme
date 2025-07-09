@extends('layouts.app')

@section('title', 'Carte Interactive des Banques de Sang - BloodLink')
@section('description', 'Trouvez les banques de sang près de chez vous avec notre carte interactive')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 600px;
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .search-container {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 1000;
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        min-width: 320px;
    }
    .location-button {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 1000;
        background: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.2s;
    }
    .location-button:hover {
        background: #f3f4f6;
        transform: scale(1.05);
    }
    .bank-card {
        transition: all 0.2s;
        cursor: pointer;
    }
    .bank-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
    }
    .feature-badge {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #dc2626;
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: bold;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-red-600 to-red-700 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">
                <i class="fas fa-map-marker-alt mr-4"></i>
                Carte Interactive des Banques de Sang
            </h1>
            <p class="text-xl text-red-100 max-w-3xl mx-auto">
                Trouvez rapidement la banque de sang la plus proche de votre position.
                Géolocalisation précise, recherche par nom et filtres avancés.
            </p>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-hospital text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">{{ $banks->count() }}</h3>
            <p class="text-sm text-gray-600">Banques de Sang</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-map-marker-alt text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">{{ $banks->whereNotNull('latitude')->count() }}</h3>
            <p class="text-sm text-gray-600">Avec Géolocalisation</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">{{ $banks->where('status', 'active')->count() }}</h3>
            <p class="text-sm text-gray-600">Actives</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-clock text-purple-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">24h/24</h3>
            <p class="text-sm text-gray-600">Disponible</p>
        </div>
    </div>

    <!-- Carte -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8 relative">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Carte Interactive</h2>
            <p class="text-gray-600">Cliquez sur les marqueurs pour voir les détails des banques de sang</p>
        </div>

        <div class="relative">
            <!-- Barre de recherche -->
            <div class="search-container">
                <div class="flex space-x-2 mb-3">
                    <input type="text" id="searchInput"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                           placeholder="Rechercher une banque...">
                    <button onclick="searchBanks()"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="flex items-center justify-between">
                    <label class="text-sm text-gray-600">Rayon de recherche:</label>
                    <select id="radiusSelect" class="px-2 py-1 border border-gray-300 rounded text-sm">
                        <option value="5">5 km</option>
                        <option value="10" selected>10 km</option>
                        <option value="20">20 km</option>
                        <option value="50">50 km</option>
                    </select>
                </div>
            </div>

            <!-- Bouton de localisation -->
            <button onclick="getCurrentLocation()" class="location-button" title="Ma position">
                <i class="fas fa-location-arrow text-gray-600 text-lg"></i>
            </button>

            <!-- Carte Leaflet -->
            <div id="map"></div>
        </div>
    </div>

    <!-- Liste des banques -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Banques de Sang Disponibles</h2>
            <div class="text-sm text-gray-600" id="resultsCount">
                {{ $banks->count() }} banque(s) trouvée(s)
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="banksList">
            @foreach($banks as $bank)
                <div class="bank-card border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all relative">
                    <div class="feature-badge">Actif</div>
                    <h3 class="font-semibold text-gray-900 mb-2">{{ $bank->name }}</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ $bank->address }}</p>
                    <div class="text-sm text-gray-500 space-y-1 mb-3">
                        <p><i class="fas fa-phone mr-2"></i>{{ $bank->contact_phone }}</p>
                        <p><i class="fas fa-envelope mr-2"></i>{{ $bank->contact_email }}</p>
                        @if($bank->latitude && $bank->longitude)
                            <p><i class="fas fa-map-marker-alt mr-2"></i>{{ $bank->latitude }}, {{ $bank->longitude }}</p>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $bank->status === 'active' ? 'Actif' : 'Inactif' }}
                        </span>
                        <button onclick="centerOnBank({{ $bank->latitude }}, {{ $bank->longitude }})"
                                class="text-red-600 hover:text-red-800 text-sm font-medium">
                            <i class="fas fa-map-marker-alt mr-1"></i>Voir sur la carte
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Instructions -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Comment utiliser la carte
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-blue-800">
            <div>
                <i class="fas fa-search mr-2"></i>
                <strong>Recherche :</strong> Tapez le nom d'une banque dans la barre de recherche
            </div>
            <div>
                <i class="fas fa-location-arrow mr-2"></i>
                <strong>Géolocalisation :</strong> Cliquez sur le bouton pour vous localiser
            </div>
            <div>
                <i class="fas fa-map-marker-alt mr-2"></i>
                <strong>Marqueurs :</strong> Cliquez sur les marqueurs pour voir les détails
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
});

// Ajouter les marqueurs des banques
function addBankMarkers() {
    const banks = @json($banks);

    banks.forEach(bank => {
        if (bank.latitude && bank.longitude) {
            const marker = L.marker([bank.latitude, bank.longitude])
                .addTo(map)
                .bindPopup(`
                    <div class="p-3">
                        <h3 class="font-semibold text-gray-900 text-lg mb-2">${bank.name}</h3>
                        <p class="text-sm text-gray-600 mb-2">${bank.address}</p>
                        <p class="text-sm text-gray-500 mb-1">📞 ${bank.contact_phone}</p>
                        <p class="text-sm text-gray-500 mb-3">✉️ ${bank.contact_email}</p>
                        <div class="flex space-x-2">
                            <button onclick="getDirections(${bank.latitude}, ${bank.longitude})"
                                    class="bg-red-600 text-white px-3 py-1 rounded text-sm">
                                Itinéraire
                            </button>
                            <button onclick="centerOnBank(${bank.latitude}, ${bank.longitude})"
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                Centrer
                            </button>
                        </div>
                    </div>
                `);

            markers.push(marker);
        }
    });
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

    fetch(`/geolocation/nearby?latitude=${lat}&longitude=${lng}&radius=${radius}`)
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

    fetch(`/geolocation/search?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(banks => {
            updateBanksList(banks);
        });
}

// Mettre à jour la liste des banques
function updateBanksList(banks) {
    const container = document.getElementById('banksList');
    const countElement = document.getElementById('resultsCount');

    countElement.textContent = `${banks.length} banque(s) trouvée(s)`;

    if (banks.length === 0) {
        container.innerHTML = '<div class="col-span-full text-center text-gray-500 py-8"><i class="fas fa-search text-4xl mb-4"></i><p>Aucune banque trouvée</p></div>';
        return;
    }

    container.innerHTML = banks.map(bank => `
        <div class="bank-card border border-gray-200 rounded-lg p-4 hover:shadow-md transition-all relative">
            <div class="feature-badge">Actif</div>
            <h3 class="font-semibold text-gray-900 mb-2">${bank.name}</h3>
            <p class="text-sm text-gray-600 mb-3">${bank.address}</p>
            <div class="text-sm text-gray-500 space-y-1 mb-3">
                <p><i class="fas fa-phone mr-2"></i>${bank.contact_phone}</p>
                <p><i class="fas fa-envelope mr-2"></i>${bank.contact_email}</p>
                ${bank.distance ? `<p><i class="fas fa-map-marker-alt mr-2"></i>${bank.distance.toFixed(1)} km</p>` : ''}
            </div>
            <div class="flex justify-between items-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    ${bank.status === 'active' ? 'Actif' : 'Inactif'}
                </span>
                <button onclick="centerOnBank(${bank.latitude}, ${bank.longitude})"
                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                    <i class="fas fa-map-marker-alt mr-1"></i>Voir sur la carte
                </button>
            </div>
        </div>
    `).join('');
}
</script>
@endpush
@endsection
