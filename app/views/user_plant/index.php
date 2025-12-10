<?php
$pageTitle = 'Mes plantes';
?>

<div class="container">
    <h1>Mes plantes</h1>
    
    <?php if (empty($plants)): ?>
        <div class="empty-state">
            <p>Vous n'avez pas encore de plantes dans votre collection.</p>
            <a href="<?php echo BASE_URL; ?>/?controller=plantCatalog&action=index" class="btn btn-primary">Explorer le catalogue</a>
        </div>
    <?php else: ?>
        <div class="plant-grid">
            <?php foreach ($plants as $plant): ?>
                <div class="plant-card <?php echo $plant['needs_watering'] ? 'needs-care' : ''; ?>">
                    <img src="<?php echo htmlspecialchars($plant['image_url'] ?? 'https://via.placeholder.com/300'); ?>" alt="<?php echo htmlspecialchars($plant['common_name']); ?>">
                    <h3><?php echo htmlspecialchars($plant['nickname_for_plant'] ?? $plant['common_name']); ?></h3>
                    <p class="scientific-name"><?php echo htmlspecialchars($plant['scientific_name'] ?? ''); ?></p>
                    
                    <div class="care-info">
                        <?php if ($plant['needs_watering']): ?>
                            <div class="alert alert-urgent">🌊 Arrosage nécessaire!</div>
                        <?php else: ?>
                            <div class="care-date">
                                Prochain arrosage: <?php echo date('d/m/Y', strtotime($plant['next_watering_date'])); ?>
                                (<?php echo $plant['days_until_watering']; ?> jours)
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($plant['needs_fertilizing']): ?>
                            <div class="alert alert-warning">🌿 Fertilisation nécessaire!</div>
                        <?php else: ?>
                            <div class="care-date">
                                Prochaine fertilisation: <?php echo date('d/m/Y', strtotime($plant['next_fertilizing_date'])); ?>
                                (<?php echo $plant['days_until_fertilizing']; ?> jours)
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($plant['room_location']): ?>
                        <p class="location">📍 <?php echo htmlspecialchars($plant['room_location']); ?></p>
                    <?php endif; ?>
                    
                    <a href="<?php echo BASE_URL; ?>/?controller=userPlant&action=detail&id=<?php echo $plant['id']; ?>" class="btn btn-secondary">Voir détails</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>



