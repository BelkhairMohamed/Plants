<?php
$pageTitle = 'Inscription';
?>

<div class="auth-container">
    <div class="auth-box">
        <h2>Inscription</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=auth&action=register">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="bio">Bio (optionnel)</label>
                <textarea id="bio" name="bio" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
        </form>
        <p class="auth-link">
            Déjà un compte ? <a href="<?php echo BASE_URL; ?>/?controller=auth&action=login">Se connecter</a>
        </p>
    </div>
</div>




