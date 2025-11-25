<?php
$pageTitle = 'Accueil - Plants Management';
?>

<div class="hero">
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

<div class="features">
    <div class="container">
        <h2>Fonctionnalités</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <h3>📚 Catalogue complet</h3>
                <p>Explorez une vaste collection de plantes d'intérieur avec toutes les informations nécessaires</p>
            </div>
            <div class="feature-card">
                <h3>🌱 Gestion personnelle</h3>
                <p>Suivez vos plantes, recevez des rappels d'arrosage et de fertilisation</p>
            </div>
            <div class="feature-card">
                <h3>🛒 Marketplace</h3>
                <p>Achetez des plantes, graines, pots et accessoires directement sur la plateforme</p>
            </div>
            <div class="feature-card">
                <h3>👥 Communauté</h3>
                <p>Partagez vos expériences, posez des questions et obtenez de l'aide</p>
            </div>
            <div class="feature-card">
                <h3>🌤️ Météo intelligente</h3>
                <p>Recevez des conseils personnalisés basés sur les conditions météorologiques</p>
            </div>
            <div class="feature-card">
                <h3>📱 Rappels automatiques</h3>
                <p>Ne manquez jamais un arrosage ou une fertilisation avec nos rappels intelligents</p>
            </div>
        </div>
    </div>
</div>



