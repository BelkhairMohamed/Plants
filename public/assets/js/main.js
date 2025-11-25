/**
 * Main JavaScript file for Plants Management System
 */

// Auto-refresh notifications count
function refreshNotificationCount() {
    if (typeof BASE_URL !== 'undefined') {
        fetch(BASE_URL + '/?controller=notification&action=getUnreadCount')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.badge');
                if (data.count > 0) {
                    if (badge) {
                        badge.textContent = data.count;
                    } else {
                        // Create badge if it doesn't exist
                        const notificationLink = document.querySelector('.notification-link');
                        if (notificationLink) {
                            const newBadge = document.createElement('span');
                            newBadge.className = 'badge';
                            newBadge.textContent = data.count;
                            notificationLink.appendChild(newBadge);
                        }
                    }
                } else if (badge) {
                    badge.remove();
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }
}

// Refresh notifications every 30 seconds
if (document.querySelector('.notification-link')) {
    setInterval(refreshNotificationCount, 30000);
    refreshNotificationCount(); // Initial load
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    // Add basic form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#dc3545';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs requis.');
            }
        });
    });
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Hamburger menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });
        
        // Close menu when clicking on a link
        const navLinks = navMenu.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideNav = navMenu.contains(event.target);
            const isClickOnHamburger = hamburger.contains(event.target);
            
            if (!isClickInsideNav && !isClickOnHamburger && navMenu.classList.contains('active')) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            }
        });
    }
});

// AJAX Like Button Handler
document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.querySelectorAll('.like-btn');
    
    likeButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const postId = this.dataset.postId;
            if (!postId) return;
            
            const likeCount = this.querySelector('.like-count');
            const button = this;
            
            fetch('/?controller=social&action=like', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'post_id=' + postId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.classList.toggle('active');
                    if (data.liked) {
                        button.classList.add('liked');
                    } else {
                        button.classList.remove('liked');
                    }
                    if (likeCount) {
                        likeCount.textContent = data.like_count;
                    }
                }
            })
            .catch(error => console.error('Like error:', error));
        });
    });
});



