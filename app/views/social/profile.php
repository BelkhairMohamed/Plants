<?php
$pageTitle = 'Profil - ' . htmlspecialchars($user['username']);
?>

<div class="container">
    <div class="instagram-profile">
        <div class="profile-header-instagram">
            <div class="profile-avatar-instagram">
                <?php 
                $defaultAvatar = BASE_URL . '/public/Images/profile-icon-symbol-design-illustration-vector.jpg';
                $avatarUrl = !empty($user['avatar_url']) ? $user['avatar_url'] : $defaultAvatar;
                ?>
                <img src="<?php echo htmlspecialchars($avatarUrl); ?>" alt="<?php echo htmlspecialchars($user['username']); ?>" class="avatar-large">
            </div>
            <div class="profile-info-instagram">
                <div class="profile-header-top">
                    <h1 class="profile-username"><?php echo htmlspecialchars($user['username']); ?></h1>
                    <?php if ($isOwnProfile): ?>
                        <a href="<?php echo BASE_URL; ?>/?controller=auth&action=profile" class="btn btn-secondary btn-edit-profile">
                            Modifier le profil
                        </a>
                        <button class="btn-icon" title="Options">
                            <i class="fas fa-cog"></i>
                        </button>
                    <?php else: ?>
                        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=social&action=follow" class="follow-form" style="display: inline-block;">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <input type="hidden" name="redirect" value="<?php echo BASE_URL; ?>/?controller=social&action=profile&user_id=<?php echo $user['id']; ?>">
                            <button type="submit" class="btn btn-primary btn-follow <?php echo $isFollowing ? 'btn-unfollow' : ''; ?>" data-user-id="<?php echo $user['id']; ?>">
                                <?php echo $isFollowing ? 'Ne plus suivre' : 'Suivre'; ?>
                            </button>
                        </form>
                        <button class="btn-icon" title="Message">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        <button class="btn-icon" title="Options">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    <?php endif; ?>
                </div>
                
                <div class="profile-stats">
                    <div class="stat-item">
                        <strong><?php echo number_format($postsCount); ?></strong>
                        <span>publications</span>
                    </div>
                    <div class="stat-item">
                        <strong><?php echo number_format($followersCount); ?></strong>
                        <span>abonnés</span>
                    </div>
                    <div class="stat-item">
                        <strong><?php echo number_format($followingCount); ?></strong>
                        <span>abonnements</span>
                    </div>
                </div>
                
                <div class="profile-bio">
                    <h2 class="profile-name"><?php echo htmlspecialchars($user['username']); ?></h2>
                    <?php if ($user['bio']): ?>
                        <p><?php echo nl2br(htmlspecialchars($user['bio'])); ?></p>
                    <?php endif; ?>
                    <?php if ($user['city']): ?>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($user['city']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="profile-tabs-instagram">
            <div class="tab-item active">
                <i class="fas fa-th"></i>
                <span>PUBLICATIONS</span>
            </div>
        </div>
        
        <div class="profile-posts-grid">
            <?php if (empty($posts)): ?>
                <div class="empty-posts">
                    <div class="empty-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <p>Aucune publication</p>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post-grid-item" onclick="window.location.href='<?php echo BASE_URL; ?>/?controller=social&action=detail&id=<?php echo $post['id']; ?>'">
                        <?php if ($post['image_url']): ?>
                            <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="Post" class="post-thumbnail">
                        <?php else: ?>
                            <div class="post-thumbnail-text">
                                <i class="fas fa-file-alt"></i>
                                <p><?php echo htmlspecialchars(substr($post['content_text'], 0, 100)); ?><?php echo strlen($post['content_text']) > 100 ? '...' : ''; ?></p>
                            </div>
                        <?php endif; ?>
                        <div class="post-overlay">
                            <div class="post-overlay-stats">
                                <span><i class="fas fa-heart"></i> <?php echo $post['like_count'] ?? 0; ?></span>
                                <span><i class="fas fa-comment"></i> <?php echo $post['comment_count'] ?? 0; ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle follow/unfollow with AJAX
    const followForms = document.querySelectorAll('.follow-form');
    followForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const userId = formData.get('user_id');
            const button = form.querySelector('.btn-follow');
            
            fetch('<?php echo BASE_URL; ?>/?controller=social&action=follow', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.textContent = data.isFollowing ? 'Ne plus suivre' : 'Suivre';
                    button.classList.toggle('btn-unfollow', data.isFollowing);
                    
                    // Update followers count
                    const followersStat = document.querySelector('.stat-item:nth-child(2) strong');
                    if (followersStat) {
                        followersStat.textContent = data.followersCount.toLocaleString();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Fallback to form submission
                form.submit();
            });
        });
    });
});
</script>
