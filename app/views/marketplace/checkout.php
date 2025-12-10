<?php
$pageTitle = 'Commande';
?>

<div class="container">
    <h1>Passer la commande</h1>
    
    <div class="checkout-form">
        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=marketplace&action=checkout">
            <div class="form-group">
                <label for="shipping_address">Adresse de livraison:</label>
                <textarea id="shipping_address" name="shipping_address" rows="4" required></textarea>
            </div>
            
            <div class="order-summary">
                <h3>Résumé de la commande</h3>
                <ul>
                    <?php foreach ($cart as $item): ?>
                        <li>
                            <?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?> = 
                            <?php echo number_format($item['price'] * $item['quantity'], 2); ?> €
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p><strong>Total: <?php 
                    $total = 0;
                    foreach ($cart as $item) {
                        $total += $item['price'] * $item['quantity'];
                    }
                    echo number_format($total, 2);
                ?> €</strong></p>
            </div>
            
            <button type="submit" class="btn btn-primary">Confirmer la commande</button>
        </form>
    </div>
</div>



