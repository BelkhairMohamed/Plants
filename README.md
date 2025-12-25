# 🌱 Plants Management System

Système complet de gestion de plantes d'intérieur avec catalogue, assistant personnel, marketplace et réseau social.

## 📋 Table des matières

- [Fonctionnalités](#-fonctionnalités)
- [Technologies](#-technologies)
- [Installation](#-installation)
- [Structure du projet](#-structure-du-projet)
- [Configuration](#️-configuration)
- [Utilisation](#-utilisation)
- [API et Endpoints](#-api-et-endpoints)
- [Base de données](#️-base-de-données)
- [Sécurité](#-sécurité)
- [Contribution](#-contribution)

## ✨ Fonctionnalités

### 1. Catalogue de plantes
- Liste complète de plantes d'intérieur avec informations détaillées
- Filtres par difficulté, lumière, eau, humidité, etc.
- Recherche par nom (commun ou scientifique)
- Pages de détails avec guides d'entretien complets
- Galerie d'images avec miniatures circulaires
- Images multiples par plante

### 2. Gestion personnelle
- Ajouter des plantes à votre collection
- Suivi des arrosages et fertilisations
- Rappels automatiques basés sur les besoins de chaque plante
- Historique complet des soins (arrosage, fertilisation, rempotage)
- Notes personnalisées par plante
- Localisation par pièce
- Intervalles personnalisés d'arrosage et de fertilisation
- Statistiques visuelles avec graphiques interactifs

### 3. Marketplace
- Achat de plantes, graines, pots, terreau, engrais, accessoires
- Panier d'achat persistant (sauvegardé en base de données)
- Synchronisation automatique du panier à la connexion
- Commandes et historique complet
- Intégration automatique des achats dans la collection
- Gestion du stock
- Catégorisation des produits

### 4. Communauté sociale
- Créer des posts avec photos
- Liker et commenter les posts (AJAX)
- Demander de l'aide avec type de post "help"
- Profils utilisateurs avec avatars et bio
- Historique des posts par utilisateur
- Modération (admin)
- Système de commentaires en temps réel

### 5. Notifications
- Rappels d'arrosage et de fertilisation
- Notifications sociales (likes, commentaires)
- Système de notifications interne
- Badge de notification en temps réel dans le header
- Mise à jour automatique toutes les 30 secondes
- Marquage comme lu/non lu

### 6. Météo intelligente
- Intégration avec API météo (OpenWeatherMap)
- Recommandations basées sur les conditions météorologiques
- Ajustement automatique des conseils de soins
- Affichage des conditions météo sur le dashboard
- Conseils personnalisés selon la ville de l'utilisateur

### 7. Administration
- Gestion du catalogue de plantes (CRUD complet)
- Gestion des images multiples par plante
- Gestion des produits marketplace
- Modération des posts et commentaires
- Interface d'administration dédiée

### 8. Tableau de bord interactif
- **Graphiques Chart.js** :
  - Graphique en donut : Répartition des plantes par emplacement
  - Graphique en barres : Plantes ajoutées sur 6 mois
  - Graphique en camembert : Types de soins effectués (30 derniers jours)
  - Graphique linéaire : Activité quotidienne (30 derniers jours)
- Cartes de statistiques avec icônes animées
- Vue d'ensemble des plantes nécessitant des soins
- Recommandations météo personnalisées
- Activité récente et posts de la communauté

### 9. Interface utilisateur moderne
- **Header moderne** avec :
  - Design élégant avec gradients et animations
  - Navigation sticky avec effet au scroll
  - Badges animés pour panier et notifications
  - Mode sombre avec toggle switch
  - Menu hamburger responsive
  - Menu flottant pour le catalogue
- **Footer redesigné** avec sections organisées
- **Page d'accueil** avec :
  - Vidéo d'arrière-plan en plein écran
  - Design moderne inspiré de Bloomscape et Hales
  - Sections Best Sellers avec cartes produits élégantes
  - Animations d'apparition du contenu
  - Design responsive
- **Mode sombre** complet avec support de tous les composants
- **Design responsive** optimisé pour mobile, tablette et desktop

## 🛠 Technologies

- **Backend**: PHP 8 (MVC custom, sans framework)
- **Frontend**: 
  - HTML5, CSS3 (avec variables CSS, animations, gradients)
  - JavaScript vanilla (ES6+)
  - Chart.js 4.4.0 pour les graphiques
  - Font Awesome 6.4.0 pour les icônes
- **Base de données**: MySQL 5.7+ (InnoDB, UTF-8)
- **Architecture**: MVC (Model-View-Controller)
- **Sessions**: PHP sessions pour authentification
- **API Externe**: OpenWeatherMap (optionnel)

## 📦 Installation

### Prérequis
- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur (ou MariaDB 10.2+)
- Serveur web (Apache/Nginx) ou XAMPP/WAMP/MAMP
- Extension PHP PDO pour MySQL
- Extension PHP GD pour le traitement d'images (optionnel)

### Étapes d'installation

1. **Cloner ou télécharger le projet**
   ```bash
   cd C:\xampp\htdocs\Plants-main
   ```

2. **Créer la base de données**
   - Ouvrir phpMyAdmin ou MySQL
   - Exécuter le script `database/SETUP_COMPLETE.sql` qui inclut :
     - Création de la base de données
     - Création de toutes les tables
     - Insertion des données d'exemple
   - **Alternative**: Exécuter `database/schema.sql` puis `database/seed_data.sql`

3. **Configurer la base de données**
   - Éditer `config/database.php`
   - Modifier les constantes si nécessaire:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'plants_management');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     ```

4. **Configurer l'URL de base**
   - Éditer `config/constants.php`
   - Modifier `BASE_URL` selon votre configuration:
     ```php
     define('BASE_URL', 'http://localhost/Plants-main');
     ```

5. **Démarrer le serveur**
   - Si vous utilisez XAMPP, démarrer Apache et MySQL
   - Accéder à `http://localhost/Plants-main`

## 📁 Structure du projet

```
Plants-main/
├── app/
│   ├── controllers/          # Contrôleurs MVC
│   ├── models/               # Modèles de données
│   ├── views/                # Vues (templates)
│   ├── core/                 # Classes de base
│   └── helpers/              # Fonctions utilitaires
├── config/                   # Configuration
│   ├── autoload.php
│   ├── constants.php
│   └── database.php
├── database/                 # Scripts SQL
│   ├── SETUP_COMPLETE.sql   # Script complet (recommandé)
│   ├── schema.sql            # Schéma de la base de données
│   ├── seed_data.sql         # Données d'exemple
│   └── add_philodendron_varieties.sql  # Données supplémentaires
├── public/                   # Point d'entrée public
│   ├── index.php
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css
│   │   └── js/
│   │       └── main.js
│   ├── Images/               # Images statiques
│   └── Video/                # Vidéos
├── index.php                 # Point d'entrée principal
└── README.md
```

## ⚙️ Configuration

### Base de données
Modifier `config/database.php` avec vos identifiants MySQL.

### URL de base
Modifier `config/constants.php` avec l'URL de votre installation.

### API Météo (optionnel)
Pour utiliser les vraies données météo:
1. Obtenir une clé API sur [OpenWeatherMap](https://openweathermap.org/api)
2. Modifier `app/models/WeatherService.php`:
   ```php
   private $apiKey = 'VOTRE_CLE_API';
   ```

### Mode sombre
Le mode sombre est activé automatiquement selon la préférence de l'utilisateur (stockée dans localStorage). Le toggle se trouve dans le header.

## 🚀 Utilisation

### Comptes par défaut

Après avoir exécuté `SETUP_COMPLETE.sql`, vous pouvez vous connecter avec:

**Admin:**
- Email: `admin@plants.com`
- Mot de passe: `admin123`

**Utilisateur:**
- Email: `user@example.com`
- Mot de passe: `user123`

### Flux principaux

1. **Inscription/Connexion**
   - Accéder à la page d'accueil
   - Cliquer sur "Inscription" ou "Connexion"
   - Créer un compte ou se connecter

2. **Explorer le catalogue**
   - Cliquer sur "Catalogue" dans le menu
   - Utiliser le menu flottant pour filtrer par catégories
   - Filtrer et rechercher des plantes
   - Voir les détails d'une plante avec galerie d'images

3. **Ajouter une plante à sa collection**
   - Sur la page de détails d'une plante
   - Cliquer sur "Ajouter à ma collection"
   - Donner un surnom (optionnel)
   - Spécifier l'emplacement (optionnel)

4. **Gérer ses plantes**
   - "Mes Plantes" → Voir toutes vos plantes
   - Cliquer sur une plante pour voir les détails
   - Marquer comme arrosé/fertilisé
   - Voir l'historique des soins
   - Ajouter des notes personnalisées

5. **Tableau de bord**
   - Voir les plantes nécessitant des soins
   - Graphiques interactifs avec statistiques
   - Recommandations météo
   - Activité récente et posts de la communauté

6. **Marketplace**
   - Parcourir les produits par catégorie
   - Ajouter au panier (panier persistant)
   - Passer commande
   - Voir l'historique des commandes

7. **Communauté**
   - Créer des posts avec photos
   - Liker et commenter
   - Voir les profils utilisateurs
   - Demander de l'aide

8. **Notifications**
   - Badge de notification en temps réel
   - Rappels de soins
   - Notifications sociales
   - Marquer comme lu

## 🔌 API et Endpoints

Le système utilise un routing simple basé sur les paramètres GET:

```
/?controller=nomController&action=nomAction
```

### Contrôleurs disponibles

- `home` - Page d'accueil
- `auth` - Authentification (login, register, logout, profile)
- `plantCatalog` - Catalogue de plantes
- `userPlant` - Gestion des plantes personnelles
- `dashboard` - Tableau de bord avec graphiques
- `marketplace` - Boutique et panier
- `social` - Communauté (posts, profils)
- `notification` - Notifications
- `admin` - Administration

### Exemples d'URLs

- Accueil: `/?controller=home&action=index` ou simplement `/`
- Catalogue: `/?controller=plantCatalog&action=index`
- Détails plante: `/?controller=plantCatalog&action=detail&id=1`
- Mes plantes: `/?controller=userPlant&action=index`
- Dashboard: `/?controller=dashboard&action=index`
- Panier: `/?controller=marketplace&action=cart`

## 🗄️ Base de données

### Installation

Exécuter `database/SETUP_COMPLETE.sql` pour une installation complète, ou :
1. `database/schema.sql` pour créer les tables
2. `database/seed_data.sql` pour les données d'exemple

**📖 Pour plus de détails, voir `database/README.md`**

### Partage de données entre machines

**⚠️ Important : Ne jamais pousser des dumps de base de données sur GitHub !**

- **Pour le développement** : Utilisez `seed_data.sql` (déjà inclus)
- **Pour vos données personnelles** : Exportez séparément et partagez via USB/cloud (pas Git)
- Voir `database/README.md` pour les instructions complètes

### Tables principales

- `users` - Utilisateurs
- `plant_catalog` - Catalogue de plantes
- `plant_catalog_images` - Images multiples par plante
- `user_plants` - Collection personnelle
- `plant_care_events` - Historique des soins
- `products` - Produits marketplace
- `cart_items` - Panier persistant
- `orders` / `order_items` - Commandes
- `posts` / `post_likes` / `comments` - Communauté
- `notifications` - Notifications système

## 🔒 Sécurité

- **Protection SQL injection** : Utilisation exclusive de prepared statements (PDO)
- **Hashage des mots de passe** : `password_hash()` avec algorithme bcrypt
- **Sessions PHP sécurisées** : Configuration sécurisée des sessions
- **Validation des entrées** : Validation côté serveur
- **Échappement des sorties** : `htmlspecialchars()` pour toutes les sorties HTML
- **Contrôle d'accès** : Vérification des rôles utilisateur

## 🤝 Contribution

Ce projet est un système complet et fonctionnel. Vous pouvez l'étendre avec:

- Upload d'images pour les plantes et avatars
- Système de paiement réel (Stripe, PayPal, etc.)
- Notifications email
- API REST complète
- Application mobile
- Système de recherche avancée
- Export de données (PDF, Excel)
- Calendrier de soins visuel
- Recommandations IA

## 📄 Licence

Ce projet est fourni tel quel pour usage éducatif et personnel.

---

**Développé avec ❤️ pour les amoureux des plantes d'intérieur 🌿**
