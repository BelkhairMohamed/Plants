<?php
$pageTitle = 'Accueil - Plants Management';
?>

<div class="hero hero-with-video">
    <video class="hero-video" autoplay muted loop playsinline>
        <!-- Video located in public/Video/hero-bg.webm -->
        <source src="<?php echo BASE_URL; ?>/public/Video/hero-bg.webm" type="video/webm">
        <!-- Fallback: si la vidéo ne charge pas, le gradient sera affiché -->
    </video>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="container hero-container-artistic">
            <h1 class="hero-title-artistic">Bienvenue sur Plants Management</h1>
            <p class="lead hero-subtitle-artistic">Votre assistant personnel pour prendre soin de vos plantes d'intérieur</p>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="cta-buttons">
                    <a href="<?php echo BASE_URL; ?>/?controller=auth&action=register" class="btn btn-primary">Commencer gratuitement</a>
                    <a href="<?php echo BASE_URL; ?>/?controller=plantCatalog&action=index" class="btn btn-secondary">Explorer le catalogue</a>
                </div>
            <?php else: ?>
                <div class="cta-buttons">
                    <a href="<?php echo BASE_URL; ?>/?controller=dashboard&action=index" class="btn btn-primary">Mon tableau de bord</a>
                    <a href="<?php echo BASE_URL; ?>/?controller=plantCatalog&action=index" class="btn btn-secondary">Ajouter une plante</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Banner Section Between Hero and Best Sellers (Dark Green) -->
<section class="hales-banner-section">
    <div class="container">
        <div class="hales-banner-content">
            <div class="hales-banner-icon">
                <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Stem -->
                    <line x1="40" y1="50" x2="40" y2="75" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    <!-- Leaves -->
                    <path d="M40 60 Q30 55 25 60" stroke="white" stroke-width="2" fill="none" stroke-linecap="round"/>
                    <path d="M40 60 Q50 55 55 60" stroke="white" stroke-width="2" fill="none" stroke-linecap="round"/>
                    <!-- Flower petals -->
                    <circle cx="40" cy="35" r="8" stroke="white" stroke-width="2" fill="none"/>
                    <circle cx="35" cy="30" r="6" stroke="white" stroke-width="2" fill="none"/>
                    <circle cx="45" cy="30" r="6" stroke="white" stroke-width="2" fill="none"/>
                    <circle cx="40" cy="25" r="6" stroke="white" stroke-width="2" fill="none"/>
                    <!-- Center -->
                    <circle cx="40" cy="35" r="3" fill="white"/>
                </svg>
            </div>
            <div class="hales-banner-text">
                <p>Here at Plants, we work with a small list of carefully chosen producers, source the freshest hothouse blooms, wildflowers and seasonal greenery and display them in custom pottery and glassware.</p>
            </div>
        </div>
    </div>
</section>

<!-- Best Sellers Section -->
<?php if (!empty($bestSellers)): ?>
<section class="bloom-section best-sellers-section">
    <div class="container">
        <div class="best-sellers-layout">
            <div class="best-sellers-left">
                <div class="hales-section-header-left">
                    <h2 class="hales-title-serif">Best sellers.</h2>
                    <p class="hales-description">Transform your home or office with our gorgeous best selling seasonal arrangements.</p>
                    <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index" class="btn-bloom-shop-left">Shop Best Sellers</a>
                </div>
            </div>
            <div class="best-sellers-right">
                <div class="bloom-product-grid">
            <?php 
            $sizes = ['SM', 'MD', 'LG', 'XL', 'XXL'];
            $potColors = ['#E8E8E8', '#4A90E2', '#2C3E50', '#34495E', '#95A5A6'];
            foreach (array_slice($bestSellers, 0, 8) as $index => $product): 
                $rating = 4.5 + (rand(0, 10) / 10); // Random rating between 4.5 and 5.5
                $reviews = rand(15, 60);
                $onSale = rand(0, 1); // Random sale badge
                $originalPrice = $onSale ? $product['price'] * 1.25 : $product['price'];
                $size = $sizes[array_rand($sizes)];
            ?>
                <div class="bloom-product-card">
                    <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=detail&id=<?php echo $product['id']; ?>" class="bloom-product-link">
                        <div class="bloom-product-image-wrapper">
                            <?php if ($onSale): ?>
                                <span class="sale-badge">20% OFF</span>
                            <?php endif; ?>
                            <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'https://via.placeholder.com/300'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="bloom-product-image">
                        </div>
                        <div class="bloom-product-info">
                            <h3 class="bloom-product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <div class="bloom-product-price-wrapper">
                                <?php if ($onSale): ?>
                                    <span class="bloom-price-original"><?php echo number_format($originalPrice, 0); ?> €</span>
                                <?php endif; ?>
                                <span class="bloom-price-current"><?php echo number_format($product['price'], 0); ?> €</span>
                            </div>
                            <div class="bloom-product-meta">
                                <span class="bloom-product-size"><?php echo $size; ?></span>
                            </div>
                            <div class="bloom-product-colors">
                                <?php foreach (array_slice($potColors, 0, 4) as $color): ?>
                                    <span class="color-option" style="background-color: <?php echo $color; ?>"></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="bloom-product-rating">
                                <div class="star-rating">
                                    <?php 
                                    $fullStars = floor($rating);
                                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                    for ($i = 0; $i < $fullStars; $i++): ?>
                                        <i class="fas fa-star"></i>
                                    <?php endfor; ?>
                                    <?php if ($hasHalfStar): ?>
                                        <i class="fas fa-star-half-alt"></i>
                                    <?php endif; ?>
                                    <?php 
                                    $emptyStars = 5 - ceil($rating);
                                    for ($i = 0; $i < $emptyStars; $i++): ?>
                                        <i class="far fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="review-count"><?php echo $reviews; ?> Reviews</span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Video and Text Section (Bloomandwild Style) -->
