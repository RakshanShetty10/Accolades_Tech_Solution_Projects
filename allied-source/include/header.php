<!-- KSAHC Portal Navigation Bar -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<header class="ksahc-header">
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/ksahc_logo.png" alt="KSAHC Logo" class="logo-img">
                <span class="logo-text d-none d-sm-inline">Karnataka State Allied & Healthcare Professions Council</span>
            </a>
            
            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" 
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            
            <!-- Navigation Content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <?php if(!empty($_SESSION['_id'])) { ?>
                <!-- Main Navigation -->
                <!-- <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'welcome.php') ? 'active' : ''; ?>" 
                           href="welcome.php">
                           <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>" 
                           href="profile.php">
                           <i class="fas fa-user"></i> My Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'receipts.php') ? 'active' : ''; ?>" 
                           href="receipts.php">
                           <i class="fas fa-receipt"></i> Receipts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'payments.php') ? 'active' : ''; ?>" 
                           href="payments.php">
                           <i class="fas fa-money-bill-wave"></i> Payments
                        </a>
                    </li>
                </ul> -->
                
                <!-- User Profile & Options -->
                <div class="navbar-nav ms-auto">
                    <?php
                        // Get user details
                        $user_id = $_SESSION['_id'];
                        $user_query = mysqli_query($conn, "SELECT practitioner_name, practitioner_title, practitioner_profile_image FROM practitioner WHERE practitioner_id = '$user_id'");
                        $user_data = mysqli_fetch_assoc($user_query);
                        $user_name = $user_data['practitioner_name'] ?? 'User';
                        $user_title = $user_data['practitioner_title'] ?? 'Dr.';
                        $display_name = $user_title . ' ' . $user_name;
                        $profile_image = $user_data['practitioner_profile_image'];
                    ?>
                    
                    <!-- User Dropdown -->
                    <div class="nav-item dropdown user-dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                <?php if (!empty($profile_image)) { ?>
                                    <img src="admin/images/dentist/<?php echo $profile_image; ?>" alt="<?php echo htmlspecialchars($display_name); ?>" class="profile-img">
                                <?php } else { ?>
                                    <i class="fas fa-user-circle"></i>
                                <?php } ?>
                            </div>
                            <span class="user-name d-none d-md-inline">
                                <?php echo htmlspecialchars(substr($display_name, 0, 15)); ?>
                                <?php if (strlen($display_name) > 15) echo '...'; ?>
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <div class="dropdown-header">
                                    <strong><?php echo htmlspecialchars($display_name); ?></strong>
                                    <p class="small text-muted mb-0">KSAHC Member</p>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="password-manager.php">
                                    <i class="fas fa-key"></i> Change Password
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item logout-btn" href="logout.php">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php } else { ?>
                <!-- Login Button for Non-authenticated Users -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="login.php" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                </ul>
                <?php } ?>
            </div>
        </div>
    </nav>
</header>

<style>
:root {
    --primary: #192a56; /* Deep navy from logo */
    --primary-light: #273c75; /* Lighter navy */
    --secondary: #7f8fa6; /* Silver gray from logo */
    --light: #f5f6fa;
    --white: #ffffff;
    --dark: #2c3e50;
    --border: #e5e9f2;
    --shadow: rgba(0,0,0,0.1);
    --header-height: 70px;
}

/* Header Styling */
.ksahc-header {
    margin-bottom: var(--header-height);
}

.navbar {
    background-color: var(--white);
    box-shadow: 0 2px 15px var(--shadow);
    padding: 0;
    height: var(--header-height);
}

/* Logo Styling */
.navbar-brand {
    display: flex;
    align-items: center;
    padding: 0;
}

.logo-img {
    height: 75px !important;
    width: inherit;
    margin-right: 10px;
}

.logo-text {
    font-weight: 600;
    font-size: 18px;
    color: var(--primary);
}

/* Navigation Links */
.navbar-nav .nav-link {
    color: var(--dark);
    font-weight: 500;
    padding: 25px 15px;
    transition: all 0.3s ease;
}

.navbar-nav .nav-link:hover {
    color: var(--primary);
}

.navbar-nav .nav-link.active {
    color: var(--primary);
    border-bottom: 3px solid var(--primary);
}

.navbar-nav .nav-link i {
    margin-right: 5px;
}

