<?php
$pageTitle = 'Administration - Produits';
?>

<div class="container">
    <h1>Gérer les produits</h1>
    
    <div class="admin-section">
        <h2>Ajouter un nouveau produit</h2>
        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=admin&action=products" class="admin-form">
            <div class="form-group">
                <label>Nom:</label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="3"></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Catégorie:</label>
                    <select name="category" required>
                        <option value="seed">Graine</option>
                        <option value="plant">Plante</option>
                        <option value="pot">Pot</option>
                        <option value="soil">Terreau</option>
                        <option value="fertilizer">Engrais</option>
                        <option value="accessory">Accessoire</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Prix (€):</label>
                    <input type="number" name="price" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label>Stock:</label>
                    <input type="number" name="stock" min="0" value="0">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>URL image:</label>
                    <input type="url" name="image_url">
                </div>
                <div class="form-group">
                    <label>Plante liée (si graine/plante):</label>
                    <select name="related_plant_catalog_id">
                        <option value="">Aucune</option>
                        <?php foreach ($plants as $plant): ?>
                            <option value="<?php echo $plant['id']; ?>">
                                <?php echo htmlspecialchars($plant['common_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
    
    <div class="admin-section">
        <h2>Produits existants</h2>
        <div class="admin-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td><?php echo number_format($product['price'], 2); ?> €</td>
                            <td><?php echo $product['stock']; ?></td>
                            <td>
                                <form method="POST" action="<?php echo BASE_URL; ?>/?controller=admin&action=products" style="display: inline;" onsubmit="return confirm('Supprimer ce produit?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



