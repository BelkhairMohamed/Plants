<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Plants Management'; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="<?php echo BASE_URL; ?>/" class="logo">🌱 Plants</a>
            <button class="hamburger" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="nav-menu">
                <li><a href="<?php echo BASE_URL; ?>/">Accueil</a></li>
                <li><a href="<?php echo BASE_URL; ?>/?controller=plantCatalog&action=index">Catalogue</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo BASE_URL; ?>/?controller=dashboard&action=index">Tableau de bord</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/?controller=userPlant&action=index">Mes Plantes</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/?controller=social&action=index">Communauté</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index">Boutique</a></li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/?controller=notification&action=index" class="notification-link">
                            🔔
                            <?php
                            if (isset($_SESSION['user_id']) && class_exists('Notification')) {
                                try {
                                    $notificationModel = new Notification();
                                    $unreadCount = $notificationModel->getUnreadCount($_SESSION['user_id']);
                                    if ($unreadCount > 0) {
                                        echo "<span class='badge'>$unreadCount</span>";
                                    }
                                } catch (Exception $e) {
                                    // Silently fail if notification system not available
                                }
                            }
                            ?>
                        </a>
                    </li>
                    <li><a href="<?php echo BASE_URL; ?>/?controller=social&action=profile&user_id=<?php echo $_SESSION['user_id']; ?>"><?php echo htmlspecialchars($_SESSION['user_username']); ?></a></li>
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li><a href="<?php echo BASE_URL; ?>/?controller=admin&action=plants">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo BASE_URL; ?>/?controller=auth&action=logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>/?controller=auth&action=login">Connexion</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/?controller=auth&action=register">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main class="main-content">

