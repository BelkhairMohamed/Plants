<?php
$pageTitle = 'Administration - Plantes';
?>

<div class="container">
    <h1>Gérer les plantes</h1>
    
    <div class="admin-section">
        <h2>Ajouter une nouvelle plante</h2>
        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=admin&action=plants" class="admin-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Nom commun:</label>
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
                    <label>URL image:</label>
                    <input type="url" name="image_url">
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
                <textarea name="seed_guide" rows="4"></textarea>
            </div>
            
            <div class="form-group">
                <label>Guide pour plante mature:</label>
                <textarea name="mature_plant_guide" rows="4"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
    
    <div class="admin-section">
        <h2>Plantes existantes</h2>
        <div class="admin-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Difficulté</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plants as $plant): ?>
                        <tr>
                            <td><?php echo $plant['id']; ?></td>
                            <td><?php echo htmlspecialchars($plant['common_name']); ?></td>
                            <td><?php echo htmlspecialchars($plant['difficulty_level']); ?></td>
                            <td>
                                <form method="POST" action="<?php echo BASE_URL; ?>/?controller=admin&action=plants" style="display: inline;" onsubmit="return confirm('Supprimer cette plante?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $plant['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




