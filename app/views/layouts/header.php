<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Plants Management'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/style.css">
    <script>
        // Define BASE_URL for JavaScript
        const BASE_URL = '<?php echo BASE_URL; ?>';
        
        // Dark Mode - Load immediately to prevent flash
        (function() {
            const html = document.documentElement;
            const currentTheme = localStorage.getItem('theme') || 'light';
            if (currentTheme === 'dark') {
                html.setAttribute('data-theme', 'dark');
            }
        })();
        
        // Initialize dark mode switch state immediately when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('dark-mode-switch');
            const currentTheme = localStorage.getItem('theme') || 'light';
            if (darkModeToggle) {
                darkModeToggle.checked = (currentTheme === 'dark');
            }
        });
    </script>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="<?php echo BASE_URL; ?>/" class="logo">
                <i class="fas fa-leaf"></i> Plants
            </a>
            <button class="hamburger" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <ul class="nav-menu">
                <li>
                    <label class="dark-mode-toggle" aria-label="Toggle dark mode">
                        <input type="checkbox" id="dark-mode-switch">
                        <span class="toggle-slider"></span>
                    </label>
                </li>
                <li><a href="<?php echo BASE_URL; ?>/">Accueil</a></li>
                <li><a href="<?php echo BASE_URL; ?>/?controller=plantCatalog&action=index">Catalogue</a></li>
                <li>
                    <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=cart" class="cart-link" title="Panier" id="cart-link">
                        <i class="fas fa-shopping-cart"></i>
                        <?php
                        // Use helper function to get cart count
                        $cartCount = getCartCount();
                        // Always render badge container, but hide if count is 0
                        echo "<span class='badge cart-badge' id='cart-badge' style='display: " . ($cartCount > 0 ? 'inline-flex' : 'none') . ";'>$cartCount</span>";
                        ?>
                    </a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo BASE_URL; ?>/?controller=dashboard&action=index">Tableau de bord</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/?controller=userPlant&action=index">Mes Plantes</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/?controller=social&action=index">Communauté</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index">Boutique</a></li>
                    <li>
                        <a href="<?php echo BASE_URL; ?>/?controller=notification&action=index" class="notification-link" title="Notifications">
                            <i class="fas fa-bell"></i>
                            <?php
                            $notificationCount = 0;
                            if (isset($_SESSION['user_id']) && class_exists('Notification')) {
                                try {
                                    $notificationModel = new Notification();
                                    $notificationCount = $notificationModel->getUnreadCount($_SESSION['user_id']);
                                } catch (Exception $e) {
                                    // Silently fail if notification system not available
                                }
                            }
                            // Always render badge container, but hide if count is 0
                            echo "<span class='badge notification-badge' id='notification-badge' style='display: " . ($notificationCount > 0 ? 'inline-block' : 'none') . ";'>$notificationCount</span>";
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

