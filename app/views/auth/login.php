<?php
$pageTitle = 'Connexion';
?>

<div class="auth-container">
    <div class="auth-box">
        <h2>Connexion</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=auth&action=login">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
        </form>
        <p class="auth-link">
            Pas encore de compte ? <a href="<?php echo BASE_URL; ?>/?controller=auth&action=register">S'inscrire</a>
        </p>
    </div>
</div>




