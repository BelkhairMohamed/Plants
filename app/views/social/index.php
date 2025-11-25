<?php
$pageTitle = 'Communauté';
?>

<div class="container">
    <div class="social-header">
        <h1>Communauté</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?php echo BASE_URL; ?>/?controller=social&action=create" class="btn btn-primary">Créer un post</a>
        <?php endif; ?>
    </div>
    
    <div class="posts-feed">
        <?php if (empty($posts)): ?>
            <div class="empty-state">
                <p>Aucun post pour le moment.</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-card">
                    <div class="post-header">
                        <img src="<?php echo htmlspecialchars($post['avatar_url'] ?? 'https://via.placeholder.com/40'); ?>" alt="" class="avatar">
                        <div>
                            <strong><?php echo htmlspecialchars($post['username']); ?></strong>
                            <span class="post-date"><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></span>
                        </div>
                    </div>
                    
                    <?php if ($post['image_url']): ?>
                        <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="" class="post-image">
                    <?php endif; ?>
                    
                    <div class="post-content">
                        <p><?php echo nl2br(htmlspecialchars($post['content_text'])); ?></p>
                    </div>
                    
                    <div class="post-actions">
                        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=social&action=like" style="display: inline;">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <input type="hidden" name="redirect" value="<?php echo BASE_URL; ?>/?controller=social&action=index">
                            <button type="submit" class="btn-like <?php echo ($post['is_liked'] ?? false) ? 'liked' : ''; ?>">
                                ❤️ <?php echo $post['like_count'] ?? 0; ?>
                            </button>
                        </form>
                        <a href="<?php echo BASE_URL; ?>/?controller=social&action=detail&id=<?php echo $post['id']; ?>" class="btn btn-secondary">Commenter</a>
                    </div>
                    
                    <div class="post-stats">
                        <span><?php echo $post['comment_count'] ?? 0; ?> commentaires</span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>