<section class="bloom-video-text-section">
    <div class="bloom-video-text-container">
        <div class="bloom-video-wrapper">
            <video class="bloom-content-video" autoplay muted loop playsinline>
                <source src="<?php echo BASE_URL; ?>/public/Video/0c3fed7e-161fa48d.mp4" type="video/mp4">
                <source src="<?php echo BASE_URL; ?>/public/Video/hero-bg.webm" type="video/webm">
            </video>
        </div>
        <div class="bloom-text-content">
            <h2 class="bloom-text-title">'Tis the season...</h2>
            <p class="bloom-text-description">For thanking the colleague who fills the office with festive spirit. For fussing over nan. Care wildly for the people who make Christmas with our thoughtfully wrapped gifts.</p>
            <div class="bloom-text-buttons">
                <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index" class="btn-bloom-white">Shop all</a>
                <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index" class="btn-bloom-white">Shop Christmas</a>
            </div>
        </div>
    </div>
</section>

<!-- More Ways to Find Your Perfect Plant -->
<section class="bloom-section category-section-bloomscape">
    <div class="container">
        <h2 class="bloomscape-section-title">More Ways to Find Your Perfect Plant</h2>
        <div class="bloomscape-category-grid">
            <a href="<?php echo BASE_URL; ?>/?controller=plantCatalog&action=index&pet_friendly=1" class="bloomscape-category-card">
                <div class="bloomscape-image-wrapper">
                    <img src="https://bloomscape.com/wp-content/uploads/2021/07/11_15_18_Bloomscape_221_1185x1582-767x1024.jpg?ver=556506" alt="Pet-Friendly" class="bloomscape-image">
                </div>
                <h3 class="bloomscape-card-title">Pet-Friendly</h3>
            </a>
            <a href="<?php echo BASE_URL; ?>/?controller=plantCatalog&action=index&difficulty=beginner" class="bloomscape-category-card">
                <div class="bloomscape-image-wrapper">
                    <img src="https://bloomscape.com/wp-content/uploads/2021/10/20200923_Bloomscape_Trish__0046_1185%E2%80%8A%C3%97%E2%80%8A1582-767x1024.jpg?ver=612704" alt="Low-Maintenance" class="bloomscape-image">
                </div>
                <h3 class="bloomscape-card-title">Low-Maintenance</h3>
            </a>
            <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index&category=seed" class="bloomscape-category-card">
                <div class="bloomscape-image-wrapper">
                    <img src="https://bloomscape.com/wp-content/uploads/2022/07/bloomscape_cacti-succulent-group1-hptile.jpeg?ver=915376" alt="Cacti & Succulents" class="bloomscape-image">
                </div>
                <h3 class="bloomscape-card-title">Cacti & Succulents</h3>
            </a>
            <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index" class="bloomscape-category-card">
                <div class="bloomscape-image-wrapper">
                    <img src="https://bloomscape.com/wp-content/uploads/2021/11/bloomscape_gifts-75-150_group_vertical_1185x1582-767x1024.jpg?ver=615127" alt="Gifts" class="bloomscape-image">
                </div>
                <h3 class="bloomscape-card-title">Gifts</h3>
            </a>
        </div>
    </div>
</section>

