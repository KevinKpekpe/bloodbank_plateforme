# 🗺️ Système de Géolocalisation - BloodLink

## Vue d'ensemble

Le système de géolocalisation de BloodLink permet aux utilisateurs de trouver rapidement les banques de sang les plus proches de leur position. Cette fonctionnalité utilise Leaflet.js pour afficher une carte interactive avec des marqueurs pour chaque banque de sang.

## 🚀 Fonctionnalités

### Carte Interactive
- **Affichage en temps réel** : Carte interactive avec marqueurs pour toutes les banques de sang
- **Géolocalisation automatique** : Détection de la position de l'utilisateur
- **Recherche par nom** : Recherche de banques par nom ou adresse
- **Filtrage par rayon** : Recherche de banques dans un rayon défini (5, 10, 20, 50 km)

### Fonctionnalités Avancées
- **Popups informatifs** : Détails complets de chaque banque au clic
- **Itinéraires** : Intégration avec Google Maps pour les directions
- **Statistiques en temps réel** : Nombre de banques trouvées
- **Interface responsive** : Compatible mobile et desktop

## 📍 Accès à la Carte

### Via la Navigation
- **Menu principal** : Lien "Carte" dans la barre de navigation
- **Page d'accueil** : Bouton "Carte Interactive" dans la section hero
- **Footer** : Lien "Carte Interactive" dans les liens rapides

### URL Directe
```
/map
```

## 🛠️ Utilisation

### 1. Recherche par Géolocalisation
1. Cliquez sur le bouton de localisation (icône de flèche)
2. Autorisez l'accès à votre position
3. La carte se centre automatiquement sur votre position
4. Les banques proches sont affichées dans la liste

### 2. Recherche par Nom
1. Tapez le nom d'une banque dans la barre de recherche
2. Cliquez sur le bouton de recherche
3. Les résultats s'affichent dans la liste

### 3. Filtrage par Rayon
1. Sélectionnez un rayon de recherche (5, 10, 20, 50 km)
2. Utilisez la géolocalisation ou la recherche
3. Seules les banques dans le rayon sélectionné sont affichées

### 4. Interaction avec les Marqueurs
- **Clic simple** : Affiche les détails de la banque
- **Bouton "Itinéraire"** : Ouvre Google Maps avec l'itinéraire
- **Bouton "Centrer"** : Centre la carte sur la banque

## 🗄️ Structure des Données

### Modèle Bank
```php
class Bank extends Model
{
    protected $fillable = [
        'name',
        'address',
        'contact_phone',
        'contact_email',
        'latitude',      // Coordonnée GPS
        'longitude',     // Coordonnée GPS
        'status'         // active/inactive
    ];
}
```

### API Endpoints
- `GET /map` - Page principale de la carte
- `GET /geolocation/nearby` - Banques proches d'une position
- `GET /geolocation/search` - Recherche par nom/adresse
- `GET /geolocation/all-banks` - Toutes les banques avec coordonnées
- `GET /geolocation/bank/{bank}` - Détails d'une banque spécifique

## 🎨 Interface Utilisateur

### Composants Visuels
- **Carte Leaflet** : Carte interactive principale
- **Barre de recherche** : Recherche par nom/adresse
- **Sélecteur de rayon** : Filtrage par distance
- **Bouton de géolocalisation** : Détection de position
- **Liste des banques** : Affichage des résultats
- **Statistiques** : Compteurs en temps réel

### Design Responsive
- **Desktop** : Carte et liste côte à côte
- **Tablet** : Carte au-dessus de la liste
- **Mobile** : Carte pleine largeur, liste en dessous

## 🔧 Configuration Technique

### Dépendances
```html
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
```

### Coordonnées par Défaut
```javascript
// Kinshasa, RDC
const defaultLat = -4.4419;
const defaultLng = 15.2663;
```

### Calcul de Distance
Utilisation de la formule de Haversine pour calculer la distance entre deux points GPS :
```sql
(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance
```

## 📊 Statistiques

### Métriques Disponibles
- **Nombre total de banques** : Toutes les banques enregistrées
- **Banques avec géolocalisation** : Banques ayant des coordonnées GPS
- **Banques actives** : Banques avec statut "active"
- **Disponibilité** : Service 24h/24

### Affichage en Temps Réel
- Compteur de résultats de recherche
- Mise à jour dynamique de la liste
- Indicateurs visuels de statut

## 🔒 Sécurité et Performance

### Sécurité
- Validation des coordonnées GPS
- Protection contre les injections SQL
- Limitation du rayon de recherche (max 50 km)

### Performance
- Chargement asynchrone des données
- Mise en cache des résultats
- Optimisation des requêtes SQL

## 🚀 Améliorations Futures

### Fonctionnalités Planifiées
- [ ] Notifications push pour nouvelles banques
- [ ] Historique des recherches
- [ ] Favoris et notes personnelles
- [ ] Intégration avec d'autres services de cartographie
- [ ] Mode hors ligne avec cache local

### Optimisations Techniques
- [ ] Service Worker pour le cache
- [ ] Compression des données de géolocalisation
- [ ] API GraphQL pour les requêtes complexes
- [ ] Tests automatisés pour la géolocalisation

## 📝 Notes de Développement

### Points d'Attention
1. **Permissions GPS** : Gestion des cas où l'utilisateur refuse l'accès
2. **Données manquantes** : Gestion des banques sans coordonnées
3. **Performance mobile** : Optimisation pour les connexions lentes
4. **Accessibilité** : Support des lecteurs d'écran

### Débogage
- Console JavaScript pour les erreurs de géolocalisation
- Logs serveur pour les requêtes API
- Validation des coordonnées GPS

---

**Développé avec ❤️ pour BloodLink** 