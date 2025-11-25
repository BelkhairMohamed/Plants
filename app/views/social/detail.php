<?php
$pageTitle = 'Post';
?>

<div class="container">
    <div class="post-detail-page">
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
            
            <?php if ($post['related_user_plant_id']): ?>
                <div class="related-plant">
                    <p>🌱 Plante liée: 
                        <a href="<?php echo BASE_URL; ?>/?controller=userPlant&action=detail&id=<?php echo $post['related_user_plant_id']; ?>">
                            <?php echo htmlspecialchars($post['nickname_for_plant'] ?? $post['common_name'] ?? 'Plante'); ?>
                        </a>
                    </p>
                </div>
            <?php endif; ?>
            
            <div class="post-actions">
                <form method="POST" action="<?php echo BASE_URL; ?>/?controller=social&action=like" style="display: inline;">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <input type="hidden" name="redirect" value="<?php echo BASE_URL; ?>/?controller=social&action=detail&id=<?php echo $post['id']; ?>">
                    <button type="submit" class="btn-like <?php echo $isLiked ? 'liked' : ''; ?>">
                        ❤️ <?php echo $likeCount; ?>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="comments-section">
            <h2>Commentaires</h2>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="POST" action="<?php echo BASE_URL; ?>/?controller=social&action=comment" class="comment-form">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <div class="form-group">
                        <textarea name="content" rows="3" placeholder="Écrivez un commentaire..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Commenter</button>
                </form>
            <?php endif; ?>
            
            <div class="comments-list">
                <?php if (empty($comments)): ?>
                    <p>Aucun commentaire pour le moment.</p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-card">
                            <div class="comment-header">
                                <img src="<?php echo htmlspecialchars($comment['avatar_url'] ?? 'https://via.placeholder.com/30'); ?>" alt="" class="avatar-small">
                                <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                                <span class="comment-date"><?php echo date('d/m/Y H:i', strtotime($comment['created_at'])); ?></span>
                            </div>
                            <p><?php echo nl2br(htmlspecialchars($comment['content_text'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



