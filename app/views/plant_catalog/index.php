<?php
$pageTitle = 'Catalogue des plantes';
?>

<div class="container">
    <h1>Catalogue des plantes</h1>
    
    <div class="filters">
        <form method="GET" action="<?php echo BASE_URL; ?>/">
            <input type="hidden" name="controller" value="plantCatalog">
            <input type="hidden" name="action" value="index">
            <input type="text" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
            <select name="difficulty">
                <option value="">Tous les niveaux</option>
                <option value="beginner" <?php echo ($filters['difficulty_level'] ?? '') === 'beginner' ? 'selected' : ''; ?>>Débutant</option>
                <option value="intermediate" <?php echo ($filters['difficulty_level'] ?? '') === 'intermediate' ? 'selected' : ''; ?>>Intermédiaire</option>
                <option value="advanced" <?php echo ($filters['difficulty_level'] ?? '') === 'advanced' ? 'selected' : ''; ?>>Avancé</option>
            </select>
            <select name="light">
                <option value="">Tous les besoins en lumière</option>
                <option value="low" <?php echo ($filters['light_requirement'] ?? '') === 'low' ? 'selected' : ''; ?>>Faible</option>
                <option value="medium" <?php echo ($filters['light_requirement'] ?? '') === 'medium' ? 'selected' : ''; ?>>Moyen</option>
                <option value="high" <?php echo ($filters['light_requirement'] ?? '') === 'high' ? 'selected' : ''; ?>>Élevé</option>
            </select>
            <label>
                <input type="checkbox" name="beginners" value="1" <?php echo isset($filters['recommended_for_beginners']) ? 'checked' : ''; ?>>
                Recommandé pour débutants
            </label>
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </form>
    </div>
    
    <div class="plant-grid">
        <?php foreach ($plants as $plant): ?>
            <div class="plant-card">
                <div class="bloom-product-image-wrapper">
                    <img src="<?php echo htmlspecialchars($plant['image_url'] ?? 'https://via.placeholder.com/300'); ?>" alt="<?php echo htmlspecialchars($plant['common_name']); ?>" class="bloom-product-image">
                </div>
                <h3><?php echo htmlspecialchars($plant['common_name']); ?></h3>
                <p class="scientific-name"><?php echo htmlspecialchars($plant['scientific_name'] ?? ''); ?></p>
                <div class="plant-tags">
                    <span class="tag"><?php echo htmlspecialchars($plant['difficulty_level']); ?></span>
                    <span class="tag"><?php echo htmlspecialchars($plant['light_requirement']); ?> lumière</span>
                </div>
                <a href="<?php echo BASE_URL; ?>/?controller=plantCatalog&action=detail&id=<?php echo $plant['id']; ?>" class="btn btn-secondary">Voir détails</a>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php
                // Build URL with filters preserved
                $params = ['controller' => 'plantCatalog', 'action' => 'index', 'page' => $i];
                if (!empty($filters['search'])) $params['search'] = $filters['search'];
                if (!empty($filters['difficulty_level'])) $params['difficulty'] = $filters['difficulty_level'];
                if (!empty($filters['light_requirement'])) $params['light'] = $filters['light_requirement'];
                if (!empty($filters['water_requirement'])) $params['water'] = $filters['water_requirement'];
                if (isset($filters['recommended_for_beginners']) && $filters['recommended_for_beginners']) $params['beginners'] = '1';
                $url = BASE_URL . '/?' . http_build_query($params);
                ?>
                <a href="<?php echo $url; ?>" 
                   class="<?php echo $i == $currentPage ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

