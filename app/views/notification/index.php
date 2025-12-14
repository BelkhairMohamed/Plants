<?php
$pageTitle = 'Notifications';
?>

<div class="container">
    <div class="notifications-header">
        <h1>Notifications</h1>
        <?php if (!$unreadOnly): ?>
            <form method="POST" action="<?php echo BASE_URL; ?>/?controller=notification&action=markAllRead" style="display: inline;">
                <button type="submit" class="btn btn-secondary">Tout marquer comme lu</button>
            </form>
        <?php endif; ?>
    </div>
    
    <div class="notifications-list">
        <?php if (empty($notifications)): ?>
            <div class="empty-state">
                <p>Aucune notification.</p>
            </div>
        <?php else: ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-card <?php echo $notification['is_read'] ? 'read' : 'unread'; ?>">
                    <div class="notification-content">
                        <p><?php echo htmlspecialchars($notification['message']); ?></p>
                        <span class="notification-date"><?php echo date('d/m/Y H:i', strtotime($notification['created_at'])); ?></span>
                    </div>
                    <?php if ($notification['link_url']): ?>
                        <a href="<?php echo BASE_URL . $notification['link_url']; ?>" class="btn btn-secondary btn-sm">Voir</a>
                    <?php endif; ?>
                    <?php if (!$notification['is_read']): ?>
                        <form method="POST" action="<?php echo BASE_URL; ?>/?controller=notification&action=markRead" style="display: inline;">
                            <input type="hidden" name="id" value="<?php echo $notification['id']; ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Marquer comme lu</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>



