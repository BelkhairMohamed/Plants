<?php
$pageTitle = 'Modifier mon profil';
?>

<div class="container">
    <div class="profile-edit-page">
        <h1>Modifier mon profil</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <div class="profile-edit-grid">
            <div class="profile-edit-card">
                <h2>Photo de profil</h2>
                <div class="avatar-upload-section">
                    <div class="current-avatar">
                        <img src="<?php echo htmlspecialchars($user['avatar_url'] ?? 'https://via.placeholder.com/150'); ?>" alt="Avatar actuel" id="avatar-preview">
                    </div>
                    <form method="POST" action="<?php echo BASE_URL; ?>/?controller=auth&action=profile" enctype="multipart/form-data" class="avatar-form" id="avatarForm">
                        <div class="form-group">
                            <label for="avatar">Choisir une nouvelle photo</label>
                            <input type="file" id="avatar" name="avatar" accept="image/*" onchange="previewAvatar(this)" required>
                            <small>Formats acceptés: JPG, PNG, GIF, WEBP (max 5MB)</small>
                        </div>
                        <button type="submit" name="update_avatar" value="1" class="btn btn-primary">Mettre à jour la photo</button>
                    </form>
                </div>
            </div>
            
            <div class="profile-edit-card">
                <h2>Informations personnelles</h2>
                <form method="POST" action="<?php echo BASE_URL; ?>/?controller=auth&action=profile">
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" rows="4" placeholder="Parlez-nous de vous..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="city">Ville</label>
                        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" placeholder="Ex: Paris">
                    </div>
                    
                    <button type="submit" name="update_profile" class="btn btn-primary">Enregistrer les modifications</button>
                </form>
            </div>
            
            <div class="profile-edit-card">
                <h2>Changer le mot de passe</h2>
                <form method="POST" action="<?php echo BASE_URL; ?>/?controller=auth&action=profile">
                    <div class="form-group">
                        <label for="current_password">Mot de passe actuel</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">Nouveau mot de passe</label>
                        <input type="password" id="new_password" name="new_password" required minlength="6">
                        <small>Minimum 6 caractères</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                    </div>
                    
                    <button type="submit" name="change_password" class="btn btn-primary">Changer le mot de passe</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Password confirmation validation
document.addEventListener('DOMContentLoaded', function() {
    const passwordForm = document.querySelector('form[action*="profile"]');
    if (passwordForm) {
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('confirm_password');
        
        if (newPassword && confirmPassword) {
            function validatePassword() {
                if (newPassword.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }
            
            newPassword.addEventListener('change', validatePassword);
            confirmPassword.addEventListener('keyup', validatePassword);
        }
    }
});
</script>

