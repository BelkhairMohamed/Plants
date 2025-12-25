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
                        <a href="<?php echo BASE_URL; ?>/?controller=social&action=profile&user_id=<?php echo $post['user_id']; ?>" class="post-user-link">
                            <?php 
                            $defaultAvatar = BASE_URL . '/public/Images/profile-icon-symbol-design-illustration-vector.jpg';
                            $avatarUrl = !empty($post['avatar_url']) ? $post['avatar_url'] : $defaultAvatar;
                            ?>
                            <img src="<?php echo htmlspecialchars($avatarUrl); ?>" alt="" class="avatar">
                        </a>
                        <div>
                            <a href="<?php echo BASE_URL; ?>/?controller=social&action=profile&user_id=<?php echo $post['user_id']; ?>" class="post-username-link">
                                <strong><?php echo htmlspecialchars($post['username']); ?></strong>
                            </a>
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
                        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=social&action=like" style="display: inline-block; margin-right: 0.5rem;">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <input type="hidden" name="redirect" value="<?php echo BASE_URL; ?>/?controller=social&action=index">
                            <button type="submit" class="btn-like <?php echo ($post['is_liked'] ?? false) ? 'liked' : ''; ?>">
                                <i class="fas fa-heart"></i> <?php echo $post['like_count'] ?? 0; ?>
                            </button>
                        </form>
                        <a href="<?php echo BASE_URL; ?>/?controller=social&action=detail&id=<?php echo $post['id']; ?>#comments-section" 
                           class="btn btn-secondary" 
                           style="display: inline-block; text-decoration: none;"
                           onclick="window.location.href=this.href; return true;">
                            <i class="fas fa-comment"></i> Commenter
                        </a>
                    </div>
                    
                    <div class="post-stats">
                        <span><?php echo $post['comment_count'] ?? 0; ?> commentaires</span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>



