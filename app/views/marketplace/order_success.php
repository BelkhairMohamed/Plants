<?php
$pageTitle = 'Commande confirmée';
?>

<div class="container">
    <div class="success-message">
        <h1>✅ Commande confirmée!</h1>
        <p>Votre commande #<?php echo $order['id']; ?> a été enregistrée.</p>
        <p><strong>Total:</strong> <?php echo number_format($order['total_amount'], 2); ?> €</p>
        <p><strong>Statut:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
        
        <h3>Articles commandés:</h3>
        <ul>
            <?php foreach ($items as $item): ?>
                <li>
                    <?php echo htmlspecialchars($item['name']); ?> - 
                    Quantité: <?php echo $item['quantity']; ?> - 
                    Prix: <?php echo number_format($item['unit_price'], 2); ?> €
                </li>
            <?php endforeach; ?>
        </ul>
        
        <div class="success-actions">
            <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=orders" class="btn btn-primary">Voir mes commandes</a>
            <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index" class="btn btn-secondary">Continuer les achats</a>
        </div>
    </div>
</div>



