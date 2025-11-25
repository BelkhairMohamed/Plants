<?php
$pageTitle = htmlspecialchars($plant['nickname_for_plant'] ?? $plant['common_name']);
?>

<div class="container">
    <div class="plant-detail-page">
        <div class="plant-header">
            <img src="<?php echo htmlspecialchars($plant['image_url'] ?? 'https://via.placeholder.com/500'); ?>" alt="<?php echo htmlspecialchars($plant['common_name']); ?>">
            <div>
                <h1><?php echo htmlspecialchars($plant['nickname_for_plant'] ?? $plant['common_name']); ?></h1>
                <p class="scientific-name"><?php echo htmlspecialchars($plant['scientific_name'] ?? ''); ?></p>
                <?php if ($plant['room_location']): ?>
                    <p>📍 <?php echo htmlspecialchars($plant['room_location']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="care-actions">
            <form method="POST" action="<?php echo BASE_URL; ?>/?controller=userPlant&action=markWatered" style="display: inline;">
                <input type="hidden" name="id" value="<?php echo $plant['id']; ?>">
                <button type="submit" class="btn btn-primary">🌊 Marquer comme arrosé aujourd'hui</button>
            </form>
            <form method="POST" action="<?php echo BASE_URL; ?>/?controller=userPlant&action=markFertilized" style="display: inline;">
                <input type="hidden" name="id" value="<?php echo $plant['id']; ?>">
                <button type="submit" class="btn btn-secondary">🌿 Marquer comme fertilisé aujourd'hui</button>
            </form>
        </div>
        
        <div class="care-schedule">
            <h2>Calendrier de soins</h2>
            <div class="schedule-item">
                <strong>Prochain arrosage:</strong> <?php echo date('d/m/Y', strtotime($plant['next_watering_date'])); ?>
            </div>
            <div class="schedule-item">
                <strong>Prochaine fertilisation:</strong> <?php echo date('d/m/Y', strtotime($plant['next_fertilizing_date'])); ?>
            </div>
        </div>
        
        <div class="plant-info-section">
            <h2>Informations</h2>
            <div class="info-grid">
                <div><strong>Difficulté:</strong> <?php echo htmlspecialchars($plant['difficulty_level']); ?></div>
                <div><strong>Lumière:</strong> <?php echo htmlspecialchars($plant['light_requirement']); ?></div>
                <div><strong>Eau:</strong> <?php echo htmlspecialchars($plant['water_requirement']); ?></div>
                <div><strong>Humidité:</strong> <?php echo htmlspecialchars($plant['humidity_preference']); ?></div>
            </div>
        </div>
        
        <?php if ($plant['acquisition_type'] === 'seed' && $plant['seed_guide']): ?>
            <div class="guide-section">
                <h2>Guide pour graines</h2>
                <p><?php echo nl2br(htmlspecialchars($plant['seed_guide'])); ?></p>
            </div>
        <?php elseif ($plant['mature_plant_guide']): ?>
            <div class="guide-section">
                <h2>Guide d'entretien</h2>
                <p><?php echo nl2br(htmlspecialchars($plant['mature_plant_guide'])); ?></p>
            </div>
        <?php endif; ?>
        
        <div class="care-history">
            <h2>Historique des soins</h2>
            <?php if (empty($events)): ?>
                <p>Aucun événement enregistré.</p>
            <?php else: ?>
                <ul class="event-list">
                    <?php foreach ($events as $event): ?>
                        <li>
                            <span class="event-type"><?php echo htmlspecialchars($event['event_type']); ?></span>
                            <span class="event-date"><?php echo date('d/m/Y', strtotime($event['event_date'])); ?></span>
                            <?php if ($event['notes']): ?>
                                <p class="event-notes"><?php echo htmlspecialchars($event['notes']); ?></p>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        
        <div class="edit-section">
            <h2>Modifier</h2>
            <form method="POST" action="<?php echo BASE_URL; ?>/?controller=userPlant&action=update">
                <input type="hidden" name="id" value="<?php echo $plant['id']; ?>">
                <div class="form-group">
                    <label>Surnom:</label>
                    <input type="text" name="nickname" value="<?php echo htmlspecialchars($plant['nickname_for_plant'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Emplacement:</label>
                    <input type="text" name="room_location" value="<?php echo htmlspecialchars($plant['room_location'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label>Intervalle d'arrosage personnalisé (jours):</label>
                    <input type="number" name="watering_interval" value="<?php echo $plant['custom_watering_interval_days'] ?? ''; ?>" min="1">
                </div>
                <div class="form-group">
                    <label>Intervalle de fertilisation personnalisé (jours):</label>
                    <input type="number" name="fertilizing_interval" value="<?php echo $plant['custom_fertilizing_interval_days'] ?? ''; ?>" min="1">
                </div>
                <div class="form-group">
                    <label>Notes:</label>
                    <textarea name="notes" rows="4"><?php echo htmlspecialchars($plant['notes'] ?? ''); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
            
            <form method="POST" action="<?php echo BASE_URL; ?>/?controller=userPlant&action=delete" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette plante de votre collection?');">
                <input type="hidden" name="id" value="<?php echo $plant['id']; ?>">
                <button type="submit" class="btn btn-danger">Supprimer de ma collection</button>
            </form>
        </div>
    </div>
</div>




