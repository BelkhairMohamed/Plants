# 🛒 Migration: Panier Persistant

## 📋 Instructions SIMPLES

### ✅ Méthode la PLUS SIMPLE (phpMyAdmin)

1. **Ouvrez phpMyAdmin** : `http://localhost/phpmyadmin`
2. **Sélectionnez** la base de données `plants_management` (menu de gauche)
3. **Cliquez sur l'onglet "SQL"** (en haut)
4. **Copiez-collez ce code** :

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

5. **Cliquez sur "Exécuter"** ou appuyez sur **F5**

### ✅ Vérification

Après l'exécution, vous devriez voir :
- ✅ Message : "La requête SQL a été exécutée avec succès"
- ✅ Une nouvelle table `cart_items` dans la liste des tables

---

### 📝 Autres Options

**Option 1: Si vous créez une nouvelle base de données**
- Exécutez simplement `database/schema.sql` - la table `cart_items` est déjà incluse

**Option 2: Via ligne de commande MySQL**
```bash
mysql -u root -p plants_management < database/migration_add_cart_table.sql
```

## ✅ Fonctionnalités

Après la migration :
- ✅ Le panier est sauvegardé dans la base de données
- ✅ Le panier persiste après déconnexion
- ✅ Le panier est restauré à la reconnexion
- ✅ Les utilisateurs non connectés utilisent toujours la session (compatible)
- ✅ Synchronisation automatique du panier session → DB au login

## 🔄 Comment ça fonctionne

1. **Utilisateur connecté** : Panier sauvegardé dans `cart_items` (table DB)
2. **Utilisateur non connecté** : Panier sauvegardé dans `$_SESSION['cart']`
3. **Au login** : Le panier de session est synchronisé vers la DB
4. **Après login** : Le panier est chargé depuis la DB

