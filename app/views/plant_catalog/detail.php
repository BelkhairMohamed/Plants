<?php
$pageTitle = htmlspecialchars($plant['common_name']);
?>

<div class="container">
    <div class="plant-detail bloom-plant-detail">
        <div class="plant-image-gallery">
            <!-- Thumbnail Images (Left Side) -->
            <div class="plant-thumbnails">
                <?php
                // Get all images for this plant
                $allImages = [];
                if (!empty($plantImages)) {
                    foreach ($plantImages as $img) {
                        $allImages[] = $img['image_url'];
                    }
                }
                
                // Fallback to main image if no images found
                if (empty($allImages)) {
                    $mainImage = $plant['image_url'] ?? 'https://via.placeholder.com/500';
                    $allImages = [$mainImage];
                } else {
                    $mainImage = $allImages[0];
                }
                ?>
                <?php foreach ($allImages as $index => $imageUrl): ?>
                    <div class="thumbnail-item <?php echo $index === 0 ? 'active' : ''; ?>" data-image="<?php echo htmlspecialchars($imageUrl); ?>">
                        <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($plant['common_name']); ?> - Vue <?php echo $index + 1; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Main Product Image (Center) -->
            <div class="plant-main-image-wrapper">
                <img id="main-plant-image" src="<?php echo htmlspecialchars($mainImage); ?>" alt="<?php echo htmlspecialchars($plant['common_name']); ?>" class="plant-main-image">
            </div>
        </div>
        <div class="plant-info">
            <h1><?php echo htmlspecialchars($plant['common_name']); ?></h1>
            <p class="scientific-name"><?php echo htmlspecialchars($plant['scientific_name'] ?? ''); ?></p>
            
            <!-- Description - Always visible -->
            <?php if (!empty($plant['description'])): ?>
            <div class="description">
                <p><?php echo nl2br(htmlspecialchars($plant['description'])); ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Collapsible Sections -->
            <div class="plant-detail-sections">
                <!-- Categories Section - Always shown -->
                <div class="detail-section">
                    <button class="detail-section-header" onclick="toggleDetailSection(this)">
                        <span><i class="fas fa-tags"></i> Catégories</span>
                        <i class="fas fa-chevron-down section-icon"></i>
                    </button>
                    <div class="detail-section-content">
                        <div class="plant-categories-list">
                            <?php 
                            $isPetFriendly = false;
                            if (!empty($plantCategories)) {
                                foreach ($plantCategories as $category) {
                                    if ($category['slug'] === 'pet_friendly') {
                                        $isPetFriendly = true;
                                        break;
                                    }
                                }
                            }
                            ?>
                            
                            <!-- Pet-Friendly Status - Always shown -->
                            <div class="category-item <?php echo $isPetFriendly ? 'category-pet-friendly' : 'category-not-pet-friendly'; ?>">
                                <div class="category-header">
                                    <i class="fas fa-paw"></i>
                                    <strong>Pet-Friendly:</strong>
                                    <span class="category-status">
                                        <?php if ($isPetFriendly): ?>
                                            <span class="status-yes">No toxic</span>
                                        <?php else: ?>
                                            <span class="status-no">Not PF</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Other Categories -->
                            <?php if (!empty($plantCategories)): ?>
                                <?php foreach ($plantCategories as $category): ?>
                                    <?php if ($category['slug'] !== 'pet_friendly'): ?>
                                        <div class="category-item">
                                            <div class="category-header">
                                                <i class="fas fa-check-circle category-icon"></i>
                                                <strong><?php echo htmlspecialchars($category['name']); ?>:</strong>
                                            </div>
                                            <?php if (!empty($category['description'])): ?>
                                                <div class="category-description">
                                                    <?php 
                                                    // Format description - if it contains "Yes—" or similar, format it nicely
                                                    $desc = htmlspecialchars($category['description']);
                                                    if (stripos($desc, 'yes') === 0 || stripos($desc, 'oui') === 0) {
                                                        echo '<span class="status-yes">' . $desc . '</span>';
                                                    } else {
                                                        echo $desc;
                                                    }
                                                    ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="category-description">
                                                    <span class="status-yes">Yes</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Details Section -->
                <div class="detail-section">
                    <button class="detail-section-header" onclick="toggleDetailSection(this)">
                        <span><i class="fas fa-info-circle"></i> Détails</span>
                        <i class="fas fa-chevron-down section-icon"></i>
                    </button>
                    <div class="detail-section-content">
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
                        </div>
                    </div>
                </div>
                
                <!-- Care Section -->
                <div class="detail-section">
                    <button class="detail-section-header" onclick="toggleDetailSection(this)">
                        <span><i class="fas fa-heart"></i> Soins</span>
                        <i class="fas fa-chevron-down section-icon"></i>
                    </button>
                    <div class="detail-section-content">
                        <div class="plant-details">
                            <div class="detail-item">
                                <strong>Arrosage:</strong> Tous les <?php echo $plant['default_watering_interval_days']; ?> jours
                            </div>
                            <div class="detail-item">
                                <strong>Fertilisation:</strong> Tous les <?php echo $plant['default_fertilizing_interval_days']; ?> jours
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Seed Guide Section -->
                <?php if (!empty($plant['seed_guide'])): ?>
                <div class="detail-section">
                    <button class="detail-section-header" onclick="toggleDetailSection(this)">
                        <span><i class="fas fa-seedling"></i> Guide pour graines</span>
                        <i class="fas fa-chevron-down section-icon"></i>
                    </button>
                    <div class="detail-section-content">
                        <div class="guide-content">
                            <p><?php echo nl2br(htmlspecialchars($plant['seed_guide'])); ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Mature Plant Guide Section -->
                <?php if (!empty($plant['mature_plant_guide'])): ?>
                <div class="detail-section">
                    <button class="detail-section-header" onclick="toggleDetailSection(this)">
                        <span><i class="fas fa-leaf"></i> Guide d'entretien</span>
                        <i class="fas fa-chevron-down section-icon"></i>
                    </button>
                    <div class="detail-section-content">
                        <div class="guide-content">
                            <p><?php echo nl2br(htmlspecialchars($plant['mature_plant_guide'])); ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Thumbnail image switching
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    const mainImage = document.getElementById('main-plant-image');
    
    thumbnails.forEach(function(thumbnail) {
        thumbnail.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(function(item) {
                item.classList.remove('active');
            });
            
            // Add active class to clicked thumbnail
            this.classList.add('active');
            
            // Get the image URL from data attribute
            const newImageUrl = this.getAttribute('data-image');
            
            // Fade out current image
            if (mainImage) {
                mainImage.style.opacity = '0';
                mainImage.style.transition = 'opacity 0.3s ease';
                
                // After fade out, change image and fade in
                setTimeout(function() {
                    mainImage.src = newImageUrl;
                    mainImage.style.opacity = '1';
                }, 150);
            }
        });
    });
});

// Toggle detail sections
function toggleDetailSection(button) {
    const section = button.closest('.detail-section');
    const content = section.querySelector('.detail-section-content');
    const icon = button.querySelector('.section-icon');
    
    // Toggle active class
    section.classList.toggle('active');
    
    // Update icon
    if (section.classList.contains('active')) {
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}
</script>


