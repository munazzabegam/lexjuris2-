<?php
// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-menu">
        <a href="dashboard.php" class="<?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i> <span class="menu-text">Dashboard</span>
        </a>
        <a href="cases.php" class="<?php echo $current_page === 'cases.php' ? 'active' : ''; ?>">
            <i class="fas fa-gavel"></i> <span class="menu-text">Cases</span>
        </a>
        <a href="articles.php" class="<?php echo $current_page === 'articles.php' ? 'active' : ''; ?>">
            <i class="fas fa-newspaper"></i> <span class="menu-text">Articles</span>
        </a>
        <a href="faqs.php" class="<?php echo $current_page === 'faqs.php' ? 'active' : ''; ?>">
            <i class="fas fa-question-circle"></i> <span class="menu-text">FAQs</span>
        </a>
        <a href="users.php" class="<?php echo $current_page === 'users.php' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i> <span class="menu-text">Users</span>
        </a>
        <a href="testimonials.php" class="<?php echo $current_page === 'testimonials.php' ? 'active' : ''; ?>">
            <i class="fas fa-star"></i> <span class="menu-text">Testimonials</span>
        </a>
        <a href="social-media.php" class="<?php echo $current_page === 'social-media.php' ? 'active' : ''; ?>">
            <i class="fas fa-share-alt"></i> <span class="menu-text">Social Media</span>
        </a>
        <a href="team.php" class="<?php echo $current_page === 'team.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-tie"></i> <span class="menu-text">Team</span>
        </a>
        <a href="gallery.php" class="<?php echo $current_page === 'gallery.php' ? 'active' : ''; ?>">
            <i class="fas fa-images"></i> <span class="menu-text">Gallery</span>
        </a>
        <a href="contact.php" class="<?php echo $current_page === 'contact.php' ? 'active' : ''; ?>">
            <i class="fas fa-envelope"></i> <span class="menu-text">Contact</span>
        </a>
        <a href="visitor_records.php" class="<?php echo $current_page === 'visitor_records.php' ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i> <span class="menu-text">Visitor Records</span>
        </a>
    </div>
    <div class="sidebar-footer">
        <a href="logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span class="menu-text">Logout</span>
        </a>
    </div>
</div>

<!-- Mobile Menu Toggle -->
<button class="mobile-menu-toggle" id="mobileMenuToggle">
    <i class="fas fa-bars"></i>
</button>

<style>
/* Sidebar Styles */
.sidebar {
    width: var(--sidebar-width);
    height: calc(100vh - var(--top-navbar-height)); 
    position: fixed;
    left: 0;
    top: var(--top-navbar-height); 
    background: white;
    color: var(--text-color);
    padding: 0;
    transition: all 0.3s;
    box-shadow: var(--card-shadow); 
    z-index: 1001;
    display: flex;
    flex-direction: column;
    border-top-right-radius: 10px; 
    border-bottom-right-radius: 10px; 
    overflow-y: auto; 
}

.sidebar-header {
    padding: 1rem;
    background: white;
    display: flex; 
    align-items: center;
    gap: 0.75rem;
}

.sidebar-header .logo img {
    width: 40px; 
    height: 40px; 
    object-fit: contain;
}

.sidebar-header .logo-name h4 {
    margin: 0;
    padding: 0;
    color: #bc8414;
    font-size: 1.8rem; 
    font-weight: 700;
    line-height: 1;
    display: inline-block;
}

.sidebar-header .logo-name h4 span {
    color: #000;
    font-weight: 700;
    margin: 0;
    padding: 0;
    display: inline-block;
}

.sidebar-menu a {
    color: var(--text-color);
    text-decoration: none;
    padding: 0.6rem 1rem;
    display: flex;
    align-items: center;
    transition: all 0.2s;
    font-weight: 500;
    font-size: 0.85rem;
    border-left: 2px solid transparent;
    margin: 0.1rem 0;
}

.sidebar-menu a:hover {
    color: var(--primary-color);
    background: rgba(67, 97, 238, 0.05);
    border-left: 2px solid var(--primary-color);
}

.sidebar-menu a.active {
    color: var(--primary-color);
    background: rgba(67, 97, 238, 0.05);
    border-left: 2px solid var(--primary-color);
}

.sidebar-menu i {
    margin-right: 0.75rem;
    width: 16px;
    text-align: center;
    font-size: 1rem;
}

.sidebar-footer {
    padding: 0.75rem;
    border-top: 1px solid rgba(0,0,0,0.05);
}

.logout-btn {
    display: flex;
    align-items: center;
    padding: 0.5rem 0.75rem;
    color: #dc3545;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.2s;
    font-weight: 500;
    font-size: 0.85rem;
}

.logout-btn:hover {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.logout-btn i {
    margin-right: 0.75rem;
    font-size: 1rem;
}

/* Mobile Menu Toggle */
.mobile-menu-toggle {
    display: none; 
    position: fixed;
    top: 0.75rem;
    left: 0.75rem;
    z-index: 1001;
    background: var(--gradient-primary);
    color: white;
    border: none;
    padding: 0.4rem;
    border-radius: 6px;
    cursor: pointer;
    width: 32px;
    height: 32px;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(67, 97, 238, 0.2);
}

.mobile-menu-toggle i {
    font-size: 1rem;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        width: 240px;
        z-index: 1001;
        height: calc(100vh - var(--top-navbar-height));
        top: var(--top-navbar-height);
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .sidebar:not(.active) .sidebar-header { /* Hide header when sidebar is not active */
        display: none;
    }

    .sidebar.active .sidebar-header { /* Show header when sidebar is active */
        display: flex; /* Ensure flex on mobile when active */
        padding: 1rem;
        background: white;
        align-items: center;
        gap: 0.75rem; /* Gap between logo and name on mobile */
    }

    .sidebar-header .logo img {
        width: 30px; /* Adjust size for mobile */
        height: 30px;
    }

    .sidebar-header .logo-name h4 {
        font-size: 1.4rem; /* Adjust size for mobile */
    }

    .menu-text {
        display: none;
    }

    .sidebar.active .menu-text {
        display: inline;
    }

    .sidebar-menu a {
        justify-content: flex-start;
        padding: 0.75rem 1rem;
    }

    .sidebar-menu i {
        margin-right: 0.75rem;
        font-size: 1.1rem;
    }

    .logout-btn {
        justify-content: flex-start;
        padding: 0.75rem 1rem;
    }

    .logout-btn .menu-text {
        display: none;
    }

    .sidebar.active .logout-btn .menu-text {
        display: inline;
    }

    /* Ensure icons are always visible */
    .sidebar-menu a i,
    .logout-btn i {
        opacity: 1 !important;
        visibility: visible !important;
        display: inline-block !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    // The mobile menu toggle listener is now in topnavbar.php

    // Close sidebar when clicking outside on mobile (excluding top navbar and sidebar)
    document.addEventListener('click', function(event) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const topNavbar = document.querySelector('.top-navbar'); // Assuming topnavbar has this class
        const isClickInsideTopNavbar = topNavbar ? topNavbar.contains(event.target) : false;

        if (!isClickInsideSidebar && !isClickInsideTopNavbar && window.innerWidth <= 768) {
             sidebar.classList.remove('active');
        }
    });

    // Handle menu item clicks
    const menuItems = document.querySelectorAll('.sidebar-menu a');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // Close sidebar on mobile after clicking a menu item
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
            }
        });
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('active');
        }
    });
});
</script> 