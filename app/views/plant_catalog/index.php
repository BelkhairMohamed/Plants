<?php
$pageTitle = 'Catalogue des plantes';
?>

<div class="catalog-page">
    <div class="catalog-header">
        <h1>Catalogue des plantes</h1>
        <div class="catalog-sort">
            <label for="sort-select">Trier par:</label>
            <select id="sort-select" class="sort-select" onchange="applySort(this.value)">
                <option value="featured" <?php echo ($filters['sort'] ?? 'featured') === 'featured' ? 'selected' : ''; ?>>En vedette</option>
                <option value="name_asc" <?php echo ($filters['sort'] ?? '') === 'name_asc' ? 'selected' : ''; ?>>Nom: A-Z</option>
                <option value="name_desc" <?php echo ($filters['sort'] ?? '') === 'name_desc' ? 'selected' : ''; ?>>Nom: Z-A</option>
                <option value="created_desc" <?php echo ($filters['sort'] ?? '') === 'created_desc' ? 'selected' : ''; ?>>Plus récent</option>
                <option value="created_asc" <?php echo ($filters['sort'] ?? '') === 'created_asc' ? 'selected' : ''; ?>>Plus ancien</option>
            </select>
        </div>
    </div>
    
    <div class="catalog-content">
        <!-- Filter Sidebar -->
        <aside class="filter-sidebar">
            <div class="filter-header">
                <h3>Filtres</h3>
                <button class="clear-filters-btn" onclick="clearFilters()">Effacer les filtres</button>
            </div>
            
            <form method="GET" action="<?php echo BASE_URL; ?>/" id="filter-form" class="filter-form">
                <input type="hidden" name="controller" value="plantCatalog">
                <input type="hidden" name="action" value="index">
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($filters['sort'] ?? 'featured'); ?>" id="sort-input">
                
                <!-- Search -->
                <div class="filter-section">
                    <div class="filter-search">
                        <input type="text" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>" class="filter-search-input">
                    </div>
                </div>
                
                <!-- Categories -->
                <?php if (!empty($allCategories)): ?>
                <div class="filter-section">
                    <button type="button" class="filter-section-header" onclick="toggleFilterSection(this)">
                        <span>CATÉGORIES</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="filter-section-content">
                        <?php 
                        $selectedCategoryIds = $selectedCategories ?? [];
                        if (isset($filters['category_slug'])) {
                            // If filtering by slug, find the category ID
                            foreach ($allCategories as $cat) {
                                if ($cat['slug'] === $filters['category_slug']) {
                                    $selectedCategoryIds = [$cat['id']];
                                    break;
                                }
                            }
                        }
                        ?>
                        <?php foreach ($allCategories as $category): ?>
                            <label class="filter-option">
                                <input type="checkbox" name="categories[]" value="<?php echo $category['id']; ?>" 
                                    <?php echo in_array($category['id'], $selectedCategoryIds) ? 'checked' : ''; ?> 
                                    onchange="applyFilters()">
                                <span><?php echo htmlspecialchars($category['name']); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Difficulty -->
                <div class="filter-section">
                    <button type="button" class="filter-section-header" onclick="toggleFilterSection(this)">
                        <span>DIFFICULTÉ</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="filter-section-content">
                        <label class="filter-option">
                            <input type="radio" name="difficulty" value="" <?php echo empty($filters['difficulty_level']) ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Tous les niveaux</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="difficulty" value="beginner" <?php echo ($filters['difficulty_level'] ?? '') === 'beginner' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Débutant</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="difficulty" value="intermediate" <?php echo ($filters['difficulty_level'] ?? '') === 'intermediate' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Intermédiaire</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="difficulty" value="advanced" <?php echo ($filters['difficulty_level'] ?? '') === 'advanced' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Avancé</span>
                        </label>
                    </div>
                </div>
                
                <!-- Light Requirement -->
                <div class="filter-section">
                    <button type="button" class="filter-section-header" onclick="toggleFilterSection(this)">
                        <span>LUMIÈRE INTÉRIEURE</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="filter-section-content">
                        <label class="filter-option">
                            <input type="radio" name="light" value="" <?php echo empty($filters['light_requirement']) ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Tous</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="light" value="low" <?php echo ($filters['light_requirement'] ?? '') === 'low' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Faible</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="light" value="medium" <?php echo ($filters['light_requirement'] ?? '') === 'medium' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Moyen</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="light" value="high" <?php echo ($filters['light_requirement'] ?? '') === 'high' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Élevé</span>
                        </label>
                    </div>
                </div>
                
                <!-- Water Requirement -->
                <div class="filter-section">
                    <button type="button" class="filter-section-header" onclick="toggleFilterSection(this)">
                        <span>BESOIN EN EAU</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="filter-section-content">
                        <label class="filter-option">
                            <input type="radio" name="water" value="" <?php echo empty($filters['water_requirement']) ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Tous</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="water" value="low" <?php echo ($filters['water_requirement'] ?? '') === 'low' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Faible</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="water" value="medium" <?php echo ($filters['water_requirement'] ?? '') === 'medium' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Moyen</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="water" value="high" <?php echo ($filters['water_requirement'] ?? '') === 'high' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Élevé</span>
                        </label>
                    </div>
                </div>
                
                <!-- Humidity -->
                <div class="filter-section">
                    <button type="button" class="filter-section-header" onclick="toggleFilterSection(this)">
                        <span>HUMIDITÉ</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="filter-section-content">
                        <label class="filter-option">
                            <input type="radio" name="humidity" value="" <?php echo empty($filters['humidity_preference']) ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Tous</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="humidity" value="low" <?php echo ($filters['humidity_preference'] ?? '') === 'low' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Faible</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="humidity" value="medium" <?php echo ($filters['humidity_preference'] ?? '') === 'medium' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Moyen</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="humidity" value="high" <?php echo ($filters['humidity_preference'] ?? '') === 'high' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Élevé</span>
                        </label>
                    </div>
                </div>
                
                <!-- Recommended for Beginners -->
                <div class="filter-section">
                    <button type="button" class="filter-section-header" onclick="toggleFilterSection(this)">
                        <span>RECOMMANDÉ POUR DÉBUTANTS</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="filter-section-content">
                        <label class="filter-option">
                            <input type="checkbox" name="beginners" value="1" <?php echo isset($filters['recommended_for_beginners']) ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Recommandé pour débutants</span>
                        </label>
                    </div>
                </div>
            </form>
        </aside>
        
        <!-- Products Grid -->
        <main class="catalog-main">
            <div class="plant-grid">
                <?php if (empty($plants)): ?>
                    <div class="no-results">
                        <p>Aucune plante trouvée avec ces filtres.</p>
                    </div>
                <?php else: ?>
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
                <?php endif; ?>
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
                        if (!empty($filters['humidity_preference'])) $params['humidity'] = $filters['humidity_preference'];
                        if (isset($filters['recommended_for_beginners']) && $filters['recommended_for_beginners']) $params['beginners'] = '1';
                        if (!empty($filters['sort'])) $params['sort'] = $filters['sort'];
                        // Preserve category filters
                        if (!empty($selectedCategories)) {
                            foreach ($selectedCategories as $catId) {
                                $params['categories[]'] = $catId;
                            }
                        } elseif (!empty($filters['category_slug'])) {
                            $params['category'] = $filters['category_slug'];
                        }
                        $url = BASE_URL . '/?' . http_build_query($params);
                        ?>
                        <a href="<?php echo $url; ?>" 
                           class="<?php echo $i == $currentPage ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
