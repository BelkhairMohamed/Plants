<?php
$pageTitle = 'Profil - ' . htmlspecialchars($user['username']);
?>

<div class="container">
    <div class="profile-page">
        <div class="profile-header">
            <div class="profile-avatar-wrapper">
                <img src="<?php echo htmlspecialchars($user['avatar_url'] ?? 'https://via.placeholder.com/150'); ?>" alt="" class="profile-avatar">
                <?php if ($isOwnProfile): ?>
                    <a href="<?php echo BASE_URL; ?>/?controller=auth&action=profile" class="edit-profile-btn">
                        <i class="fas fa-edit"></i> Modifier le profil
                    </a>
                <?php endif; ?>
            </div>
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                <?php if ($user['bio']): ?>
                    <p class="bio"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
                <?php endif; ?>
                <?php if ($user['city']): ?>
                    <p class="location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($user['city']); ?></p>
                <?php endif; ?>
                <?php if ($isOwnProfile): ?>
                    <p class="profile-link">
                        <a href="<?php echo BASE_URL; ?>/?controller=userPlant&action=index" class="btn btn-secondary">
                            <i class="fas fa-seedling"></i> Voir mes plantes
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="profile-tabs">
            <h2>Posts</h2>
            <?php if (empty($posts)): ?>
                <div class="empty-state">
                    <p>Cet utilisateur n'a pas encore publié de posts.</p>
                </div>
            <?php else: ?>
                <div class="posts-list">
                    <?php foreach ($posts as $post): ?>
                        <div class="post-card-small">
                            <p><?php echo htmlspecialchars(substr($post['content_text'], 0, 200)); ?><?php echo strlen($post['content_text']) > 200 ? '...' : ''; ?></p>
                            <a href="<?php echo BASE_URL; ?>/?controller=social&action=detail&id=<?php echo $post['id']; ?>" class="btn btn-secondary btn-sm">Voir</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>



