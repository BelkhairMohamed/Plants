<?php
$pageTitle = 'Accueil - Plants Management';
?>

<div class="hero hero-with-video">
    <video class="hero-video" autoplay muted loop playsinline>
        <!-- Placez votre vidéo dans public/uploads/videos/hero-video.mp4 -->
        <source src="<?php echo BASE_URL; ?>/public/uploads/videos/hero-bg.webm" type="video/mp4">
        <source src="<?php echo BASE_URL; ?>/public/uploads/videos/hero-bg.webm" type="video/webm">
        <!-- Fallback: si la vidéo ne charge pas, le gradient sera affiché -->
    </video>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="container">
            <h1>Bienvenue sur Plants Management</h1>
            <p class="lead">Votre assistant personnel pour prendre soin de vos plantes d'intérieur</p>
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

<div class="features">
    <div class="container">
        <h2>Fonctionnalités</h2>
        <div class="feature-carousel-wrapper">
            <div class="feature-carousel">
                <div class="feature-card prev" data-index="0">
                    <button class="card-nav-btn card-nav-left" aria-label="Précédent">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="feature-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>Catalogue complet</h3>
                    <p>Explorez une vaste collection de plantes d'intérieur avec toutes les informations nécessaires</p>
                </div>
                <div class="feature-card active" data-index="1">
                    <div class="feature-icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h3>Gestion personnelle</h3>
                    <p>Suivez vos plantes, recevez des rappels d'arrosage et de fertilisation</p>
                </div>
                <div class="feature-card next" data-index="2">
                    <button class="card-nav-btn card-nav-right" aria-label="Suivant">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <div class="feature-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <h3>Marketplace</h3>
                    <p>Achetez des plantes, graines, pots et accessoires directement sur la plateforme</p>
                </div>
            </div>
        </div>
        <div class="carousel-dots">
            <span class="dot active" data-index="0"></span>
            <span class="dot" data-index="1"></span>
            <span class="dot" data-index="2"></span>
            <span class="dot" data-index="3"></span>
            <span class="dot" data-index="4"></span>
            <span class="dot" data-index="5"></span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.querySelector('.feature-carousel');
    const allCards = [
        { icon: 'fa-book', title: 'Catalogue complet', desc: 'Explorez une vaste collection de plantes d\'intérieur avec toutes les informations nécessaires' },
        { icon: 'fa-seedling', title: 'Gestion personnelle', desc: 'Suivez vos plantes, recevez des rappels d\'arrosage et de fertilisation' },
        { icon: 'fa-store', title: 'Marketplace', desc: 'Achetez des plantes, graines, pots et accessoires directement sur la plateforme' },
        { icon: 'fa-users', title: 'Communauté', desc: 'Partagez vos expériences, posez des questions et obtenez de l\'aide' },
        { icon: 'fa-cloud-sun', title: 'Météo intelligente', desc: 'Recevez des conseils personnalisés basés sur les conditions météorologiques' },
        { icon: 'fa-bell', title: 'Rappels automatiques', desc: 'Ne manquez jamais un arrosage ou une fertilisation avec nos rappels intelligents' }
    ];
    
    let currentIndex = 1;
    
    function renderCards() {
        const prevIndex = (currentIndex - 1 + allCards.length) % allCards.length;
        const nextIndex = (currentIndex + 1) % allCards.length;
        
        carousel.innerHTML = `
            <div class="feature-card prev" data-index="${prevIndex}">
                <button class="card-nav-btn card-nav-left" aria-label="Précédent">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="feature-icon">
                    <i class="fas ${allCards[prevIndex].icon}"></i>
                </div>
                <h3>${allCards[prevIndex].title}</h3>
                <p>${allCards[prevIndex].desc}</p>
            </div>
            <div class="feature-card active" data-index="${currentIndex}">
                <div class="feature-icon">
                    <i class="fas ${allCards[currentIndex].icon}"></i>
                </div>
                <h3>${allCards[currentIndex].title}</h3>
                <p>${allCards[currentIndex].desc}</p>
            </div>
            <div class="feature-card next" data-index="${nextIndex}">
                <button class="card-nav-btn card-nav-right" aria-label="Suivant">
                    <i class="fas fa-chevron-right"></i>
                </button>
                <div class="feature-icon">
                    <i class="fas ${allCards[nextIndex].icon}"></i>
                </div>
                <h3>${allCards[nextIndex].title}</h3>
                <p>${allCards[nextIndex].desc}</p>
            </div>
        `;
        
        // Re-attach event listeners
        document.querySelector('.card-nav-left')?.addEventListener('click', prevCard);
        document.querySelector('.card-nav-right')?.addEventListener('click', nextCard);
        
        // Update dots
        document.querySelectorAll('.dot').forEach((dot, index) => {
            dot.classList.toggle('active', index === currentIndex);
        });
    }
    
    function nextCard() {
        currentIndex = (currentIndex + 1) % allCards.length;
        renderCards();
    }
    
    function prevCard() {
        currentIndex = (currentIndex - 1 + allCards.length) % allCards.length;
        renderCards();
    }
    
    // Dots navigation
    document.querySelectorAll('.dot').forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentIndex = index;
            renderCards();
        });
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') prevCard();
        if (e.key === 'ArrowRight') nextCard();
    });
    
    renderCards();
});
</script>