/* User Avatar and Dropdown */
.user-dropdown .nav-link {
    display: flex;
    align-items: center;
    padding: 0 15px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: var(--primary);
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    overflow: hidden;
    border: 2px solid var(--light);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.user-avatar .profile-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.user-avatar i {
    font-size: 24px;
}

.user-name {
    margin-left: 8px;
    font-weight: 500;
}

/* Dropdown Menu Styling */
.dropdown-menu {
    border: none;
    box-shadow: 0 5px 15px var(--shadow);
    border-radius: 8px;
    padding: 0;
    min-width: 220px;
    margin-top: 10px;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.dropdown-header {
    background-color: var(--light);
    padding: 12px 15px;
    border-radius: 8px 8px 0 0;
}

.dropdown-item {
    padding: 10px 20px;
    color: var(--dark);
    font-weight: 400;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: rgba(25, 42, 86, 0.05);
    color: var(--primary);
    transform: translateX(3px);
}

.dropdown-item i {
    width: 20px;
    margin-right: 10px;
    color: var(--primary);
}

.logout-btn {
    color: #e74c3c !important;
}

.logout-btn i {
    color: #e74c3c !important;
}

.logout-btn:hover {
    background-color: rgba(231, 76, 60, 0.1) !important;
}

.dropdown-divider {
    margin: 0;
    border-top-color: var(--border);
}

/* Button Styling */
.btn-outline-primary {
    color: var(--primary);
    border-color: var(--primary);
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background-color: var(--primary);
    color: var(--white);
}

.btn-primary {
    background-color: var(--primary);
    border-color: var(--primary);
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: var(--primary-light);
    border-color: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(25, 42, 86, 0.2);
}

/* Mobile Toggle Button */
.navbar-toggler {
    border: none;
    padding: 0;
    font-size: 22px;
    color: var(--primary);
    outline: none !important;
    box-shadow: none !important;
}

/* Responsive Adjustments */
@media (max-width: 991.98px) {
    .navbar-collapse {
        background-color: var(--white);
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 10px 30px var(--shadow);
        position: absolute;
        top: var(--header-height);
        left: 0;
        right: 0;
        z-index: 1000;
    }
    
    .navbar-nav .nav-link {
        padding: 12px 15px;
    }
    
    .navbar-nav .nav-link.active {
        border-bottom: none;
        background-color: rgba(25, 42, 86, 0.05);
        border-radius: 5px;
    }
    
    /* Add margin to space out elements in mobile view */
    .navbar-nav {
        margin-bottom: 10px;
    }
}

/* Body adjustment for fixed header */
body {
    padding-top: var(--header-height);
}
</style>

<!-- Include Bootstrap JS and Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<!-- Professional navbar toggle functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get the navbar toggler button
    const navbarToggler = document.querySelector('.navbar-toggler');
    // Get the hamburger icon
    const hamburgerIcon = navbarToggler.querySelector('i');
    // Get the collapsible navbar
    const navbarCollapse = document.getElementById('navbarContent');
    
    // Create a Bootstrap collapse instance for manual control
    const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
        toggle: false
    });
    
    // Track if navbar is open
    let isNavbarOpen = false;
    
    // Handle toggler click
    navbarToggler.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (!isNavbarOpen) {
            // Open the navbar
            bsCollapse.show();
            hamburgerIcon.classList.remove('fa-bars');
            hamburgerIcon.classList.add('fa-times');
            isNavbarOpen = true;
        } else {
            // Close the navbar
            bsCollapse.hide();
            hamburgerIcon.classList.remove('fa-times');
            hamburgerIcon.classList.add('fa-bars');
            isNavbarOpen = false;
        }
    });
    
    // Update state based on bootstrap events
    navbarCollapse.addEventListener('hidden.bs.collapse', function() {
        hamburgerIcon.classList.remove('fa-times');
        hamburgerIcon.classList.add('fa-bars');
        isNavbarOpen = false;
    });
    
    navbarCollapse.addEventListener('shown.bs.collapse', function() {
        hamburgerIcon.classList.remove('fa-bars');
        hamburgerIcon.classList.add('fa-times');
        isNavbarOpen = true;
    });
    
    // Close navbar when clicking nav links in mobile
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link:not(.dropdown-toggle)');
    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth < 992 && isNavbarOpen) {
                bsCollapse.hide();
            }
        });
    });
    
    // Handle profile dropdown separately
    const userDropdown = document.querySelector('.user-dropdown .dropdown-toggle');
    if (userDropdown) {
        userDropdown.addEventListener('click', function(e) {
            // Stop event propagation to prevent navbar collapse conflicts
            e.stopPropagation();
        });
    }
    
    // Add styles for the toggle animation
    const style = document.createElement('style');
    style.textContent = `
        .navbar-toggler {
            z-index: 100;
        }
        
        .navbar-toggler i {
            transition: all 0.25s ease;
        }
        
        .navbar-toggler i.fa-times {
            color: #e74c3c;
            transform: rotate(180deg);
        }
        
        .navbar-collapse {
            transition: all 0.3s ease !important;
        }
        
        .navbar-collapse.show {
            animation: slideDown 0.3s ease forwards;
        }
        
        .navbar-collapse.collapsing {
            animation: slideUp 0.2s ease forwards;
        }
        
        @keyframes slideDown {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideUp {
            0% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-10px); }
        }
        
        @media (max-width: 991.98px) {
            .user-dropdown .dropdown-menu {
                position: static !important;
                float: none;
                width: 100%;
                margin-top: 10px;
            }
            
            /* Ensure proper animation in mobile view */
            .collapse:not(.show) {
                display: none !important;
            }
            
            .navbar-collapse.collapsing {
                height: 0 !important;
                overflow: hidden;
                transition: height 0.35s ease !important;
            }
            
            /* Fix for mobile layout */
            .navbar-collapse.show {
                position: absolute;
                top: var(--header-height);
                left: 0;
                right: 0;
                z-index: 1000;
            }
        }
    `;
    document.head.appendChild(style);
});
</script>
