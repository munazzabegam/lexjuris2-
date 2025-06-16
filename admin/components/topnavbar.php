<?php
// Get current user data from session
$username = $_SESSION['username'] ?? 'Admin';
$profileImage = $_SESSION['profile_image'] ?? null;
?>
<!-- Top Navbar -->
<div class="top-navbar">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- Left side: Mobile Menu Toggle and Logo/Name -->
        <div class="navbar-left d-flex align-items-center">
            <button class="mobile-menu-toggle btn btn-link p-0 d-md-none">
                <i class="fas fa-bars"></i>
            </button>
            <!-- Logo and Name -->
            <div class="navbar-brand-top d-flex align-items-center">
                <div class="logo me-2">
                    <img src="../../image/logo.png" alt="Lex Juris Logo" width="30" height="30">
                </div>
                <div class="logo-name">
                    <h4>Lex<span>Juris</span></h4>
                </div>
            </div>
        </div>

        <!-- Nav Icons -->
        <div class="nav-icons d-flex align-items-center">
            <div class="user-avatar">
                <?php if ($profileImage): ?>
                    <img src="../../<?php echo htmlspecialchars($profileImage); ?>" 
                         class="rounded-circle" width="32" height="32" alt="User Profile Image">
                <?php else: ?>
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($username); ?>&background=bc8414&color=fff" 
                         class="rounded-circle" width="32" height="32" alt="User Avatar">
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.top-navbar {
    background: white;
    padding: 0.75rem 1.5rem;
    box-shadow: var(--card-shadow);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1002;
}

.top-navbar .navbar-left {
    display: flex;
    align-items: center;
    gap: 3.5rem; 
}

.top-navbar .navbar-left .mobile-menu-toggle {
    margin-bottom: 1rem !important;
}

.top-navbar .navbar-brand-top {
    display: flex;
    align-items: center;
    gap: 0.75rem; 
}

.top-navbar .navbar-brand-top .logo img {
    width: 35px; 
    height: 35px;
    margin-left: 1rem;
}

.top-navbar .navbar-brand-top .logo-name h4 {
    margin: 0;
    padding: 0;
    color: #bc8414;
    font-size: 1.8rem; 
    font-weight: 700;
    line-height: 1;
}

.top-navbar .navbar-brand-top .logo-name h4 span {
    color: #000;
    font-weight: 700;
    margin: 0;
    padding: 0;
}

.top-navbar .nav-icons {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.top-navbar .user-avatar img {
    width: 32px;
    height: 32px;
    object-fit: cover;
}

/* Mobile styles */
@media (max-width: 768px) {
    .top-navbar {
        padding: 0.5rem 1rem;
    }

    .top-navbar .container-fluid {
        padding: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .top-navbar .navbar-left {
        display: flex;
        align-items: center;
        gap: 0.5rem; 
    }

    .top-navbar .navbar-brand-top {
        display: flex !important;
        align-items: center;
    }

    .top-navbar .navbar-brand-top .logo img {
        width: 30px;
        height: 30px;
    }

    .top-navbar .navbar-brand-top .logo-name h4 {
        font-size: 1.4rem;
    }

    .top-navbar .mobile-menu-toggle {
        display: flex !important;
        color: var(--text-color);
        font-size: 1.2rem;
        width: 36px;
        height: 36px;
        align-items: center;
        justify-content: center;
        padding: 0 !important;
        margin: 0 !important;
        background: none;
        border: none;
        position: relative;
        z-index: 1003;
    }

    .top-navbar .nav-icons {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        z-index: 1003;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const mobileMenuToggle = document.querySelector('.top-navbar .mobile-menu-toggle');
    
    // Mobile menu toggle
    if(mobileMenuToggle) {
       mobileMenuToggle.addEventListener('click', function() {
           sidebar.classList.toggle('active');
       });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const topNavbar = document.querySelector('.top-navbar');
        const isClickInsideTopNavbar = topNavbar ? topNavbar.contains(event.target) : false;

        if (!isClickInsideSidebar && !isClickInsideTopNavbar && window.innerWidth <= 768) {
             sidebar.classList.remove('active');
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('active');
        }
    });
});
</script> 