/**
 * Main JavaScript file for Plants Management System
 */

// Dark Mode Toggle Handler
(function() {
    function initDarkMode() {
        const darkModeToggle = document.getElementById('dark-mode-switch');
        const html = document.documentElement;
        
        if (!darkModeToggle) {
            // Retry if element not found yet
            setTimeout(initDarkMode, 50);
            return;
        }
        
        // Check for saved theme preference
        const currentTheme = localStorage.getItem('theme') || 'light';
        
        // Set initial state of toggle
        darkModeToggle.checked = (currentTheme === 'dark');
        
        // Ensure theme is applied to html element
        if (currentTheme === 'dark') {
            html.setAttribute('data-theme', 'dark');
        } else {
            html.removeAttribute('data-theme');
        }
        
        // Toggle theme when switch is clicked
        darkModeToggle.addEventListener('change', function(e) {
            e.stopPropagation();
            if (this.checked) {
                html.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            } else {
                html.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
            }
        });
    }
    
    // Initialize immediately if DOM is ready, otherwise wait
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDarkMode);
    } else {
        // DOM is already ready
        initDarkMode();
    }
})();

// Auto-refresh notifications count
function refreshNotificationCount() {
    if (typeof BASE_URL !== 'undefined') {
        fetch(BASE_URL + '/?controller=notification&action=getUnreadCount')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notification-badge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline-flex';
                    } else {
                        badge.style.display = 'none';
                    }
                } else {
                    // Create badge if it doesn't exist
                    const notificationLink = document.querySelector('.notification-link');
                    if (notificationLink && data.count > 0) {
                        const newBadge = document.createElement('span');
                        newBadge.id = 'notification-badge';
                        newBadge.className = 'badge notification-badge';
                        newBadge.textContent = data.count;
                        newBadge.style.display = 'inline-flex';
                        notificationLink.appendChild(newBadge);
                    }
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

// Form validation - but exclude like forms and comment forms (they have their own handlers)
document.addEventListener('DOMContentLoaded', function() {
    // Add basic form validation (but exclude forms with special handlers)
    const forms = document.querySelectorAll('form:not([action*="action=like"]):not([action*="action=comment"])');
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

// Smooth scroll for anchor links (both internal and with hash)
document.querySelectorAll('a[href*="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        // Only handle if it's an anchor link (starts with # or contains #)
        if (href && href.includes('#') && (href.startsWith('#') || href.includes('#comments-section'))) {
            const hash = href.split('#')[1];
            if (hash) {
                // If it's a full URL with hash, navigate first then scroll
                if (href.startsWith('http') || href.startsWith('/')) {
                    // Let the navigation happen, then scroll after page load
                    const targetId = hash;
                    setTimeout(() => {
                        const target = document.getElementById(targetId);
                        if (target) {
                            target.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }, 100);
                    return true; // Allow navigation
                } else {
                    // Internal anchor link
                    e.preventDefault();
                    const target = document.querySelector('#' + hash);
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            }
        }
    });
});

// Navbar scroll effect
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    let lastScroll = 0;
    
    if (navbar) {
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            lastScroll = currentScroll;
        });
    }
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
    
    // AJAX Like functionality
    const likeForms = document.querySelectorAll('form[action*="action=like"]');
    console.log('Found like forms:', likeForms.length);
    
    likeForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = form.querySelector('.btn-like');
            if (!button) {
                console.error('Like button not found');
                form.submit(); // Fallback
                return;
            }
            
            const formData = new FormData(form);
            const postId = formData.get('post_id');
            
            if (!postId) {
                console.error('Post ID missing');
                form.submit(); // Fallback
                return;
            }
            
            console.log('Liking post:', postId);
            
            // Add loading state
            const originalHTML = button.innerHTML;
            button.classList.add('loading');
            button.disabled = true;
            
            // Get the action URL - use form.action as is (it should already have BASE_URL)
            const actionUrl = form.action;
            console.log('Action URL:', actionUrl);
            
            // Create AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', actionUrl, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
            xhr.onload = function() {
                button.classList.remove('loading');
                button.disabled = false;
                
                console.log('Response status:', xhr.status);
                console.log('Response text:', xhr.responseText);
                
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log('Parsed response:', response);
                        
                        if (response.success) {
                            // Update button state
                            if (response.liked) {
                                button.classList.add('liked');
                                button.innerHTML = '❤️ ' + response.likeCount;
                            } else {
                                button.classList.remove('liked');
                                button.innerHTML = '🤍 ' + response.likeCount;
                            }
                            
                            // Add animation
                            button.style.animation = 'none';
                            setTimeout(() => {
                                button.style.animation = 'heartBeat 0.6s ease-in-out';
                            }, 10);
                            
                            // Refresh notification count if available
                            if (typeof refreshNotificationCount === 'function') {
                                refreshNotificationCount();
                            }
                        } else {
                            console.error('Like failed:', response);
                            // Fallback to form submission
                            form.submit();
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e, xhr.responseText);
                        // If response is HTML (redirect), just reload
                        if (xhr.responseText.includes('<!DOCTYPE') || xhr.responseText.includes('<html')) {
                            window.location.reload();
                        } else {
                            // Fallback to form submission
                            form.submit();
                        }
                    }
                } else if (xhr.status === 302 || xhr.status === 301 || xhr.status === 0) {
                    // Redirect response or CORS issue - reload page
                    console.log('Redirect detected, reloading page');
                    window.location.reload();
                } else {
                    console.error('Request failed with status:', xhr.status);
                    // Fallback to form submission
                    form.submit();
                }
            };
            
            xhr.onerror = function() {
                console.error('Network error');
                button.classList.remove('loading');
                button.disabled = false;
                button.innerHTML = originalHTML;
                // Fallback to form submission
                form.submit();
            };
            
            xhr.send(formData);
        });
    });
    
    // Comment form - allow normal submission but add visual feedback
    const commentForms = document.querySelectorAll('form[action*="action=comment"]');
    console.log('Found comment forms:', commentForms.length);
    
    commentForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Don't prevent default - let form submit normally
            const submitButton = form.querySelector('button[type="submit"]');
            const textarea = form.querySelector('textarea[name="content"]');
            
            // Validate content
            if (textarea && !textarea.value.trim()) {
                e.preventDefault();
                alert('Veuillez écrire un commentaire.');
                textarea.focus();
                return false;
            }
            
            if (submitButton) {
                submitButton.disabled = true;
                const originalText = submitButton.textContent;
                submitButton.textContent = 'Publication...';
                submitButton.classList.add('loading');
                
                // Re-enable after 5 seconds in case of error
                setTimeout(() => {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                    submitButton.classList.remove('loading');
                }, 5000);
            }
            
            // Allow form to submit normally
            return true;
        });
    });
});

