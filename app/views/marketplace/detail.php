<?php
$pageTitle = htmlspecialchars($product['name']);
?>

<div class="container">
    <div class="product-detail">
        <div class="product-image-carousel">
            <?php
            // Get images - prefer images array from model, fallback to image_url
            $images = [];
            if (!empty($product['images']) && is_array($product['images'])) {
                $images = $product['images'];
            } elseif (!empty($product['image_url'])) {
                // Try to decode as JSON (for backward compatibility)
                $decoded = json_decode($product['image_url'], true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $images = $decoded;
                } else {
                    // Single image URL
                    $images = [$product['image_url']];
                }
            } else {
                $images = ['https://via.placeholder.com/500'];
            }
            ?>
            <div class="product-image-container">
                <?php foreach ($images as $index => $imageUrl): ?>
                    <div class="product-image-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                        <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($images) > 1): ?>
                <button class="product-carousel-btn product-carousel-prev" aria-label="Image précédente">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="product-carousel-btn product-carousel-next" aria-label="Image suivante">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="product-carousel-indicators">
                    <?php foreach ($images as $index => $imageUrl): ?>
                        <button class="product-carousel-indicator <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>" aria-label="Image <?php echo $index + 1; ?>"></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="product-category">Catégorie: <?php echo htmlspecialchars($product['category']); ?></p>
            <p class="product-price-large"><?php echo number_format($product['price'], 2); ?> €</p>
            <p class="product-stock">Stock disponible: <?php echo $product['stock']; ?></p>
            
            <?php if ($product['description']): ?>
                <div class="product-description">
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ($product['stock'] > 0): ?>
                <form method="POST" action="<?php echo BASE_URL; ?>/?controller=marketplace&action=addToCart" id="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div class="form-group">
                        <label>Quantité:</label>
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" id="cart-quantity">
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter au panier</button>
                </form>
            <?php else: ?>
                <div class="alert alert-error">Produit en rupture de stock</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.product-image-carousel');
    if (!carousel) return;
    
    const slides = carousel.querySelectorAll('.product-image-slide');
    const indicators = carousel.querySelectorAll('.product-carousel-indicator');
    const prevBtn = carousel.querySelector('.product-carousel-prev');
    const nextBtn = carousel.querySelector('.product-carousel-next');
    
    if (slides.length <= 1) return; // No carousel needed for single image
    
    let currentIndex = 0;
    
    function showSlide(index) {
        // Ensure index is within bounds
        if (index < 0) {
            index = slides.length - 1;
        } else if (index >= slides.length) {
            index = 0;
        }
        
        // Update slides
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        
        // Update indicators
        indicators.forEach((indicator, i) => {
            indicator.classList.toggle('active', i === index);
        });
        
        currentIndex = index;
    }
    
    // Previous button
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            showSlide(currentIndex - 1);
        });
    }
    
    // Next button
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            showSlide(currentIndex + 1);
        });
    }
    
    // Indicator clicks
    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', function() {
            showSlide(index);
        });
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (carousel.querySelector(':hover')) {
            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                showSlide(currentIndex - 1);
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                showSlide(currentIndex + 1);
            }
        }
    });
    
    // Auto-play (optional - can be disabled)
    // let autoPlayInterval = setInterval(() => {
    //     showSlide(currentIndex + 1);
    // }, 5000);
    
    // Pause on hover
    // carousel.addEventListener('mouseenter', () => clearInterval(autoPlayInterval));
    // carousel.addEventListener('mouseleave', () => {
    //     autoPlayInterval = setInterval(() => {
    //         showSlide(currentIndex + 1);
    //     }, 5000);
    // });
});
</script>

