<?php
$pageTitle = htmlspecialchars($product['name']);
?>

<div class="container">
    <div class="product-detail">
        <div class="product-image">
            <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'https://via.placeholder.com/500'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="product-category">Catégorie: <?php echo htmlspecialchars($product['category']); ?></p>
            <p class="product-price-large"><?php echo number_format($product['price'], 2); ?> €</p>
            <p class="product-stock">Stock disponible: <?php echo $product['stock']; ?></p>
            
            <?php if ($product['description']): ?>
                <div class="product-description">
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ($product['stock'] > 0): ?>
                <form method="POST" action="<?php echo BASE_URL; ?>/?controller=marketplace&action=addToCart">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div class="form-group">
                        <label>Quantité:</label>
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter au panier</button>
                </form>
            <?php else: ?>
                <div class="alert alert-error">Produit en rupture de stock</div>
            <?php endif; ?>
        </div>
    </div>
</div>




