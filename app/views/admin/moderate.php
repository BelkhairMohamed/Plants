<?php
$pageTitle = 'Administration - Modération';
?>

<div class="container">
    <h1>Modération des posts</h1>
    
    <div class="admin-section">
        <h2>Posts récents</h2>
        <div class="posts-list">
            <?php foreach ($posts as $post): ?>
                <div class="post-card-admin">
                    <div class="post-header">
                        <strong>Post #<?php echo $post['id']; ?></strong>
                        <span class="post-date"><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></span>
                    </div>
                    <p><?php echo htmlspecialchars(substr($post['content_text'], 0, 200)); ?>...</p>
                    <div class="admin-actions">
                        <a href="<?php echo BASE_URL; ?>/?controller=social&action=detail&id=<?php echo $post['id']; ?>" class="btn btn-secondary btn-sm">Voir</a>
                        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=admin&action=moderate" style="display: inline;" onsubmit="return confirm('Supprimer ce post?');">
                            <input type="hidden" name="delete_post" value="1">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>




