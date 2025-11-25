<?php
$pageTitle = 'Panier';
?>

<div class="container">
    <h1>Panier</h1>
    
    <?php if (empty($cart)): ?>
        <div class="empty-state">
            <p>Votre panier est vide.</p>
            <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index" class="btn btn-primary">Continuer les achats</a>
        </div>
    <?php else: ?>
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
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars($item['image_url'] ?? 'https://via.placeholder.com/50'); ?>" alt="" class="cart-image">
                            <?php echo htmlspecialchars($item['name']); ?>
                        </td>
                        <td><?php echo number_format($item['price'], 2); ?> €</td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?> €</td>
                        <td>
                            <form method="POST" action="<?php echo BASE_URL; ?>/?controller=marketplace&action=removeFromCart" style="display: inline;">
                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong><?php echo number_format($total, 2); ?> €</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        
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



