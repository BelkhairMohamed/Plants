    </main>
    <footer class="footer bloom-footer">
        <div class="container">
            <div class="footer-main">
                <div class="footer-section">
                    <h4>Company</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo BASE_URL; ?>/">À propos</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/?controller=marketplace&action=index">Boutique</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/?controller=social&action=index">Communauté</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/?controller=plantCatalog&action=index">Catalogue</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Support</h4>
                    <ul class="footer-links">
                        <li><a href="#">Aide & FAQ</a></li>
                        <li><a href="#">Suivre ma commande</a></li>
                        <li><a href="#">Livraison</a></li>
                        <li><a href="#">Retours</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Questions sur les plantes ?</h4>
                    <ul class="footer-links">
                        <li><a href="#">Conseils d'entretien</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Guides de soins</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Restez informé</h4>
                    <p class="footer-newsletter-text">Restez informé des offres spéciales, conseils de jardinage et plus encore.</p>
                    <form class="newsletter-form" method="POST" action="#">
                        <input type="email" placeholder="Votre email" required>
                        <button type="submit" class="btn-newsletter">S'abonner</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p>&copy; <?php echo date('Y'); ?> Plants Management. Tous droits réservés.</p>
                    <div class="footer-legal">
                        <a href="#">Conditions</a>
                        <a href="#">Politique de confidentialité</a>
                        <a href="#">Accessibilité</a>
                    </div>
                    <div class="footer-social">
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Pinterest"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="<?php echo BASE_URL; ?>/public/assets/js/main.js"></script>
</body>
</html>



