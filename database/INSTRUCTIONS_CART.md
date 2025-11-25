# 🛒 Instructions pour Activer le Panier Persistant

## 📝 Étape 1: Ouvrir phpMyAdmin

1. Ouvrez votre navigateur
2. Allez sur `http://localhost/phpmyadmin`
3. Connectez-vous (généralement : utilisateur `root`, mot de passe vide)

## 📝 Étape 2: Sélectionner la Base de Données

1. Dans le menu de gauche, cliquez sur `plants_management`
2. Si la base n'existe pas, créez-la d'abord

## 📝 Étape 3: Exécuter la Migration

### Option A: Via l'onglet SQL (RECOMMANDÉ)

1. Cliquez sur l'onglet **"SQL"** en haut
2. Copiez-collez ce code SQL :

```sql
CREATE TABLE IF NOT EXISTS cart_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id),
    INDEX idx_user_id (user_id),
    INDEX idx_product_id (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

3. Cliquez sur **"Exécuter"** ou appuyez sur **F5**

### Option B: Via l'Import

1. Cliquez sur l'onglet **"Importer"** en haut
2. Cliquez sur **"Choisir un fichier"**
3. Sélectionnez le fichier : `database/migration_add_cart_table.sql`
4. Cliquez sur **"Exécuter"**

## ✅ Vérification

Après l'exécution, vous devriez voir :
- ✅ Message de succès : "La requête SQL a été exécutée avec succès"
- ✅ Une nouvelle table `cart_items` dans la liste des tables à gauche

## 🎯 C'est tout !

Maintenant votre panier sera sauvegardé dans la base de données et persistera même après déconnexion.

