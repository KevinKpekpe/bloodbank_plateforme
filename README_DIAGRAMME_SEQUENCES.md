# Diagramme de Séquences - Application BloodLink

## Vue d'ensemble

Ce document présente le diagramme de séquences de l'application BloodLink, une plateforme de gestion de banques de sang qui facilite la prise de rendez-vous pour les dons de sang et la gestion des stocks.

## Architecture du Système

### Acteurs Principaux

1. **Donneur** : Utilisateur qui souhaite faire un don de sang
2. **Admin Banque** : Administrateur d'une banque de sang spécifique
3. **Super Admin** : Administrateur global du système

### Composants Principaux

- **Interface Web** : Interface utilisateur (Laravel Blade + Tailwind CSS)
- **Contrôleurs** : Gestion de la logique métier
- **Modèles** : Représentation des entités de données
- **Services** : Logique métier spécialisée
- **Base de Données** : Stockage persistant des données

## Processus Principaux

### 1. Processus d'Authentification

**Objectif** : Permettre aux utilisateurs de se connecter au système

**Séquence** :
1. L'utilisateur accède à la page de connexion
2. Saisie des identifiants (email/mot de passe)
3. Vérification des identifiants dans la base de données
4. Création de session et redirection vers le dashboard approprié

**Points clés** :
- Vérification du statut du compte (actif/inactif)
- Gestion des rôles (donneur/admin/superadmin)
- Vérification de l'email si nécessaire

### 2. Processus de Prise de Rendez-vous

**Objectif** : Permettre aux donneurs de planifier un don de sang

**Séquence** :
1. Le donneur accède au formulaire de prise de rendez-vous
2. Sélection d'une banque de sang active
3. Choix de la date et de l'heure (8h-18h)
4. Vérification des disponibilités et conflits
5. Création du rendez-vous avec statut "pending"

**Validations** :
- Vérification que la banque est active
- Contrôle des heures d'ouverture
- Vérification de l'absence de conflit de rendez-vous
- Validation de l'éligibilité du donneur (3 mois minimum entre dons)

### 3. Processus de Gestion des Rendez-vous (Admin)

**Objectif** : Permettre aux administrateurs de banque de gérer les rendez-vous

**Séquences** :

#### Consultation des Rendez-vous
1. L'admin accède à la liste des rendez-vous de sa banque
2. Filtrage par statut, date, etc.
3. Affichage des détails de chaque rendez-vous

#### Confirmation d'un Rendez-vous
1. L'admin confirme un rendez-vous en attente
2. Mise à jour du statut vers "confirmed"
3. Envoi d'une notification au donneur
4. Mise à jour de l'interface

#### Rejet d'un Rendez-vous
1. L'admin rejette un rendez-vous avec motif
2. Mise à jour du statut vers "cancelled"
3. Envoi d'une notification au donneur
4. Mise à jour de l'interface

### 4. Processus de Don de Sang

**Objectif** : Enregistrer un don de sang effectué

**Séquence** :
1. L'admin marque un rendez-vous confirmé comme terminé
2. Saisie du volume de sang collecté (0.3-0.5L)
3. Création automatique d'un enregistrement de don
4. Mise à jour du stock de la banque
5. Mise à jour du statut du rendez-vous vers "completed"

**Actions automatiques** :
- Récupération du groupe sanguin du donneur
- Calcul de la quantité en ml (volume × 1000)
- Mise à jour des statistiques du donneur
- Création/mise à jour du stock de sang

### 5. Processus de Gestion des Dons

**Objectif** : Suivre le cycle de vie d'un don de sang

**États possibles** :
- **collected** : Don collecté
- **processed** : Don traité en laboratoire
- **available** : Don disponible pour utilisation
- **used** : Don utilisé pour un patient
- **expired** : Don expiré (42 jours)

**Séquences** :

#### Traitement d'un Don
1. L'admin marque un don collecté comme traité
2. Mise à jour du statut vers "processed"
3. Envoi d'une notification au donneur

#### Mise à Disposition d'un Don
1. L'admin rend un don traité disponible
2. Mise à jour du statut vers "available"
3. Envoi d'une notification au donneur

#### Utilisation d'un Don
1. L'admin marque un don disponible comme utilisé
2. Mise à jour du statut vers "used"
3. Envoi d'une notification au donneur

### 6. Processus de Consultation de la Carte

**Objectif** : Permettre aux utilisateurs de localiser les banques de sang

**Fonctionnalités** :
- Affichage de toutes les banques actives sur une carte
- Recherche par proximité géographique
- Filtrage par groupe sanguin disponible
- Affichage des informations de contact

**Séquences** :

#### Consultation Générale
1. L'utilisateur accède à la carte
2. Récupération de toutes les banques actives
3. Affichage sur la carte interactive

#### Recherche par Proximité
1. L'utilisateur saisit sa localisation
2. Calcul des distances vers toutes les banques
3. Tri par proximité
4. Affichage des résultats

#### Filtrage par Groupe Sanguin
1. L'utilisateur sélectionne un groupe sanguin
2. Vérification des stocks disponibles
3. Affichage des banques avec stock
4. Mise à jour de la carte

### 7. Processus de Gestion des Notifications

**Objectif** : Informer les utilisateurs des événements importants

**Types de notifications** :
- Confirmation/rejet de rendez-vous
- Rappels de rendez-vous
- Statut des dons (traité, disponible, utilisé, expiré)
- Alertes de stock faible
- Notifications système

**Séquences** :
1. Création d'une notification par le service approprié
2. Stockage en base de données
3. Affichage dans l'interface utilisateur
4. Marquage comme lue par l'utilisateur

## Points Techniques Importants

### Sécurité
- Authentification obligatoire pour les actions sensibles
- Vérification des permissions par rôle
- Validation des données côté serveur
- Protection CSRF sur tous les formulaires

### Performance
- Pagination des listes volumineuses
- Indexation des requêtes fréquentes
- Mise en cache des données statiques
- Optimisation des requêtes de géolocalisation

### Fiabilité
- Transactions de base de données pour les opérations critiques
- Gestion des erreurs et exceptions
- Logs détaillés pour le debugging
- Validation des données d'entrée

## Évolutions Possibles

### Fonctionnalités Futures
- Système de rappels automatiques
- Intégration avec des systèmes hospitaliers
- Application mobile
- API REST pour intégrations tierces
- Système de badges et récompenses
- Campagnes de don de sang

### Améliorations Techniques
- Mise en cache Redis
- Queue pour les tâches asynchrones
- WebSockets pour les notifications temps réel
- Microservices pour la scalabilité
- Tests automatisés complets

## Conclusion

Ce diagramme de séquences illustre les interactions principales entre les acteurs et les composants du système BloodLink. Il met en évidence la complexité des processus de gestion des dons de sang tout en maintenant une architecture claire et modulaire.

L'application suit les bonnes pratiques de développement Laravel et offre une expérience utilisateur fluide pour tous les types d'utilisateurs (donneurs, administrateurs de banque, super administrateurs). 
