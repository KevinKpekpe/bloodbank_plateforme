# Implémentation de la Gestion des Stocks de Sang

## Vue d'ensemble

Cette fonctionnalité permet aux administrateurs de banque de sang de gérer les stocks de sang par groupe sanguin. Elle suit la même logique que l'inventaire des dons existant.

## Fonctionnalités implémentées

### 1. Contrôleur StockController
- **Localisation** : `app/Http/Controllers/Admin/StockController.php`
- **Fonctionnalités** :
  - `index()` : Affichage de tous les stocks avec alertes
  - `create()` : Création d'un stock individuel
  - `store()` : Sauvegarde d'un stock
  - `edit()` : Modification d'un stock
  - `update()` : Mise à jour d'un stock
  - `destroy()` : Suppression d'un stock (si quantité = 0)
  - `createMultiple()` : Création de plusieurs stocks en une fois
  - `storeMultiple()` : Sauvegarde de plusieurs stocks

### 2. Routes
- **Localisation** : `routes/web.php`
- **Routes ajoutées** :
  ```php
  Route::resource('stocks', StockController::class);
  Route::get('/stocks/create/multiple', [StockController::class, 'createMultiple'])->name('stocks.create-multiple');
  Route::post('/stocks/store/multiple', [StockController::class, 'storeMultiple'])->name('stocks.store-multiple');
  ```

### 3. Vues
- **Index** : `resources/views/admin/stocks/index.blade.php`
- **Création** : `resources/views/admin/stocks/create.blade.php`
- **Édition** : `resources/views/admin/stocks/edit.blade.php`
- **Création multiple** : `resources/views/admin/stocks/create-multiple.blade.php`

### 4. Navigation
- **Localisation** : `resources/views/layouts/partials/admin-sidebar-nav.blade.php`
- Le lien "Stocks" pointe maintenant vers `admin.stocks.index`

## Logique des seuils critiques

La fonctionnalité utilise la même logique que l'inventaire des dons :

### Définition des seuils (dans le modèle BloodStock)
- **Critique** : `quantity <= (critical_level * 0.5)` (50% du seuil critique)
- **Faible** : `quantity <= critical_level` (seuil critique)
- **Normal** : `quantity > critical_level` (au-dessus du seuil critique)
- **Rupture** : `quantity == 0` (stock vide)

### Méthodes utilisées
- `$stock->isCritical()` : Vérifie si le stock est en niveau critique
- `$stock->isLow()` : Vérifie si le stock est en niveau faible

## Interface utilisateur

### Page d'index
- Tableau complet de tous les types de sang avec leur état
- Affichage des quantités en ml et litres
- Statuts visuels (Rupture, Critique, Faible, Normal)
- Actions rapides (Créer, Modifier, Supprimer)
- Section d'alertes pour les stocks problématiques

### Création individuelle
- Formulaire simple pour un type de sang
- Validation des données
- Informations contextuelles

### Création multiple
- Interface dynamique avec checkboxes
- Actions rapides (Tout sélectionner, Valeurs par défaut)
- Validation JavaScript
- Gestion des erreurs

### Édition
- Affichage des informations actuelles
- Modification de la quantité et du seuil critique
- Recalcul automatique du statut

## Sécurité

- Vérification que l'admin ne peut gérer que les stocks de sa banque
- Validation des données côté serveur
- Protection CSRF
- Confirmation pour la suppression

## Cohérence avec l'existant

- Utilise les mêmes modèles (`BloodStock`, `BloodType`, `Bank`)
- Suit la même logique de seuils que l'inventaire des dons
- Interface cohérente avec le reste de l'application
- Même système de navigation et de layout

## Utilisation

1. **Accès** : Menu "Stocks" dans la navigation admin
2. **Création** : Boutons "Ajouter un stock" ou "Ajouter plusieurs stocks"
3. **Modification** : Lien "Modifier" dans le tableau
4. **Suppression** : Bouton "Supprimer" (uniquement si quantité = 0)

## Points techniques

- Pas de mise à jour automatique du statut (utilisation des méthodes `isLow()` et `isCritical()`)
- Gestion des stocks vides (quantité = 0)
- Interface responsive avec Tailwind CSS
- Validation côté client et serveur
- Messages de succès/erreur cohérents 
