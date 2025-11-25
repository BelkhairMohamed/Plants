# 🌱 Plants Management System

Système complet de gestion de plantes d'intérieur avec catalogue, assistant personnel, marketplace et réseau social.

## 📋 Table des matières

- [Fonctionnalités](#fonctionnalités)
- [Technologies](#technologies)
- [Installation](#installation)
- [Structure du projet](#structure-du-projet)
- [Configuration](#configuration)
- [Utilisation](#utilisation)
- [API et Endpoints](#api-et-endpoints)
- [Base de données](#base-de-données)

## ✨ Fonctionnalités

### 1. Catalogue de plantes
- Liste complète de plantes d'intérieur avec informations détaillées
- Filtres par difficulté, lumière, eau, etc.
- Recherche par nom
- Pages de détails avec guides d'entretien

### 2. Gestion personnelle
- Ajouter des plantes à votre collection
- Suivi des arrosages et fertilisations
- Rappels automatiques basés sur les besoins de chaque plante
- Historique des soins
- Notes personnalisées par plante

### 3. Marketplace (optionnel)
- Achat de plantes, graines, pots, terreau, engrais
- Panier d'achat
- Commandes et historique
- Intégration automatique des achats dans la collection

### 4. Communauté sociale
- Créer des posts avec photos
- Liker et commenter les posts
- Demander de l'aide
- Profils utilisateurs
- Modération (admin)

### 5. Notifications
- Rappels d'arrosage et de fertilisation
- Notifications sociales (likes, commentaires)
- Système de notifications interne

### 6. Météo intelligente
- Intégration avec API météo (OpenWeatherMap)
- Recommandations basées sur les conditions météorologiques
- Ajustement automatique des conseils de soins

### 7. Administration
- Gestion du catalogue de plantes
- Gestion des produits marketplace
- Modération des posts et commentaires

## 🛠 Technologies

- **Backend**: PHP 8 (MVC custom, sans framework)
- **Frontend**: HTML5, CSS3, JavaScript vanilla
- **Base de données**: MySQL (InnoDB, UTF-8)
- **Architecture**: MVC (Model-View-Controller)

## 📦 Installation

### Prérequis
- PHP 8.0 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx) ou XAMPP/WAMP
- Extension PHP PDO pour MySQL

### Étapes d'installation

1. **Cloner ou télécharger le projet**
   ```bash
   cd C:\xampp\htdocs\JobinTech\PHP\Plants
   ```

2. **Créer la base de données**
   - Ouvrir phpMyAdmin ou MySQL
   - Exécuter le script `database/schema.sql`
   - Exécuter le script `database/seed_data.sql` pour les données d'exemple

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

5. **Démarrer le serveur**
   - Si vous utilisez XAMPP, démarrer Apache et MySQL
   - Accéder à `http://localhost/JobinTech/PHP/Plants`

## 📁 Structure du projet

```
Plants/
├── app/
│   ├── controllers/          # Contrôleurs MVC
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   ├── PlantCatalogController.php
│   │   ├── UserPlantController.php
│   │   ├── DashboardController.php
│   │   ├── MarketplaceController.php
│   │   ├── SocialController.php
│   │   ├── NotificationController.php
│   │   └── AdminController.php
│   ├── models/               # Modèles de données
│   │   ├── User.php
│   │   ├── PlantCatalog.php
│   │   ├── UserPlant.php
│   │   ├── PlantCareEvent.php
│   │   ├── Product.php
│   │   ├── Order.php
│   │   ├── Post.php
│   │   ├── Comment.php
│   │   ├── Notification.php
│   │   └── WeatherService.php
│   ├── views/                # Vues (templates)
│   │   ├── layouts/
│   │   ├── home/
│   │   ├── auth/
│   │   ├── plant_catalog/
│   │   ├── user_plant/
│   │   ├── dashboard/
│   │   ├── marketplace/
│   │   ├── social/
│   │   ├── notification/
│   │   ├── admin/
│   │   └── errors/
│   └── core/                 # Classes de base
│       ├── Controller.php
│       └── Router.php
├── config/                   # Configuration
│   ├── database.php
│   ├── constants.php
│   └── autoload.php
├── database/                 # Scripts SQL
│   ├── schema.sql
│   └── seed_data.sql
├── public/                   # Point d'entrée public
│   ├── index.php
│   └── assets/
│       ├── css/
│       │   └── style.css
│       ├── js/
│       │   └── main.js
│       └── img/
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

2. **Explorer le catalogue**
   - `/` → "Catalogue"
   - Filtrer et rechercher des plantes
   - Voir les détails d'une plante

3. **Ajouter une plante à sa collection**
   - Sur la page de détails d'une plante
   - Cliquer sur "Ajouter à ma collection"
   - Choisir le type (graine/plante mature)
   - Donner un surnom (optionnel)

4. **Gérer ses plantes**
   - "Mes Plantes" → Voir toutes vos plantes
   - Cliquer sur une plante pour voir les détails
   - Marquer comme arrosé/fertilisé
   - Voir l'historique des soins

5. **Tableau de bord**
   - Voir les plantes nécessitant des soins
   - Voir les recommandations météo
   - Voir l'activité récente

6. **Marketplace**
   - Parcourir les produits
   - Ajouter au panier
   - Passer commande

7. **Communauté**
   - Créer des posts
   - Liker et commenter
   - Voir les profils

## 🔌 API et Endpoints

Le système utilise un routing simple basé sur les paramètres GET:

```
/?controller=nomController&action=nomAction
```

### Contrôleurs disponibles

- `home` - Page d'accueil
- `auth` - Authentification (login, register, logout)
- `plantCatalog` - Catalogue de plantes
- `userPlant` - Gestion des plantes personnelles
- `dashboard` - Tableau de bord
- `marketplace` - Boutique
- `social` - Communauté
- `notification` - Notifications
- `admin` - Administration

### Exemples d'URLs

- Accueil: `/?controller=home&action=index` ou simplement `/`
- Catalogue: `/?controller=plantCatalog&action=index`
- Détails plante: `/?controller=plantCatalog&action=detail&id=1`
- Mes plantes: `/?controller=userPlant&action=index`
- Dashboard: `/?controller=dashboard&action=index`

## 🗄️ Base de données

### Tables principales

- `users` - Utilisateurs
- `plant_catalog` - Catalogue de plantes
- `user_plants` - Plantes des utilisateurs
- `plant_care_events` - Historique des soins
- `products` - Produits marketplace
- `orders` - Commandes
- `order_items` - Articles des commandes
- `posts` - Posts sociaux
- `post_likes` - Likes sur les posts
- `comments` - Commentaires
- `notifications` - Notifications

Voir `database/schema.sql` pour le schéma complet.

## 🔒 Sécurité

- Protection SQL injection (prepared statements)
- Hashage des mots de passe (password_hash)
- Sessions PHP sécurisées
- Validation des entrées utilisateur

## 📝 Notes

- Le système est conçu pour être extensible
- Les images sont actuellement gérées via URLs (à améliorer avec upload de fichiers)
- L'API météo utilise des données mockées par défaut
- Le système de paiement est simulé (pas de vrai paiement)

## 🤝 Contribution

Ce projet est un système complet et fonctionnel. Vous pouvez l'étendre avec:
- Upload d'images
- Système de paiement réel
- Notifications email
- API REST complète
- Application mobile

## 📄 Licence

Ce projet est fourni tel quel pour usage éducatif et personnel.

---

**Développé avec ❤️ pour les amoureux des plantes d'intérieur**

