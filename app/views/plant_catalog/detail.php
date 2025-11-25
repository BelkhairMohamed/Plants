<?php
$pageTitle = htmlspecialchars($plant['common_name']);
?>

<div class="container">
    <div class="plant-detail">
        <div class="plant-image">
            <img src="<?php echo htmlspecialchars($plant['image_url'] ?? 'https://via.placeholder.com/500'); ?>" alt="<?php echo htmlspecialchars($plant['common_name']); ?>">
        </div>
        <div class="plant-info">
            <h1><?php echo htmlspecialchars($plant['common_name']); ?></h1>
            <p class="scientific-name"><?php echo htmlspecialchars($plant['scientific_name'] ?? ''); ?></p>
            
            <div class="plant-details">
                <div class="detail-item">
                    <strong>Difficulté:</strong> <?php echo htmlspecialchars($plant['difficulty_level']); ?>
                </div>
                <div class="detail-item">
                    <strong>Lumière:</strong> <?php echo htmlspecialchars($plant['light_requirement']); ?>
                </div>
                <div class="detail-item">
                    <strong>Eau:</strong> <?php echo htmlspecialchars($plant['water_requirement']); ?>
                </div>
                <div class="detail-item">
                    <strong>Humidité:</strong> <?php echo htmlspecialchars($plant['humidity_preference']); ?>
                </div>
                <?php if ($plant['temperature_min'] && $plant['temperature_max']): ?>
                    <div class="detail-item">
                        <strong>Température:</strong> <?php echo $plant['temperature_min']; ?>°C - <?php echo $plant['temperature_max']; ?>°C
                    </div>
                <?php endif; ?>
                <div class="detail-item">
                    <strong>Arrosage:</strong> Tous les <?php echo $plant['default_watering_interval_days']; ?> jours
                </div>
                <div class="detail-item">
                    <strong>Fertilisation:</strong> Tous les <?php echo $plant['default_fertilizing_interval_days']; ?> jours
                </div>
            </div>
            
            <div class="description">
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($plant['description'] ?? '')); ?></p>
            </div>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($userOwnsPlant): ?>
                    <div class="alert alert-info">
                        ✅ Cette plante est déjà dans votre collection.
                        <a href="<?php echo BASE_URL; ?>/?controller=userPlant&action=index">Voir mes plantes</a>
                    </div>
                <?php else: ?>
                    <div class="add-to-collection">
                        <h3>Ajouter à ma collection</h3>
                        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=plantCatalog&action=addToCollection">
                            <input type="hidden" name="plant_catalog_id" value="<?php echo $plant['id']; ?>">
                            <div class="form-group">
                                <label>Type d'acquisition:</label>
                                <select name="acquisition_type" required>
                                    <option value="plant">Plante mature</option>
                                    <option value="seed">Graine</option>
                                    <option value="unknown">Inconnu</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Surnom (optionnel):</label>
                                <input type="text" name="nickname" placeholder="Ex: Mon Monstera du salon">
                            </div>
                            <div class="form-group">
                                <label>Emplacement (optionnel):</label>
                                <input type="text" name="room_location" placeholder="Ex: Salon, Chambre...">
                            </div>
                            <button type="submit" class="btn btn-primary">Ajouter à ma collection</button>
                        </form>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-info">
                    <a href="<?php echo BASE_URL; ?>/?controller=auth&action=login">Connectez-vous</a> pour ajouter cette plante à votre collection.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>




