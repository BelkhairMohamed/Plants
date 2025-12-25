<?php
$pageTitle = 'Boutique';
?>

<div class="catalog-page">
    <div class="catalog-header">
        <h1>Boutique</h1>
        <div class="catalog-sort">
            <label for="sort-select">Trier par:</label>
            <select id="sort-select" class="sort-select" onchange="applySort(this.value)">
                <option value="featured" <?php echo ($filters['sort'] ?? 'featured') === 'featured' ? 'selected' : ''; ?>>En vedette</option>
                <option value="price_asc" <?php echo ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : ''; ?>>Prix: Croissant</option>
                <option value="price_desc" <?php echo ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : ''; ?>>Prix: Décroissant</option>
                <option value="name_asc" <?php echo ($filters['sort'] ?? '') === 'name_asc' ? 'selected' : ''; ?>>Nom: A-Z</option>
                <option value="name_desc" <?php echo ($filters['sort'] ?? '') === 'name_desc' ? 'selected' : ''; ?>>Nom: Z-A</option>
                <option value="created_desc" <?php echo ($filters['sort'] ?? '') === 'created_desc' ? 'selected' : ''; ?>>Plus récent</option>
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
                <input type="hidden" name="controller" value="marketplace">
                <input type="hidden" name="action" value="index">
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($filters['sort'] ?? 'featured'); ?>" id="sort-input">
                
                <!-- Search -->
                <div class="filter-section">
                    <div class="filter-search">
                        <input type="text" name="search" placeholder="Rechercher..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>" class="filter-search-input">
                    </div>
                </div>
                
                <!-- Product Type / Category -->
                <div class="filter-section">
                    <button type="button" class="filter-section-header" onclick="toggleFilterSection(this)">
                        <span>TYPE DE PRODUIT</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="filter-section-content">
                        <label class="filter-option">
                            <input type="radio" name="category" value="" <?php echo empty($filters['category']) ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Toutes les catégories</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="category" value="plant" <?php echo ($filters['category'] ?? '') === 'plant' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Plantes</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="category" value="seed" <?php echo ($filters['category'] ?? '') === 'seed' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Graines</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="category" value="pot" <?php echo ($filters['category'] ?? '') === 'pot' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Pots</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="category" value="soil" <?php echo ($filters['category'] ?? '') === 'soil' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Terreau</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="category" value="fertilizer" <?php echo ($filters['category'] ?? '') === 'fertilizer' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Engrais</span>
                        </label>
                        <label class="filter-option">
                            <input type="radio" name="category" value="accessory" <?php echo ($filters['category'] ?? '') === 'accessory' ? 'checked' : ''; ?> onchange="applyFilters()">
                            <span>Accessoires</span>
                        </label>
                    </div>
                </div>
            </form>
        </aside>
        
        <!-- Products Grid -->
        <main class="catalog-main">
            <div class="product-grid">
                <?php if (empty($products)): ?>
                    <div class="no-results">
                        <p>Aucun produit trouvé avec ces filtres.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image-wrapper">
                                <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'https://via.placeholder.com/300'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php if (isset($product['image_count']) && $product['image_count'] > 1): ?>
                                    <span class="product-multi-image-badge" title="<?php echo $product['image_count']; ?> images">
                                        <i class="fas fa-images"></i> <?php echo $product['image_count']; ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-category"><?php echo htmlspecialchars($product['category']); ?></p>
                            <p class="product-price"><?php echo number_format($product['price'], 2); ?> €</p>
                            <p class="product-stock">Stock: <?php echo $product['stock']; ?></p>
                            <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=detail&id=<?php echo $product['id']; ?>" class="btn btn-secondary">Voir détails</a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
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
    window.location.href = '<?php echo BASE_URL; ?>/?controller=marketplace&action=index';
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
        const checkedInput = content.querySelector('input:checked, input[checked]');
        if (checkedInput && checkedInput.value !== '') {
            content.classList.add('active');
            const icon = section.querySelector('.filter-section-header i');
            if (icon) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        }
    });
});
</script>