// Refresh cart count
function refreshCartCount() {
    if (typeof BASE_URL !== 'undefined') {
        fetch(BASE_URL + '/?controller=marketplace&action=getCartCount')
            .then(response => response.json())
            .then(data => {
                const cartBadge = document.getElementById('cart-badge');
                if (cartBadge) {
                    if (data.count > 0) {
                        cartBadge.textContent = data.count;
                        cartBadge.style.display = 'inline-flex';
                    } else {
                        cartBadge.style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error fetching cart count:', error));
    }
}

// Refresh cart count on page load and after cart operations
document.addEventListener('DOMContentLoaded', function() {
    // Initial refresh
    refreshCartCount();
    
    // Refresh after form submissions (like add to cart)
    const forms = document.querySelectorAll('form[action*="action=addToCart"]');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            // Refresh after a short delay to allow server processing
            setTimeout(refreshCartCount, 500);
        });
    });
    
    // Refresh cart count every 5 seconds to keep it updated
    setInterval(refreshCartCount, 5000);
});

// User Search Sidebar Functionality
(function() {
    let searchTimeout;
    let currentSearchQuery = '';
    
    function initUserSearch() {
        const searchToggleBtn = document.getElementById('search-toggle-btn');
        const searchSidebar = document.getElementById('search-sidebar');
        const searchSidebarClose = document.getElementById('search-sidebar-close');
        const searchSidebarOverlay = document.getElementById('search-sidebar-overlay');
        const searchInput = document.getElementById('user-search-input');
        const searchClearBtn = document.getElementById('search-clear-btn');
        const searchResults = document.getElementById('search-results');
        
        if (!searchToggleBtn || !searchSidebar) {
            return; // Search feature not available (user not logged in)
        }
        
        // Toggle sidebar
        function openSidebar() {
            searchSidebar.classList.add('active');
            searchSidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            // Focus search input after animation
            setTimeout(() => {
                if (searchInput) searchInput.focus();
            }, 300);
        }
        
        function closeSidebar() {
            searchSidebar.classList.remove('active');
            searchSidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
            if (searchInput) {
                searchInput.value = '';
                currentSearchQuery = '';
            }
            showPlaceholder();
        }
        
        // Event listeners
        searchToggleBtn.addEventListener('click', openSidebar);
        searchSidebarClose.addEventListener('click', closeSidebar);
        searchSidebarOverlay.addEventListener('click', closeSidebar);
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchSidebar.classList.contains('active')) {
                closeSidebar();
            }
        });
        
        // Search input handling
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.trim();
                
                // Show/hide clear button
                if (searchClearBtn) {
                    searchClearBtn.style.display = query ? 'flex' : 'none';
                }
                
                // Clear previous timeout
                clearTimeout(searchTimeout);
                
                // If query is too short, show placeholder
                if (query.length < 2) {
                    currentSearchQuery = '';
                    showPlaceholder();
                    return;
                }
                
                // Debounce search
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });
            
            // Clear button
            if (searchClearBtn) {
                searchClearBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    currentSearchQuery = '';
                    searchClearBtn.style.display = 'none';
                    showPlaceholder();
                    searchInput.focus();
                });
            }
        }
        
        // Perform search
        function performSearch(query) {
            if (query === currentSearchQuery) return;
            currentSearchQuery = query;
            
            // Show loading
            searchResults.innerHTML = `
                <div class="search-loading">
                    <i class="fas fa-spinner"></i>
                    <p>Recherche en cours...</p>
                </div>
            `;
            
            // Make AJAX request
            if (typeof BASE_URL === 'undefined') {
                console.error('BASE_URL not defined');
                return;
            }
            
            fetch(`${BASE_URL}/?controller=social&action=searchUsers&q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.users) {
                    displaySearchResults(data.users);
                } else {
                    showNoResults();
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                searchResults.innerHTML = `
                    <div class="search-no-results">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Erreur lors de la recherche</p>
                    </div>
                `;
            });
        }
        
        // Display search results
        function displaySearchResults(users) {
            if (users.length === 0) {
                showNoResults();
                return;
            }
            
            const resultsHTML = users.map(user => {
                const avatarUrl = user.avatar_url || 'https://via.placeholder.com/150';
                const bio = user.bio ? `<p class="search-result-bio">${escapeHtml(user.bio)}</p>` : '';
                const followBtnClass = user.isFollowing ? 'unfollow' : 'follow';
                const followBtnText = user.isFollowing ? 'Ne plus suivre' : 'Suivre';
                
                return `
                    <div class="search-result-item" onclick="window.location.href='${BASE_URL}/?controller=social&action=profile&user_id=${user.id}'">
                        <img src="${escapeHtml(avatarUrl)}" alt="${escapeHtml(user.username)}" class="search-result-avatar">
                        <div class="search-result-info">
                            <h3 class="search-result-username">${escapeHtml(user.username)}</h3>
                            ${bio}
                        </div>
                        <div class="search-result-actions" onclick="event.stopPropagation();">
                            <button class="search-follow-btn ${followBtnClass}" 
                                    data-user-id="${user.id}" 
                                    data-is-following="${user.isFollowing ? '1' : '0'}">
                                ${followBtnText}
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
            
            searchResults.innerHTML = resultsHTML;
            
            // Attach follow button handlers
            attachFollowHandlers();
        }
        
        // Show placeholder
        function showPlaceholder() {
            searchResults.innerHTML = `
                <div class="search-placeholder">
                    <i class="fas fa-user-friends"></i>
                    <p>Tapez au moins 2 caractères pour rechercher</p>
                </div>
            `;
        }
        
        // Show no results
        function showNoResults() {
            searchResults.innerHTML = `
                <div class="search-no-results">
                    <i class="fas fa-search"></i>
                    <p>Aucun utilisateur trouvé</p>
                </div>
            `;
        }
        
        // Attach follow button handlers
        function attachFollowHandlers() {
            const followButtons = searchResults.querySelectorAll('.search-follow-btn');
            followButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const userId = parseInt(this.getAttribute('data-user-id'));
                    const isFollowing = this.getAttribute('data-is-following') === '1';
                    
                    if (!userId) return;
                    
                    // Disable button during request
                    const originalText = this.textContent;
                    this.disabled = true;
                    this.textContent = '...';
                    
                    // Make follow/unfollow request
                    const formData = new FormData();
                    formData.append('user_id', userId);
                    formData.append('redirect', window.location.href);
                    
                    fetch(`${BASE_URL}/?controller=social&action=follow`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update button state
                            const newIsFollowing = data.isFollowing;
                            this.setAttribute('data-is-following', newIsFollowing ? '1' : '0');
                            this.className = `search-follow-btn ${newIsFollowing ? 'unfollow' : 'follow'}`;
                            this.textContent = newIsFollowing ? 'Ne plus suivre' : 'Suivre';
                            
                            // Update followers count if displayed
                            // Could update a counter if we show it in search results
                        }
                        this.disabled = false;
                    })
                    .catch(error => {
                        console.error('Follow error:', error);
                        this.disabled = false;
                        this.textContent = originalText;
                    });
                });
            });
        }
        
        // Helper function to escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initUserSearch);
    } else {
        initUserSearch();
    }
})();

