// Profile Widget - Universal User Profile Button
(function() {
    'use strict';
    
    // Check if user is logged in
    function isLoggedIn() {
        const user = sessionStorage.getItem('ctfUser');
        return user !== null && user !== '';
    }

    // Get user data
    function getUserData() {
        const username = sessionStorage.getItem('ctfUser');
        if (!username) return null;

        const registeredUsers = JSON.parse(localStorage.getItem('registeredUsers') || '{}');
        const ctfUsers = JSON.parse(localStorage.getItem('ctfUsers') || '{}');
        
        const userInfo = registeredUsers[username] || {};
        const userData = ctfUsers[username] || { solved: [], points: 0 };

        return {
            username: username,
            name: userInfo.name || username,
            email: userInfo.email || '',
            points: userData.points || 0,
            solved: userData.solved.length || 0,
            initial: (userInfo.name || username).charAt(0).toUpperCase()
        };
    }

    // Get base path (for relative URLs)
    function getBasePath() {
        const path = window.location.pathname;
        if (path.includes('/auth/')) return '../';
        if (path.includes('/pages/') || path.includes('/dashboard/') || path.includes('/challenges/')) return '../';
        return '';
    }

    // Create profile widget HTML
    function createProfileWidget(userData, basePath) {
        return `
            <div class="profile-widget">
                <button class="profile-btn" id="profileBtn" title="${userData.name}">
                    <span class="profile-avatar">${userData.initial}</span>
                    <span class="profile-name">${userData.name}</span>
                    <span class="profile-arrow">▼</span>
                </button>
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-dropdown-header">
                        <div class="profile-dropdown-avatar">${userData.initial}</div>
                        <div class="profile-dropdown-info">
                            <div class="profile-dropdown-name">${userData.name}</div>
                            <div class="profile-dropdown-username">@${userData.username}</div>
                        </div>
                    </div>
                    <div class="profile-dropdown-stats">
                        <div class="profile-stat">
                            <span class="profile-stat-value">${userData.points}</span>
                            <span class="profile-stat-label">Points</span>
                        </div>
                        <div class="profile-stat">
                            <span class="profile-stat-value">${userData.solved}</span>
                            <span class="profile-stat-label">Solved</span>
                        </div>
                    </div>
                    <div class="profile-dropdown-divider"></div>
                    <a href="${basePath}dashboard/user-dashboard.html" class="profile-dropdown-item">
                        <span class="profile-dropdown-icon">📊</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="${basePath}auth/profile.html" class="profile-dropdown-item">
                        <span class="profile-dropdown-icon">👤</span>
                        <span>My Profile</span>
                    </a>
                    <a href="${basePath}dashboard/leaderboard.html" class="profile-dropdown-item">
                        <span class="profile-dropdown-icon">🏆</span>
                        <span>Leaderboard</span>
                    </a>
                    <a href="${basePath}pages/challenges.html" class="profile-dropdown-item">
                        <span class="profile-dropdown-icon">🎯</span>
                        <span>Challenges</span>
                    </a>
                    <div class="profile-dropdown-divider"></div>
                    <a href="#" class="profile-dropdown-item profile-dropdown-logout" id="logoutBtn">
                        <span class="profile-dropdown-icon">🚪</span>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        `;
    }

    // Initialize profile widget
    function initProfileWidget() {
        if (!isLoggedIn()) return;

        const authLinks = document.querySelector('.auth-links');
        if (!authLinks) return;

        const userData = getUserData();
        if (!userData) return;

        const basePath = getBasePath();
        
        // Replace auth links with profile widget
        authLinks.innerHTML = createProfileWidget(userData, basePath);
        authLinks.style.display = 'flex';
        authLinks.style.alignItems = 'center';

        // Add event listeners
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');
        const logoutBtn = document.getElementById('logoutBtn');

        if (profileBtn && profileDropdown) {
            // Toggle dropdown
            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.remove('show');
                }
            });
        }

        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to logout?')) {
                    sessionStorage.removeItem('ctfUser');
                    window.location.href = basePath + 'auth/login.html';
                }
            });
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProfileWidget);
    } else {
        initProfileWidget();
    }
})();
