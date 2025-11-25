<?php
$pageTitle = 'Créer un post';
?>

<div class="container">
    <h1>Créer un post</h1>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="<?php echo BASE_URL; ?>/?controller=social&action=create" class="post-form">
        <div class="form-group">
            <label for="content">Contenu du post:</label>
            <textarea id="content" name="content" rows="6" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="post_type">Type de post:</label>
            <select id="post_type" name="post_type">
                <option value="normal">Normal</option>
                <option value="help">Demande d'aide</option>
                <option value="article">Article</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="related_plant_id">Lier à une de mes plantes (optionnel):</label>
            <select id="related_plant_id" name="related_plant_id">
                <option value="">Aucune</option>
                <?php if (!empty($userPlants)): ?>
                    <?php foreach ($userPlants as $plant): ?>
                        <option value="<?php echo $plant['id']; ?>">
                            <?php 
                            // Build display name with fallbacks
                            $parts = [];
                            
                            if (!empty($plant['nickname_for_plant'])) {
                                $parts[] = $plant['nickname_for_plant'];
                            }
                            
                            if (!empty($plant['common_name'])) {
                                if (empty($plant['nickname_for_plant'])) {
                                    $parts[] = $plant['common_name'];
                                } else {
                                    $parts[] = '(' . $plant['common_name'] . ')';
                                }
                            }
                            
                            // If still empty, use ID
                            if (empty($parts)) {
                                $parts[] = 'Plante #' . $plant['id'];
                            }
                            
                            echo htmlspecialchars(implode(' ', $parts));
                            ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>Vous n'avez pas encore de plantes dans votre collection</option>
                <?php endif; ?>
            </select>
            <?php if (empty($userPlants)): ?>
                <small style="color: #666; display: block; margin-top: 5px;">
                    <a href="<?php echo BASE_URL; ?>/?controller=plantCatalog&action=index">Ajoutez des plantes à votre collection</a> pour pouvoir les lier à vos posts.
                </small>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="image_url">URL de l'image (optionnel):</label>
            <input type="url" id="image_url" name="image_url" placeholder="https://...">
        </div>
        
        <button type="submit" class="btn btn-primary">Publier</button>
        <a href="<?php echo BASE_URL; ?>/?controller=social&action=index" class="btn btn-secondary">Annuler</a>
    </form>
</div>

