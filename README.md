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
- [Améliorations récentes](#-améliorations-récentes)
- [Contribution](#-contribution)

## ✨ Fonctionnalités

### 1. Catalogue de plantes
- Liste complète de plantes d'intérieur avec informations détaillées
- Filtres par difficulté, lumière, eau, humidité, etc.
- Recherche par nom (commun ou scientifique)
- Pages de détails avec guides d'entretien complets
- Guides séparés pour graines et plantes matures
- Images et descriptions détaillées

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
- **Panier d'achat persistant** (sauvegardé en base de données pour utilisateurs connectés)
- Synchronisation automatique du panier à la connexion
- Commandes et historique complet
- Intégration automatique des achats dans la collection
- Gestion du stock
- Catégorisation des produits

### 4. Communauté sociale
- Créer des posts avec photos
- Liker et commenter les posts (AJAX pour une expérience fluide)
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
- Gestion des produits marketplace
- Modération des posts et commentaires
- Interface d'administration dédiée
- Gestion des utilisateurs

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
- **Footer redesigné** avec :
  - Sections organisées avec icônes
  - Liens animés au survol
  - Design moderne avec gradients
- **Page d'accueil** avec :
  - Vidéo d'arrière-plan en plein écran
  - Overlay rose subtil pour mettre en valeur les fleurs
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
   cd C:\xampp\htdocs\JobinTech\PHP\Plants
   ```

2. **Créer la base de données**
   - Ouvrir phpMyAdmin ou MySQL
   - Exécuter le script `database/schema.sql` (inclut toutes les tables, y compris `cart_items`)
   - Exécuter le script `database/seed_data.sql` pour les données d'exemple
   - **Note**: Si vous avez une base existante sans la table `cart_items`, exécutez `database/migration_add_cart_table.sql`

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
     define('BASE_URL', 'http://localhost/JobinTech/PHP/Plants');
     ```

5. **Ajouter la vidéo d'arrière-plan (optionnel)**
   - Placer votre vidéo dans `public/uploads/videos/hero-bg.webm`
   - Formats supportés : MP4, WebM
   - La vidéo sera automatiquement intégrée

6. **Démarrer le serveur**
   - Si vous utilisez XAMPP, démarrer Apache et MySQL
   - Accéder à `http://localhost/JobinTech/PHP/Plants`

## 📁 Structure du projet

```
Plants/
├── app/
│   ├── controllers/          # Contrôleurs MVC
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── HomeController.php
│   │   ├── MarketplaceController.php
│   │   ├── NotificationController.php
│   │   ├── PlantCatalogController.php
│   │   ├── SocialController.php
│   │   └── UserPlantController.php
│   ├── models/               # Modèles de données
│   │   ├── Cart.php
│   │   ├── Comment.php
│   │   ├── Notification.php
│   │   ├── Order.php
│   │   ├── PlantCareEvent.php
│   │   ├── PlantCatalog.php
│   │   ├── Post.php
│   │   ├── Product.php
│   │   ├── User.php
│   │   ├── UserPlant.php
│   │   └── WeatherService.php
│   ├── helpers/              # Fonctions utilitaires
│   │   └── cart_helper.php
│   ├── views/                # Vues (templates)
│   │   ├── layouts/
│   │   │   ├── header.php
│   │   │   └── footer.php
│   │   ├── admin/
│   │   │   ├── moderate.php
│   │   │   ├── plants.php
│   │   │   └── products.php
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   ├── profile.php
│   │   │   └── register.php
│   │   ├── dashboard/
│   │   │   └── index.php
│   │   ├── errors/
│   │   │   └── 404.php
│   │   ├── home/
│   │   │   └── index.php
│   │   ├── marketplace/
│   │   │   ├── cart.php
│   │   │   ├── checkout.php
│   │   │   ├── detail.php
│   │   │   ├── index.php
│   │   │   ├── order_success.php
│   │   │   └── orders.php
│   │   ├── notification/
│   │   │   └── index.php
│   │   ├── plant_catalog/
│   │   │   ├── detail.php
│   │   │   └── index.php
│   │   ├── social/
│   │   │   ├── create.php
│   │   │   ├── detail.php
│   │   │   ├── index.php
│   │   │   └── profile.php
│   │   └── user_plant/
│   │       ├── detail.php
│   │       └── index.php
│   └── core/                 # Classes de base
│       ├── Controller.php
│       └── Router.php
├── config/                   # Configuration
│   ├── autoload.php
│   ├── constants.php
│   └── database.php
├── database/                 # Scripts SQL
│   ├── schema.sql            # Schéma complet de la base de données
│   ├── seed_data.sql        # Données d'exemple
│   ├── migration_add_cart_table.sql
│   ├── add_cart_table_only.sql
│   ├── README_CART_MIGRATION.md
│   └── INSTRUCTIONS_CART.md
├── public/                   # Point d'entrée public
│   ├── index.php
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css    # Styles complets avec mode sombre
│   │   ├── js/
│   │   │   └── main.js      # JavaScript pour interactions
│   │   └── img/
│   └── uploads/
│       ├── avatars/         # Avatars des utilisateurs
│       └── videos/          # Vidéos (ex: hero-bg.webm)
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

Après avoir exécuté `seed_data.sql`, vous pouvez vous connecter avec:

**Admin:**
- Email: `admin@plants.com`
- Mot de passe: `admin123`

**Utilisateur:**
- Email: `user@example.com`
- Mot de passe: `user123`

### Flux principaux

1. **Inscription/Connexion**
   - `/` → Cliquer sur "Inscription" ou "Connexion"
   - Créer un compte ou se connecter
   - Le panier de session sera synchronisé avec le panier en base de données

2. **Explorer le catalogue**
   - `/` → "Catalogue"
   - Filtrer et rechercher des plantes
   - Voir les détails d'une plante avec guides complets

3. **Ajouter une plante à sa collection**
   - Sur la page de détails d'une plante
   - Cliquer sur "Ajouter à ma collection"
   - Choisir le type (graine/plante mature)
   - Donner un surnom (optionnel)
   - Spécifier l'emplacement (optionnel)

4. **Gérer ses plantes**
   - "Mes Plantes" → Voir toutes vos plantes
   - Cliquer sur une plante pour voir les détails
   - Marquer comme arrosé/fertilisé
   - Voir l'historique des soins
   - Ajouter des notes personnalisées

5. **Tableau de bord**
   - Voir les plantes nécessitant des soins (arrosage, fertilisation)
   - **Graphiques interactifs** :
     - Répartition par emplacement
     - Évolution mensuelle des ajouts
     - Types de soins effectués
     - Activité quotidienne
   - Cartes de statistiques avec totaux
   - Voir les recommandations météo
   - Voir l'activité récente et les posts de la communauté

6. **Marketplace**
   - Parcourir les produits par catégorie
   - Ajouter au panier (panier persistant pour utilisateurs connectés)
   - Le panier est sauvegardé et restauré automatiquement à la connexion
   - Badge de panier en temps réel dans le header
   - Passer commande
   - Voir l'historique des commandes

7. **Communauté**
   - Créer des posts avec photos
   - Liker et commenter (AJAX pour une expérience fluide)
   - Voir les profils utilisateurs
   - Demander de l'aide avec type de post "help"

8. **Notifications**
   - Badge de notification en temps réel dans le header
   - Notifications pour rappels de soins
   - Notifications sociales (likes, commentaires)
   - Marquer comme lu

## 🔌 API et Endpoints

Le système utilise un routing simple basé sur les paramètres GET:

```
/?controller=nomController&action=nomAction
```

### Contrôleurs disponibles

- `home` - Page d'accueil avec vidéo d'arrière-plan
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
- Profil utilisateur: `/?controller=social&action=profile&user_id=1`

### Endpoints AJAX

- `/?controller=marketplace&action=getCartCount` - Obtenir le nombre d'articles dans le panier (JSON)
- `/?controller=notification&action=getUnreadCount` - Obtenir le nombre de notifications non lues (JSON)
- `/?controller=social&action=like` - Liker un post (AJAX, JSON)

## 🗄️ Base de données

### Schéma complet

Le système utilise MySQL avec le moteur InnoDB et l'encodage UTF-8.

### Tables principales

#### `users`
- Gestion des utilisateurs
- Champs : id, email, password (hashé), username, bio, avatar_url, role, city, created_at
- Index : email, username

#### `plant_catalog`
- Catalogue de toutes les plantes disponibles
- Champs : id, common_name, scientific_name, description, difficulty_level, light_requirement, water_requirement, humidity_preference, temperature_min, temperature_max, image_url, recommended_for_beginners, default_watering_interval_days, default_fertilizing_interval_days, seed_guide, mature_plant_guide, created_at, updated_at
- Index : difficulty_level, light_requirement, common_name

#### `user_plants`
- Collection personnelle de chaque utilisateur
- Champs : id, user_id, plant_catalog_id, nickname_for_plant, is_from_marketplace, purchase_date, acquisition_type, last_watering_date, last_fertilizing_date, custom_watering_interval_days, custom_fertilizing_interval_days, room_location, notes, created_at
- Clés étrangères : user_id → users(id), plant_catalog_id → plant_catalog(id)
- Index : user_id, plant_catalog_id

#### `plant_care_events`
- Historique des soins apportés aux plantes
- Champs : id, user_plant_id, event_type (watering, fertilizing, repotting, other), event_date, notes, created_at
- Clé étrangère : user_plant_id → user_plants(id)
- Index : user_plant_id, event_date

#### `plant_photos`
- Photos des plantes des utilisateurs
- Champs : id, user_plant_id, image_url, caption, created_at
- Clé étrangère : user_plant_id → user_plants(id)

#### `products`
- Produits du marketplace
- Champs : id, name, description, category (seed, plant, pot, soil, fertilizer, accessory), price, image_url, stock, is_seed, related_plant_catalog_id, created_at, updated_at
- Clé étrangère : related_plant_catalog_id → plant_catalog(id)
- Index : category, related_plant_catalog_id

#### `cart_items`
- **Panier persistant** pour utilisateurs connectés
- Champs : id, user_id, product_id, quantity, created_at, updated_at
- Clés étrangères : user_id → users(id), product_id → products(id)
- Contrainte unique : (user_id, product_id)
- Index : user_id, product_id

#### `orders`
- Commandes passées
- Champs : id, user_id, total_amount, status (pending, processing, shipped, delivered, cancelled), shipping_address, created_at, updated_at
- Clé étrangère : user_id → users(id)
- Index : user_id, status

#### `order_items`
- Articles des commandes
- Champs : id, order_id, product_id, quantity, unit_price
- Clés étrangères : order_id → orders(id), product_id → products(id)
- Index : order_id

#### `posts`
- Posts de la communauté
- Champs : id, user_id, content_text, image_url, related_user_plant_id, post_type (normal, help, article), created_at, updated_at
- Clés étrangères : user_id → users(id), related_user_plant_id → user_plants(id)
- Index : user_id, post_type, created_at

#### `post_likes`
- Likes sur les posts
- Champs : id, post_id, user_id, created_at
- Clés étrangères : post_id → posts(id), user_id → users(id)
- Contrainte unique : (post_id, user_id)
- Index : post_id, user_id

#### `comments`
- Commentaires sur les posts
- Champs : id, post_id, user_id, content_text, created_at
- Clés étrangères : post_id → posts(id), user_id → users(id)
- Index : post_id, user_id

#### `notifications`
- Notifications système
- Champs : id, user_id, type, message, link_url, is_read, created_at
- Clé étrangère : user_id → users(id)
- Index : user_id, is_read, created_at

### Relations principales

```
users
  ├── user_plants (1:N)
  │     └── plant_care_events (1:N)
  │     └── plant_photos (1:N)
  ├── cart_items (1:N)
  ├── orders (1:N)
  │     └── order_items (1:N)
  ├── posts (1:N)
  │     ├── post_likes (1:N)
  │     └── comments (1:N)
  └── notifications (1:N)

plant_catalog
  ├── user_plants (1:N)
  └── products (1:N)
```

### Visualisation du schéma

Pour visualiser le schéma de la base de données, vous pouvez utiliser :

```sql
-- Dans MySQL Workbench ou phpMyAdmin
SHOW TABLES;
DESCRIBE table_name;
```

Ou utiliser des outils comme :
- MySQL Workbench (Reverse Engineering)
- phpMyAdmin (Designer)
- DBeaver
- dbdiagram.io

## 🔒 Sécurité

- **Protection SQL injection** : Utilisation exclusive de prepared statements (PDO)
- **Hashage des mots de passe** : `password_hash()` avec algorithme bcrypt
- **Sessions PHP sécurisées** : Configuration sécurisée des sessions
- **Validation des entrées utilisateur** : Validation côté serveur
- **Protection CSRF** : À implémenter pour les formulaires critiques
- **Échappement des sorties** : `htmlspecialchars()` pour toutes les sorties HTML
- **Contrôle d'accès** : Vérification des rôles utilisateur
- **Sanitization** : Nettoyage des données utilisateur

## 🎨 Améliorations récentes

### Interface utilisateur
- ✅ Header moderne avec design élégant, gradients et animations
- ✅ Footer redesigné avec sections organisées et animations
- ✅ Mode sombre complet avec toggle dans le header
- ✅ Badges animés pour panier et notifications en temps réel
- ✅ Menu hamburger responsive avec animations fluides
- ✅ Navigation sticky avec effet au scroll

### Dashboard
- ✅ Graphiques Chart.js interactifs :
  - Graphique en donut : Répartition des plantes par emplacement
  - Graphique en barres : Évolution mensuelle (6 mois)
  - Graphique en camembert : Types de soins (30 jours)
  - Graphique linéaire : Activité quotidienne (30 jours)
- ✅ Cartes de statistiques avec icônes animées
- ✅ Animations d'apparition pour tous les graphiques

### Page d'accueil
- ✅ Vidéo d'arrière-plan en plein écran
- ✅ Overlay rose subtil pour mettre en valeur les fleurs
- ✅ Animations d'apparition du contenu
- ✅ Design responsive optimisé

### Panier
- ✅ Panier persistant en base de données
- ✅ Synchronisation automatique à la connexion
- ✅ Badge de panier en temps réel
- ✅ Mise à jour AJAX du nombre d'articles

### Notifications
- ✅ Badge de notification en temps réel
- ✅ Mise à jour automatique toutes les 30 secondes
- ✅ Interface de notifications améliorée

## 📝 Notes

- Le système est conçu pour être extensible et modulaire
- Les images sont actuellement gérées via URLs (à améliorer avec upload de fichiers)
- L'API météo utilise des données mockées par défaut (configurable)
- Le système de paiement est simulé (pas de vrai paiement)
- **Panier persistant** : Le panier des utilisateurs connectés est sauvegardé en base de données et persiste après déconnexion. Les utilisateurs non connectés utilisent un panier basé sur la session.
- Le mode sombre est stocké dans le localStorage du navigateur
- Les graphiques du dashboard nécessitent Chart.js (chargé via CDN)

## 🤝 Contribution

Ce projet est un système complet et fonctionnel. Vous pouvez l'étendre avec:

- Upload d'images pour les plantes et avatars
- Système de paiement réel (Stripe, PayPal, etc.)
- Notifications email
- API REST complète
- Application mobile (React Native, Flutter)
- Système de recherche avancée
- Export de données (PDF, Excel)
- Calendrier de soins visuel
- Système de tags pour les plantes
- Recommandations IA basées sur l'environnement

## 📄 Licence

Ce projet est fourni tel quel pour usage éducatif et personnel.

---

**Développé avec ❤️ pour les amoureux des plantes d'intérieur 🌿**
