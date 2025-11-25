<?php
$pageTitle = 'Profil - ' . htmlspecialchars($user['username']);
$isOwnProfile = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id'];
?>

<div class="container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-content">
            <!-- Avatar Upload Section -->
            <div style="position: relative;">
                <img src="<?php echo htmlspecialchars($user['avatar_url'] ?? 'https://via.placeholder.com/150'); ?>" 
                     alt="<?php echo htmlspecialchars($user['username'] ?? 'User'); ?>" 
                     class="profile-avatar" 
                     id="profileAvatar">
                
                <?php if ($isOwnProfile): ?>
                <div style="position: absolute; bottom: 0; right: 0; width: 45px; height: 45px; background: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.15); font-size: 1.25rem;" id="avatarEditBtn" title="Change Profile Picture">
                    📷
                </div>
                <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;">
                <?php endif; ?>
            </div>

            <!-- Profile Info -->
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($user['username'] ?? 'User'); ?></h1>
                
                <p style="color: rgba(255, 255, 255, 0.9); margin: 0.5rem 0;">📧 <?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                
                <?php if (!empty($user['city'])): ?>
                <p style="color: rgba(255, 255, 255, 0.9); margin: 0.5rem 0;">
                    📍 <?php echo htmlspecialchars($user['city']); ?>
                </p>
                <?php endif; ?>
                
                <?php if (!empty($user['bio'])): ?>
                <p style="color: rgba(255, 255, 255, 0.9); margin: 1rem 0; line-height: 1.6;">
                    <?php echo nl2br(htmlspecialchars($user['bio'])); ?>
                </p>
                <?php else: ?>
                <p style="color: rgba(255, 255, 255, 0.7); margin: 1rem 0;">No bio yet</p>
                <?php endif; ?>

                <!-- Profile Stats -->
                <div class="profile-stats">
                    <div class="stat">
                        <div class="stat-value"><?php echo count($posts ?? []); ?></div>
                        <div class="stat-label">Posts</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value">0</div>
                        <div class="stat-label">Followers</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value">0</div>
                        <div class="stat-label">Following</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <?php if ($isOwnProfile): ?>
                <div style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                    <button class="btn btn-primary" onclick="openModal('editProfileModal')">
                        ✏️ Edit Profile
                    </button>
                    <button class="btn btn-primary" onclick="openModal('changePasswordModal')">
                        🔐 Change Password
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- User Posts Section -->
    <div style="margin-top: 2rem;">
        <h2 style="margin-bottom: 2rem;">Recent Activity</h2>

        <?php if (!empty($posts) && count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
            <div class="post-card">
                <div class="post-header">
                    <img src="<?php echo htmlspecialchars($user['avatar_url'] ?? 'https://via.placeholder.com/50'); ?>" 
                         alt="<?php echo htmlspecialchars($user['username']); ?>" 
                         class="avatar">
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: var(--text-primary);"><?php echo htmlspecialchars($user['username']); ?></div>
                        <div style="font-size: 0.875rem; color: #666;"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></div>
                    </div>
                </div>

                <div class="post-content">
                    <?php echo htmlspecialchars(substr($post['content'], 0, 200)); ?>
                    <?php if (strlen($post['content']) > 200): ?>...<?php endif; ?>
                </div>

                <?php if (!empty($post['image_url'])): ?>
                <img src="<?php echo htmlspecialchars($post['image_url']); ?>" 
                     alt="Post image" 
                     class="post-image">
                <?php endif; ?>

                <div class="post-actions">
                    <button class="action-btn like-btn" data-post-id="<?php echo $post['id']; ?>">
                        ❤️ <span class="like-count"><?php echo $post['likes_count'] ?? 0; ?></span>
                    </button>
                    <a href="/?controller=social&action=detail&id=<?php echo $post['id']; ?>" class="action-btn">
                        💬 Comments
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
        <div class="card" style="text-align: center; padding: 3rem;">
            <p style="color: var(--text-muted); font-size: 1.1rem;">📝 No posts yet</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Profile</h2>
            <button class="modal-close" onclick="closeModal('editProfileModal')">&times;</button>
        </div>
        <form id="editProfileForm" class="modal-body" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" placeholder="Tell us about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" placeholder="Your city">
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editProfileModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Change Password</h2>
            <button class="modal-close" onclick="closeModal('changePasswordModal')">&times;</button>
        </div>
        <form id="changePasswordForm" class="modal-body" method="POST">
            <div class="form-group">
                <label for="currentPassword">Current Password</label>
                <input type="password" id="currentPassword" name="current_password" required>
            </div>
            
            <div class="form-group">
                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" name="new_password" required minlength="8">
                <small style="color: var(--text-muted);">• At least 8 characters</small>
            </div>
            
            <div class="form-group">
                <label for="confirmPassword">Confirm New Password</label>
                <input type="password" id="confirmPassword" name="confirm_password" required minlength="8">
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('changePasswordModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal Functions
function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Close modal on outside click
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('active');
        }
    });
});

// Avatar Upload
document.addEventListener('DOMContentLoaded', function() {
    const avatarEditBtn = document.getElementById('avatarEditBtn');
    const avatarInput = document.getElementById('avatarInput');
    
    if (avatarEditBtn && avatarInput) {
        avatarEditBtn.addEventListener('click', function() {
            avatarInput.click();
        });
        
        avatarInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const formData = new FormData();
                formData.append('avatar', this.files[0]);
                
                fetch('/?controller=social&action=uploadAvatar', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('profileAvatar').src = data.avatar_url;
                        showAlert('Avatar updated successfully!', 'success');
                    } else {
                        showAlert(data.message || 'Upload failed', 'error');
                    }
                })
                .catch(error => showAlert('Error uploading avatar', 'error'));
            }
        });
    }
});

// Edit Profile Form
document.getElementById('editProfileForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/?controller=social&action=updateProfile', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Profile updated successfully!', 'success');
            closeModal('editProfileModal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert(data.message || 'Failed to update profile', 'error');
        }
    })
    .catch(error => showAlert('Error updating profile', 'error'));
});

// Change Password Form
document.getElementById('changePasswordForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const newPass = document.getElementById('newPassword').value;
    const confirmPass = document.getElementById('confirmPassword').value;
    
    if (newPass !== confirmPass) {
        showAlert('Passwords do not match!', 'warning');
        return;
    }
    
    const formData = new FormData(this);
    
    fetch('/?controller=social&action=changePassword', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Password changed successfully!', 'success');
            closeModal('changePasswordModal');
            this.reset();
        } else {
            showAlert(data.message || 'Failed to change password', 'error');
        }
    })
    .catch(error => showAlert('Error changing password', 'error'));
});

function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '10000';
    alertDiv.style.maxWidth = '400px';
    alertDiv.textContent = message;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => alertDiv.remove(), 300);
    }, 4000);
}
</script>




