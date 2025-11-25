<?php
$pageTitle = 'Tableau de bord';
?>

<div class="container">
    <h1>Tableau de bord</h1>
    
    <?php if ($weather): ?>
        <div class="weather-card">
            <h3>🌤️ Météo - <?php echo htmlspecialchars($this->getCurrentUser()['city'] ?? 'Votre ville'); ?></h3>
            <p>Température: <?php echo $weather['temperature']; ?>°C</p>
            <p>Humidité: <?php echo $weather['humidity']; ?>%</p>
            <?php if (!empty($weatherRecommendations)): ?>
                <div class="recommendations">
                    <h4>Recommandations:</h4>
                    <ul>
                        <?php foreach ($weatherRecommendations as $rec): ?>
                            <li><?php echo htmlspecialchars($rec); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="dashboard-grid">
        <div class="dashboard-card urgent">
            <h2>🌊 Plantes à arroser aujourd'hui</h2>
            <?php if (empty($plantsNeedingWater)): ?>
                <p>Aucune plante ne nécessite d'arrosage pour le moment.</p>
            <?php else: ?>
                <ul class="plant-list">
                    <?php foreach ($plantsNeedingWater as $plant): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>/?controller=userPlant&action=detail&id=<?php echo $plant['id']; ?>">
                                <?php echo htmlspecialchars($plant['nickname_for_plant'] ?? $plant['common_name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        
        <div class="dashboard-card">
            <h2>🌿 Plantes à fertiliser</h2>
            <?php if (empty($plantsNeedingFertilizer)): ?>
                <p>Aucune fertilisation nécessaire pour le moment.</p>
            <?php else: ?>
                <ul class="plant-list">
                    <?php foreach ($plantsNeedingFertilizer as $plant): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>/?controller=userPlant&action=detail&id=<?php echo $plant['id']; ?>">
                                <?php echo htmlspecialchars($plant['nickname_for_plant'] ?? $plant['common_name']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h2>📝 Activité récente</h2>
            <?php if (empty($recentCareEvents)): ?>
                <p>Aucune activité récente.</p>
            <?php else: ?>
                <ul class="activity-list">
                    <?php foreach ($recentCareEvents as $event): ?>
                        <li>
                            <?php echo htmlspecialchars($event['event_type']); ?> - 
                            <?php echo htmlspecialchars($event['nickname_for_plant'] ?? $event['common_name']); ?>
                            <span class="date"><?php echo date('d/m/Y', strtotime($event['event_date'])); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        
        <div class="dashboard-card">
            <h2>👥 Communauté</h2>
            <?php if (empty($recentPosts)): ?>
                <p>Aucun post récent.</p>
            <?php else: ?>
                <ul class="post-preview">
                    <?php foreach ($recentPosts as $post): ?>
                        <li>
                            <a href="<?php echo BASE_URL; ?>/?controller=social&action=detail&id=<?php echo $post['id']; ?>">
                                <?php echo htmlspecialchars(substr($post['content_text'], 0, 100)); ?>...
                            </a>
                            <span class="author">par <?php echo htmlspecialchars($post['username']); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>