function applyFilters() {
    document.getElementById('filter-form').submit();
}

function applySort(sortValue) {
    document.getElementById('sort-input').value = sortValue;
    document.getElementById('filter-form').submit();
}

function clearFilters() {
    window.location.href = '<?php echo BASE_URL; ?>/?controller=plantCatalog&action=index';
}

function toggleFilterSection(button) {
    const section = button.closest('.filter-section');
    const content = section.querySelector('.filter-section-content');
    const icon = button.querySelector('i');
    
    content.classList.toggle('active');
    icon.classList.toggle('fa-chevron-down');
    icon.classList.toggle('fa-chevron-up');
}

// Initialize filter sections - open if they have selected values
document.addEventListener('DOMContentLoaded', function() {
    const filterSections = document.querySelectorAll('.filter-section');
    filterSections.forEach(section => {
        const content = section.querySelector('.filter-section-content');
        if (!content) return;
        
        // Check for checked radio buttons or checkboxes
        const checkedInput = content.querySelector('input[type="radio"]:checked, input[type="checkbox"]:checked');
        if (checkedInput) {
            // For radio buttons, check if value is not empty
            if (checkedInput.type === 'radio' && checkedInput.value !== '') {
                content.classList.add('active');
                const icon = section.querySelector('.filter-section-header i');
                if (icon) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            }
            // For checkboxes, always open if checked
            if (checkedInput.type === 'checkbox' && checkedInput.checked) {
                content.classList.add('active');
                const icon = section.querySelector('.filter-section-header i');
                if (icon) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            }
        }
    });
});
</script>
