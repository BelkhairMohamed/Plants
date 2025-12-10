<?php
$pageTitle = 'Mes commandes';
?>

<div class="container">
    <h1>Mes commandes</h1>
    
    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <p>Vous n'avez pas encore de commandes.</p>
            <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index" class="btn btn-primary">Explorer la boutique</a>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <h3>Commande #<?php echo $order['id']; ?></h3>
                    <p><strong>Date:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                    <p><strong>Total:</strong> <?php echo number_format($order['total_amount'], 2); ?> €</p>
                    <p><strong>Statut:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>