// Sub-header hamburger menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const subHamburger = document.getElementById('navbar-sub-hamburger');
    const subMenu = document.getElementById('navbar-sub-menu');
    const subOverlay = document.getElementById('navbar-sub-overlay');
    const subMenuClose = document.getElementById('navbar-sub-menu-close');
    
    if (subHamburger && subMenu) {
        function openMenu() {
            subHamburger.classList.add('active');
            subMenu.classList.add('active');
            if (subOverlay) {
                subOverlay.classList.add('active');
            }
            document.body.style.overflow = 'hidden';
        }
        
        function closeMenu() {
            subHamburger.classList.remove('active');
            subMenu.classList.remove('active');
            if (subOverlay) {
                subOverlay.classList.remove('active');
            }
            document.body.style.overflow = '';
        }
        
        subHamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            if (subMenu.classList.contains('active')) {
                closeMenu();
            } else {
                openMenu();
            }
        });
        
        // Close menu button
        if (subMenuClose) {
            subMenuClose.addEventListener('click', function(e) {
                e.stopPropagation();
                closeMenu();
            });
        }
        
        // Close menu when clicking on overlay
        if (subOverlay) {
            subOverlay.addEventListener('click', function() {
                closeMenu();
            });
        }
        
        // Close menu when clicking on a link
        const subLinks = subMenu.querySelectorAll('a');
        subLinks.forEach(link => {
            link.addEventListener('click', function() {
                closeMenu();
            });
        });
        
        // Close menu on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && subMenu.classList.contains('active')) {
                closeMenu();
            }
        });
    }
});


