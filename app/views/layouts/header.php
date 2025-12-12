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
        <div class="navbar-main">
            <div class="navbar-left">
                <a href="<?php echo BASE_URL; ?>/" class="logo">
                    <i class="fas fa-leaf"></i> Plants
                </a>
            </div>
            <div class="navbar-right">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php
                    // Get user avatar
                    $userModel = new User();
                    $currentUser = $userModel->findById($_SESSION['user_id']);
                    $userAvatar = $currentUser['avatar_url'] ?? 'https://via.placeholder.com/150';
                    ?>
                    <button class="search-toggle-btn" id="search-toggle-btn" title="Rechercher des utilisateurs">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=cart" class="navbar-icon-link cart-link" title="Panier" id="cart-link">
                        <i class="fas fa-shopping-cart"></i>
                        <?php
                        $cartCount = getCartCount();
                        echo "<span class='badge cart-badge' id='cart-badge' style='display: " . ($cartCount > 0 ? 'inline-flex' : 'none') . ";'>$cartCount</span>";
                        ?>
                    </a>
                    <a href="<?php echo BASE_URL; ?>/?controller=notification&action=index" class="navbar-icon-link notification-link" title="Notifications">
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
                        echo "<span class='badge notification-badge' id='notification-badge' style='display: " . ($notificationCount > 0 ? 'inline-block' : 'none') . ";'>$notificationCount</span>";
                        ?>
                    </a>
                    <a href="<?php echo BASE_URL; ?>/?controller=social&action=profile&user_id=<?php echo $_SESSION['user_id']; ?>" class="navbar-profile-link" title="Profil">
                        <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="<?php echo htmlspecialchars($_SESSION['user_username']); ?>" class="navbar-profile-avatar">
                    </a>
                    <a href="<?php echo BASE_URL; ?>/?controller=auth&action=logout" class="navbar-logout-btn" title="Déconnexion">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>/?controller=auth&action=login" class="navbar-login-btn">Connexion</a>
                    <a href="<?php echo BASE_URL; ?>/?controller=auth&action=register" class="navbar-register-btn">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="navbar-sub">
            <div class="navbar-sub-container">
                <a href="<?php echo BASE_URL; ?>/?controller=plantCatalog&action=index" class="navbar-sub-link">Catalogue</a>
                <a href="<?php echo BASE_URL; ?>/?controller=dashboard&action=index" class="navbar-sub-link">Tableau de bord</a>
                <a href="<?php echo BASE_URL; ?>/?controller=social&action=index" class="navbar-sub-link">Communauté</a>
                <a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index" class="navbar-sub-link">Boutique</a>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="<?php echo BASE_URL; ?>/?controller=admin&action=plants" class="navbar-sub-link">Admin</a>
                <?php endif; ?>
                <div class="navbar-sub-spacer"></div>
                <label class="dark-mode-toggle" aria-label="Toggle dark mode">
                    <input type="checkbox" id="dark-mode-switch">
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
        <?php endif; ?>
    </nav>
    
    <!-- User Search Sidebar -->
    <div class="search-sidebar" id="search-sidebar">
        <div class="search-sidebar-header">
            <h2><i class="fas fa-search"></i> Rechercher des utilisateurs</h2>
            <button class="search-sidebar-close" id="search-sidebar-close" aria-label="Fermer">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="search-sidebar-content">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input 
                    type="text" 
                    id="user-search-input" 
                    class="user-search-input" 
                    placeholder="Tapez un nom d'utilisateur..."
                    autocomplete="off"
                >
                <button class="search-clear-btn" id="search-clear-btn" style="display: none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="search-results" id="search-results">
                <div class="search-placeholder">
                    <i class="fas fa-user-friends"></i>
                    <p>Tapez au moins 2 caractères pour rechercher</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Overlay for sidebar -->
    <div class="search-sidebar-overlay" id="search-sidebar-overlay"></div>
    
    <main class="main-content">

