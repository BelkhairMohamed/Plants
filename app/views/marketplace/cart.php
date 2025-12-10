<?php
$pageTitle = 'Panier';
?>

<div class="container">
    <h1>Panier</h1>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>
    
    <?php if (empty($cart)): ?>
        <div class="empty-state">
            <p>Votre panier est vide.</p>
            <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index" class="btn btn-primary">Continuer les achats</a>
        </div>
    <?php else: ?>
        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=marketplace&action=updateCart" id="cart-form">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $index => $item): ?>
                        <?php 
                        $productId = $item['product_id'] ?? 0;
                        $maxStock = isset($products[$productId]) ? $products[$productId]['stock'] : 999;
                        ?>
                        <tr>
                            <td>
                                <img src="<?php echo htmlspecialchars($item['image_url'] ?? 'https://via.placeholder.com/50'); ?>" alt="" class="cart-image">
                                <?php echo htmlspecialchars($item['name']); ?>
                                <?php if ($maxStock < 10): ?>
                                    <small style="display: block; color: #666; margin-top: 4px;">
                                        Stock: <?php echo $maxStock; ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo number_format($item['price'], 2); ?> €</td>
                            <td>
                                <input 
                                    type="number" 
                                    name="quantities[<?php echo $productId; ?>]" 
                                    value="<?php echo htmlspecialchars($item['quantity']); ?>" 
                                    min="0" 
                                    max="<?php echo $maxStock; ?>"
                                    class="cart-quantity-input"
                                    onchange="updateCartItem(<?php echo $productId; ?>, this.value)"
                                >
                            </td>
                            <td class="item-total"><?php echo number_format($item['price'] * $item['quantity'], 2); ?> €</td>
                            <td>
                                <button 
                                    type="button" 
                                    class="btn btn-danger btn-sm" 
                                    onclick="removeCartItem(<?php echo $index; ?>)"
                                >
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td><strong id="cart-total"><?php echo number_format($total, 2); ?> €</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </form>
        
        <div class="cart-actions">
            <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index" class="btn btn-secondary">Continuer les achats</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=checkout" class="btn btn-primary">Passer la commande</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/?controller=auth&action=login" class="btn btn-primary">Connectez-vous pour commander</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function updateCartItem(productId, quantity) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo BASE_URL; ?>/?controller=marketplace&action=updateCart';
    
    const productInput = document.createElement('input');
    productInput.type = 'hidden';
    productInput.name = 'product_id';
    productInput.value = productId;
    form.appendChild(productInput);
    
    const quantityInput = document.createElement('input');
    quantityInput.type = 'hidden';
    quantityInput.name = 'quantity';
    quantityInput.value = quantity;
    form.appendChild(quantityInput);
    
    document.body.appendChild(form);
    form.submit();
}

function removeCartItem(index) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet article du panier ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo BASE_URL; ?>/?controller=marketplace&action=removeFromCart';
        
        const indexInput = document.createElement('input');
        indexInput.type = 'hidden';
        indexInput.name = 'index';
        indexInput.value = index;
        form.appendChild(indexInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>



