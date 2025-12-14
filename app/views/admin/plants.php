<?php
$pageTitle = 'Administration - Plantes';
?>

<div class="container">
    <h1>Gérer les plantes</h1>
    
    <?php if (isset($message)): ?>
        <div class="alert alert-<?php echo $messageType === 'error' ? 'error' : 'success'; ?>" style="margin-bottom: 2rem; padding: 1.5rem;">
            <strong><?php echo $messageType === 'error' ? '❌ Erreur:' : '✅ Succès:'; ?></strong>
            <?php echo htmlspecialchars($message); ?>
            <?php if ($messageType === 'error' && strpos($message, 'plant_catalog_images') !== false): ?>
                <div style="margin-top: 1rem; padding: 1rem; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
                    <strong>📋 Solution:</strong>
                    <ol style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                        <li>Ouvrez <strong>phpMyAdmin</strong>: <code>http://localhost/phpmyadmin</code></li>
                        <li>Sélectionnez la base <strong>plants_management</strong></li>
                        <li>Cliquez sur l'onglet <strong>"SQL"</strong></li>
                        <li>Ouvrez le fichier: <code>database/migration_add_plant_images_table.sql</code></li>
                        <li>Copiez TOUT le contenu et collez-le dans phpMyAdmin</li>
                        <li>Cliquez sur <strong>"Exécuter"</strong></li>
                    </ol>
                    <p style="margin-top: 0.75rem; margin-bottom: 0;">
                        <strong>Fichier à exécuter:</strong> <code>database/migration_add_plant_images_table.sql</code>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <div class="admin-section">
        <h2>Ajouter une nouvelle plante</h2>
        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=admin&action=plants" class="admin-form" id="add-plant-form">
            <input type="hidden" name="action" value="create">
            <div class="form-row">
                <div class="form-group">
                    <label>Nom commun: <span class="required">*</span></label>
                    <input type="text" name="common_name" required>
                </div>
                <div class="form-group">
                    <label>Nom scientifique:</label>
                    <input type="text" name="scientific_name">
                </div>
            </div>
            
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" rows="4"></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Difficulté:</label>
                    <select name="difficulty_level">
                        <option value="beginner">Débutant</option>
                        <option value="intermediate">Intermédiaire</option>
                        <option value="advanced">Avancé</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Lumière:</label>
                    <select name="light_requirement">
                        <option value="low">Faible</option>
                        <option value="medium">Moyen</option>
                        <option value="high">Élevé</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Eau:</label>
                    <select name="water_requirement">
                        <option value="low">Faible</option>
                        <option value="medium">Moyen</option>
                        <option value="high">Élevé</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Humidité:</label>
                    <select name="humidity_preference">
                        <option value="low">Faible</option>
                        <option value="medium">Moyen</option>
                        <option value="high">Élevé</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Température min (°C):</label>
                    <input type="number" name="temperature_min">
                </div>
                <div class="form-group">
                    <label>Température max (°C):</label>
                    <input type="number" name="temperature_max">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>URL image principale:</label>
                    <input type="url" name="image_url" placeholder="https://example.com/image.jpg">
                </div>
                <div class="form-group">
                    <label>Arrosage (jours):</label>
                    <input type="number" name="default_watering_interval_days" value="7" min="1">
                </div>
                <div class="form-group">
                    <label>Fertilisation (jours):</label>
                    <input type="number" name="default_fertilizing_interval_days" value="30" min="1">
                </div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="recommended_for_beginners">
                    Recommandé pour débutants
                </label>
            </div>
            
            <div class="form-group">
                <label>Guide pour graines:</label>
                <textarea name="seed_guide" rows="4" placeholder="Instructions pour planter et faire germer les graines..."></textarea>
            </div>
            
            <div class="form-group">
                <label>Guide pour plante mature:</label>
                <textarea name="mature_plant_guide" rows="4" placeholder="Instructions pour entretenir une plante mature..."></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Ajouter la plante</button>
        </form>
    </div>
    
    <div class="admin-section">
        <h2>Plantes existantes</h2>
        <div class="plants-list">
            <?php foreach ($plants as $plant): ?>
                <div class="plant-admin-card">
                    <div class="plant-admin-header">
                        <div class="plant-admin-info">
                            <h3><?php echo htmlspecialchars($plant['common_name']); ?></h3>
                            <p class="plant-admin-id">ID: <?php echo $plant['id']; ?></p>
                            <?php if ($plant['scientific_name']): ?>
                                <p class="plant-admin-scientific"><?php echo htmlspecialchars($plant['scientific_name']); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="plant-admin-actions">
                            <button class="btn btn-secondary btn-edit-plant" data-plant-id="<?php echo $plant['id']; ?>">Modifier</button>
                            <form method="POST" action="<?php echo BASE_URL; ?>/?controller=admin&action=plants" style="display: inline;" onsubmit="return confirm('Supprimer cette plante?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $plant['id']; ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Edit Form (Hidden by default) -->
                    <div class="plant-edit-form" id="edit-form-<?php echo $plant['id']; ?>" style="display: none;">
                        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=admin&action=plants" class="admin-form">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?php echo $plant['id']; ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Nom commun: <span class="required">*</span></label>
                                    <input type="text" name="common_name" value="<?php echo htmlspecialchars($plant['common_name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Nom scientifique:</label>
                                    <input type="text" name="scientific_name" value="<?php echo htmlspecialchars($plant['scientific_name'] ?? ''); ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Description:</label>
                                <textarea name="description" rows="4"><?php echo htmlspecialchars($plant['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Difficulté:</label>
                                    <select name="difficulty_level">
                                        <option value="beginner" <?php echo $plant['difficulty_level'] === 'beginner' ? 'selected' : ''; ?>>Débutant</option>
                                        <option value="intermediate" <?php echo $plant['difficulty_level'] === 'intermediate' ? 'selected' : ''; ?>>Intermédiaire</option>
                                        <option value="advanced" <?php echo $plant['difficulty_level'] === 'advanced' ? 'selected' : ''; ?>>Avancé</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Lumière:</label>
                                    <select name="light_requirement">
                                        <option value="low" <?php echo $plant['light_requirement'] === 'low' ? 'selected' : ''; ?>>Faible</option>
                                        <option value="medium" <?php echo $plant['light_requirement'] === 'medium' ? 'selected' : ''; ?>>Moyen</option>
                                        <option value="high" <?php echo $plant['light_requirement'] === 'high' ? 'selected' : ''; ?>>Élevé</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Eau:</label>
                                    <select name="water_requirement">
                                        <option value="low" <?php echo $plant['water_requirement'] === 'low' ? 'selected' : ''; ?>>Faible</option>
                                        <option value="medium" <?php echo $plant['water_requirement'] === 'medium' ? 'selected' : ''; ?>>Moyen</option>
                                        <option value="high" <?php echo $plant['water_requirement'] === 'high' ? 'selected' : ''; ?>>Élevé</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Humidité:</label>
                                    <select name="humidity_preference">
                                        <option value="low" <?php echo $plant['humidity_preference'] === 'low' ? 'selected' : ''; ?>>Faible</option>
                                        <option value="medium" <?php echo $plant['humidity_preference'] === 'medium' ? 'selected' : ''; ?>>Moyen</option>
                                        <option value="high" <?php echo $plant['humidity_preference'] === 'high' ? 'selected' : ''; ?>>Élevé</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Température min (°C):</label>
                                    <input type="number" name="temperature_min" value="<?php echo $plant['temperature_min'] ?? ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label>Température max (°C):</label>
                                    <input type="number" name="temperature_max" value="<?php echo $plant['temperature_max'] ?? ''; ?>">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>URL image principale:</label>
                                    <input type="url" name="image_url" value="<?php echo htmlspecialchars($plant['image_url'] ?? ''); ?>" placeholder="https://example.com/image.jpg">
                                </div>
                                <div class="form-group">
                                    <label>Arrosage (jours):</label>
                                    <input type="number" name="default_watering_interval_days" value="<?php echo $plant['default_watering_interval_days'] ?? 7; ?>" min="1">
                                </div>
                                <div class="form-group">
                                    <label>Fertilisation (jours):</label>
                                    <input type="number" name="default_fertilizing_interval_days" value="<?php echo $plant['default_fertilizing_interval_days'] ?? 30; ?>" min="1">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="recommended_for_beginners" <?php echo $plant['recommended_for_beginners'] ? 'checked' : ''; ?>>
                                    Recommandé pour débutants
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label>Guide pour graines:</label>
                                <textarea name="seed_guide" rows="4"><?php echo htmlspecialchars($plant['seed_guide'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Guide pour plante mature:</label>
                                <textarea name="mature_plant_guide" rows="4"><?php echo htmlspecialchars($plant['mature_plant_guide'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                                <button type="button" class="btn btn-secondary btn-cancel-edit" data-plant-id="<?php echo $plant['id']; ?>">Annuler</button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Images Management -->
                    <div class="plant-images-section">
                        <h4>Images de la plante</h4>
                        <div class="plant-images-list">
                            <?php if (!empty($plant['images'])): ?>
                                <?php foreach ($plant['images'] as $image): ?>
                                    <div class="plant-image-item">
                                        <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="Image" class="plant-image-thumb">
                                        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=admin&action=plants" style="display: inline;" onsubmit="return confirm('Supprimer cette image?');">
                                            <input type="hidden" name="action" value="delete_image">
                                            <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">×</button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="no-images">Aucune image ajoutée</p>
                            <?php endif; ?>
                        </div>
                        
                        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=admin&action=plants" class="add-image-form">
                            <input type="hidden" name="action" value="add_image">
                            <input type="hidden" name="plant_id" value="<?php echo $plant['id']; ?>">
                            <div class="form-row">
                                <div class="form-group" style="flex: 1;">
                                    <input type="url" name="image_url" placeholder="URL de l'image (https://example.com/image.jpg)" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Ajouter une image</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle edit form
    document.querySelectorAll('.btn-edit-plant').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const plantId = this.getAttribute('data-plant-id');
            const editForm = document.getElementById('edit-form-' + plantId);
            if (editForm) {
                editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
                this.textContent = editForm.style.display === 'none' ? 'Modifier' : 'Masquer';
            }
        });
    });
    
    // Cancel edit
    document.querySelectorAll('.btn-cancel-edit').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const plantId = this.getAttribute('data-plant-id');
            const editForm = document.getElementById('edit-form-' + plantId);
            const editBtn = document.querySelector('.btn-edit-plant[data-plant-id="' + plantId + '"]');
            if (editForm) {
                editForm.style.display = 'none';
            }
            if (editBtn) {
                editBtn.textContent = 'Modifier';
            }
        });
    });
});
</script>
