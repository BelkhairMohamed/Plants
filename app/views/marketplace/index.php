<?php
$pageTitle = 'Boutique';
?>

<div class="container">
    <h1>Boutique</h1>
    
    <div class="filters">
        <form method="GET" action="<?php echo BASE_URL; ?>/">
            <input type="hidden" name="controller" value="marketplace">
            <input type="hidden" name="action" value="index">
            <input type="text" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
            <select name="category">
                <option value="">Toutes les catégories</option>
                <option value="seed" <?php echo ($filters['category'] ?? '') === 'seed' ? 'selected' : ''; ?>>Graines</option>
                <option value="plant" <?php echo ($filters['category'] ?? '') === 'plant' ? 'selected' : ''; ?>>Plantes</option>
                <option value="pot" <?php echo ($filters['category'] ?? '') === 'pot' ? 'selected' : ''; ?>>Pots</option>
                <option value="soil" <?php echo ($filters['category'] ?? '') === 'soil' ? 'selected' : ''; ?>>Terreau</option>
                <option value="fertilizer" <?php echo ($filters['category'] ?? '') === 'fertilizer' ? 'selected' : ''; ?>>Engrais</option>
                <option value="accessory" <?php echo ($filters['category'] ?? '') === 'accessory' ? 'selected' : ''; ?>>Accessoires</option>
            </select>
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </form>
    </div>
    
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image-wrapper">
                    <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'https://via.placeholder.com/300'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php if (isset($product['image_count']) && $product['image_count'] > 1): ?>
                        <span class="product-multi-image-badge" title="<?php echo $product['image_count']; ?> images">
                            <i class="fas fa-images"></i> <?php echo $product['image_count']; ?>
                        </span>
                    <?php endif; ?>
                </div>
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <p class="product-category"><?php echo htmlspecialchars($product['category']); ?></p>
                <p class="product-price"><?php echo number_format($product['price'], 2); ?> €</p>
                <p class="product-stock">Stock: <?php echo $product['stock']; ?></p>
                <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=detail&id=<?php echo $product['id']; ?>" class="btn btn-secondary">Voir détails</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

