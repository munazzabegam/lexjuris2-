<?php
// Get current user data from database
$user_id = $_SESSION['admin_id'] ?? null;
$username = $_SESSION['admin_username'] ?? 'Admin';
$profile_image = null;

if ($user_id) {
    $stmt = $conn->prepare("SELECT profile_image FROM admin_users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $profile_image = $user['profile_image'];
    }
    $stmt->close();
}
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
                    <img src="../image/logo.png" alt="Lex Juris Logo" width="30" height="30">
                </div>
                <div class="logo-name">
                    <h4>Lex<span>Juris</span></h4>
                </div>
            </div>
        </div>

        <!-- Nav Icons -->
        <div class="nav-icons d-flex align-items-center">
            <div class="dropdown">
                <?php if ($profile_image): ?>
                    <img src="/lexjuris/<?php echo htmlspecialchars($profile_image); ?>" 
                         class="rounded-circle" width="32" height="32" 
                         style="cursor: pointer; object-fit: cover;" data-bs-toggle="dropdown">
                <?php else: ?>
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($username); ?>&background=bc8414&color=fff" 
                         class="rounded-circle" width="32" height="32" 
                         style="cursor: pointer;" data-bs-toggle="dropdown">
                <?php endif; ?>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                </ul>
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

.top-navbar .nav-icons .icon {
    position: relative;
    cursor: pointer;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.2s;
}

.top-navbar .nav-icons .icon:hover {
    background: rgba(67, 97, 238, 0.1);
}

.top-navbar .nav-icons .icon i {
    font-size: 1rem;
    color: #666;
}

.top-navbar .nav-icons .badge {
    position: absolute;
    top: -4px;
    right: -4px;
    padding: 0.15rem 0.35rem;
    border-radius: 10px;
    background: var(--warning-color);
    color: white;
    font-size: 0.7rem;
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

     /* Adjust display for logo/name on mobile */
    .top-navbar .navbar-brand-top {
        display: flex !important;
        align-items: center;
        /* gap: 0.75rem; */
    }

    .top-navbar .navbar-brand-top .logo img {
        width: 30px; /* Adjust size for mobile */
        height: 30px;
    }

    .top-navbar .navbar-brand-top .logo-name h4 {
        font-size: 1.4rem; /* Adjust size for mobile */
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

    .top-navbar .nav-icons .dropdown img {
        width: 32px;
        height: 32px;
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