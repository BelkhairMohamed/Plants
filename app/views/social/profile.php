<?php
$pageTitle = 'Profil - ' . htmlspecialchars($user['username']);
?>

<div class="container">
    <div class="profile-page">
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($user['avatar_url'] ?? 'https://via.placeholder.com/150'); ?>" alt="" class="profile-avatar">
            <div>
                <h1><?php echo htmlspecialchars($user['username']); ?></h1>
                <?php if ($user['bio']): ?>
                    <p class="bio"><?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
                <?php endif; ?>
                <?php if ($user['city']): ?>
                    <p>📍 <?php echo htmlspecialchars($user['city']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="profile-tabs">
            <h2>Mes plantes</h2>
            <?php if (empty($userPlants)): ?>
                <p>Cet utilisateur n'a pas encore de plantes.</p>
            <?php else: ?>
                <div class="plant-grid">
                    <?php foreach ($userPlants as $plant): ?>
                        <div class="plant-card-small">
                            <img src="<?php echo htmlspecialchars($plant['image_url'] ?? 'https://via.placeholder.com/200'); ?>" alt="">
                            <h4><?php echo htmlspecialchars($plant['nickname_for_plant'] ?? $plant['common_name']); ?></h4>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="profile-tabs">
            <h2>Posts</h2>
            <?php if (empty($posts)): ?>
                <p>Cet utilisateur n'a pas encore publié de posts.</p>
            <?php else: ?>
                <div class="posts-list">
                    <?php foreach ($posts as $post): ?>
                        <div class="post-card-small">
                            <p><?php echo htmlspecialchars(substr($post['content_text'], 0, 200)); ?>...</p>
                            <a href="<?php echo BASE_URL; ?>/?controller=social&action=detail&id=<?php echo $post['id']; ?>" class="btn btn-secondary btn-sm">Voir</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>



