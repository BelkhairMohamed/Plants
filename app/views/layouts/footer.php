    </main>
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4><i class="fas fa-leaf"></i> Plants Management</h4>
                    <p>Système complet de gestion de plantes d'intérieur. Gérez vos plantes, découvrez de nouvelles espèces et partagez votre passion avec une communauté de jardiniers.</p>
                </div>
                <div class="footer-section">
                    <h4><i class="fas fa-compass"></i> Navigation</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo BASE_URL; ?>/"><i class="fas fa-home"></i> Accueil</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/?controller=plantCatalog&action=index"><i class="fas fa-book"></i> Catalogue</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index"><i class="fas fa-store"></i> Boutique</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/?controller=social&action=index"><i class="fas fa-users"></i> Communauté</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4><i class="fas fa-user-circle"></i> Compte</h4>
                    <ul class="footer-links">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="<?php echo BASE_URL; ?>/?controller=dashboard&action=index"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/?controller=userPlant&action=index"><i class="fas fa-seedling"></i> Mes Plantes</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/?controller=social&action=profile&user_id=<?php echo $_SESSION['user_id']; ?>"><i class="fas fa-user"></i> Mon Profil</a></li>
                        <?php else: ?>
                            <li><a href="<?php echo BASE_URL; ?>/?controller=auth&action=login"><i class="fas fa-sign-in-alt"></i> Connexion</a></li>
                            <li><a href="<?php echo BASE_URL; ?>/?controller=auth&action=register"><i class="fas fa-user-plus"></i> Inscription</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Plants Management System. Tous droits réservés. <i class="fas fa-heart" style="color: var(--primary-color); margin: 0 0.25rem;"></i> Fait avec passion</p>
            </div>
        </div>
    </footer>
    <script src="<?php echo BASE_URL; ?>/public/assets/js/main.js"></script>
</body>
</html>



