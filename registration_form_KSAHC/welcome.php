<?php
// Start session
session_start();

// Database connection
require_once 'config/config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Get user data
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$user_name = $_SESSION['user_name'] ?? 'Practitioner';

// Fetch practitioner information with profile image
$practitioner_sql = "SELECT p.*, rt.registration_type 
                    FROM practitioner p
                    LEFT JOIN registration_type_master rt ON p.registration_type_id = rt.registration_type_id
                    WHERE p.practitioner_id = ?";
$practitioner_stmt = $conn->prepare($practitioner_sql);
$practitioner_stmt->bind_param("i", $user_id);
$practitioner_stmt->execute();
$practitioner_result = $practitioner_stmt->get_result();
$practitioner_data = $practitioner_result->fetch_assoc();

// Fetch current president information from past_president table
$president_sql = "SELECT * FROM past_president WHERE president_status = 'Active' ORDER BY president_id DESC LIMIT 1";
$president_result = $conn->query($president_sql);
$president_data = $president_result->fetch_assoc();

// Default president information if none found in database
if (!$president_data) {
    $president_data = [
        'president_name' => 'Dr. Rajesh Kumar',
        'president_from_date' => date('Y-m-d'),
        'president_to_date' => date('Y-m-d', strtotime('+1 year')),
        'president_image' => 'president.jpg'
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | KSAHC Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="css/navbar.css"> -->
    <style>
        :root {
            --primary-color: #2A5C9D;
            --secondary-color: #4A90E2;
            --accent-color: #33b679;
            --dark-color: #2C3E50;
            --light-color: #f8f9fc;
            --border-color: #e3e6f0;
            --gradient-start: #2A5C9D;
            --gradient-end: #1E3F75;
            --card-shadow: 0 10px 20px rgba(0,0,0,0.08);
            --hover-shadow: 0 15px 30px rgba(0,0,0,0.12);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F5F7FA;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            padding-top: 70px; /* Updated to match navbar height */
            color: #444;
        }

        /* Preloader Styles */
        .preloader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s, visibility 0.5s;
        }

        .logo-container {
            position: relative;
            width: 200px;
            height: 200px;
        }

        .logo {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            animation: pulse 2s infinite alternate;
        }

        .spinner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 4px solid transparent;
            border-radius: 50%;
            border-top: 4px solid var(--primary-color);
            border-right: 4px solid var(--primary-color);
            animation: spin 1.5s linear infinite;
        }

        .spinner-inner {
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            bottom: 15px;
            border: 3px solid transparent;
            border-radius: 50%;
            border-top: 3px solid var(--secondary-color);
            border-left: 3px solid var(--secondary-color);
            animation: spin-reverse 1.2s linear infinite;
        }

        .loading-text {
            margin-top: 30px;
            color: var(--primary-color);
            font-size: 18px;
            letter-spacing: 3px;
            position: relative;
        }

        .loading-dots {
            display: inline-block;
            width: 30px;
            text-align: left;
        }

        .loading-dot {
            animation: dot-fade 1.5s infinite;
            animation-delay: calc(0.3s * var(--dot-index));
            opacity: 0;
        }

        .progress-bar {
            width: 250px;
            height: 6px;
            background: rgba(78, 115, 223, 0.2);
            border-radius: 3px;
            margin-top: 20px;
            overflow: hidden;
        }

        .progress {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 3px;
            animation: progress 3s ease-in-out infinite;
        }

        .wings-effect {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0) 70%);
            opacity: 0;
            animation: wings-glow 3s infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes spin-reverse {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(-360deg); }
        }

        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.7; }
            100% { transform: scale(1.05); opacity: 1; }
        }

        @keyframes dot-fade {
            0%, 100% { opacity: 0; }
            50% { opacity: 1; }
        }

        @keyframes progress {
            0% { width: 5%; }
            50% { width: 75%; }
            100% { width: 95%; }
        }

        @keyframes wings-glow {
            0%, 100% { opacity: 0; transform: translate(-50%, -50%) scale(0.8); }
            50% { opacity: 0.5; transform: translate(-50%, -50%) scale(1.2); }
        }

        /* Main Content Styles */
        .main-content {
            padding: 30px 0;
            flex: 1;
        }
        
        .main-title {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
        }
        
        /* Integrated Profile & Welcome Message Card */
        .profile-welcome-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            transition: box-shadow 0.3s ease;
        }
        
        .profile-welcome-card:hover {
            box-shadow: var(--hover-shadow);
        }
        
        .profile-welcome-header {
            background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
            color: white;
            padding: 18px 25px;
            position: relative;
        }
        
        .profile-welcome-header h4 {
            margin-bottom: 0;
            font-weight: 600;
        }
        
        .profile-welcome-body {
            padding: 0;
            display: flex;
            flex-direction: row;
        }
        
        .profile-section {
            padding: 25px;
            text-align: center;
            width: 30%;
            border-right: 1px solid rgba(0,0,0,0.05);
            background: rgba(242, 245, 250, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        
        .welcome-section {
            padding: 25px;
            width: 70%;
        }
        
        .practitioner-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            margin: 0 auto 20px;
            display: block;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .badge-active {
            background: var(--accent-color);
            color: white;
            padding: 6px 20px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 12px;
            letter-spacing: 0.5px;
            display: inline-block;
            margin-bottom: 20px;
            box-shadow: 0 3px 8px rgba(51, 182, 121, 0.15);
        }
        
        .btn-profile {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 500;
            color: white;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(74, 144, 226, 0.2);
            width: 100%;
            max-width: 180px;
        }
        
        .btn-profile:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(74, 144, 226, 0.3);
            color: white;
        }
        
        .welcome-message p {
            color: #555;
            line-height: 1.7;
            margin-bottom: 18px;
        }
        
        .president-info {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .president-info::before {
            content: '';
            position: absolute;
            top: -1px;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-color);
        }
        
        .president-image {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 3px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        
        .president-term {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
            padding: 4px 12px;
            background: rgba(242, 245, 250, 0.6);
            border-radius: 4px;
            display: inline-block;
        }
        
        /* Info Card */
        .info-card {
            border-radius: 12px;
            overflow: hidden;
            background: white;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            transition: box-shadow 0.3s ease;
        }
        
        .info-card:hover {
            box-shadow: var(--hover-shadow);
        }
        
        .info-card-header {
            background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
            color: white;
            padding: 18px 25px;
        }
        
        .info-card-header h5 {
            margin-bottom: 0;
            font-weight: 600;
        }
        
        .info-card-body {
            padding: 0;
        }
        
        .quick-links {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }
        
        .quick-links li {
            transition: all 0.2s ease;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .quick-links li:last-child {
            border-bottom: none;
        }
        
        .quick-links li a {
            color: #444;
            display: block;
            padding: 15px 20px;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .quick-links li a::after {
            content: "\f054";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            opacity: 0;
            color: var(--secondary-color);
            transition: all 0.2s ease;
        }
        
        .quick-links li:hover {
            background-color: rgba(74, 144, 226, 0.05);
        }
        
        .quick-links li:hover a {
            color: var(--primary-color);
            padding-left: 25px;
            text-decoration: none;
        }
        
        .quick-links li:hover a::after {
            opacity: 1;
            right: 15px;
        }
        
        .quick-links li a i {
            margin-right: 12px;
            color: var(--primary-color);
            width: 20px;
            text-align: center;
            background: rgba(42, 92, 157, 0.1);
            padding: 7px;
            border-radius: 5px;
            transition: all 0.2s ease;
        }
        
        .quick-links li:hover a i {
            background: rgba(42, 92, 157, 0.2);
        }

        /* Page header */
        .page-header {
            padding: 0 15px 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .page-header .main-title {
            margin-bottom: 0;
        }
        
        /* Media Queries */
        @media (max-width: 991.98px) {
            .profile-welcome-body {
                flex-direction: column;
            }
            
            .profile-section, .welcome-section {
                width: 100%;
            }
            
            .profile-section {
                border-right: none;
                border-bottom: 1px solid rgba(0,0,0,0.05);
                padding-bottom: 30px;
            }
        }
        
        @media (max-width: 767.98px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Preloader -->
    <div class="preloader-container">
        <div class="logo-container">
            <div class="spinner"></div>
            <div class="spinner-inner"></div>
            <div class="wings-effect"></div>
            <img src="ksahc_logo.png" alt="KSAHC Logo" class="logo">
        </div>
        <div class="progress-bar">
            <div class="progress"></div>
        </div>
        <div class="loading-text">
            LOADING<span class="loading-dots">
                <span class="loading-dot" style="--dot-index: 1">.</span>
                <span class="loading-dot" style="--dot-index: 2">.</span>
                <span class="loading-dot" style="--dot-index: 3">.</span>
            </span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <div class="page-header">
                <h4 class="main-title">KSAHC Dashboard</h4>
            </div>
            
            <!-- Main Sections -->
            <div class="row">
                <!-- Left Column - Quick Links -->
                <div class="col-lg-4">
                    <div class="info-card">
                        
                        <div class="info-card-body">
                            <ul class="quick-links">
                                <li><a href="profile.php"><i class="fas fa-user-circle"></i> My Profile</a></li>
                                <li><a href="#"><i class="fas fa-file-alt"></i> My Documents</a></li>
                                <li><a href="#"><i class="fas fa-certificate"></i> Certificates</a></li>
                                <li><a href="#"><i class="fas fa-sync"></i> Renewal</a></li>
                                <li><a href="reset_password.php"><i class="fas fa-key"></i> Change Password</a></li>
                                <li><a href="#"><i class="fas fa-question-circle"></i> Help & Support</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Integrated Profile & Welcome Message -->
                <div class="col-lg-8">
                    <div class="profile-welcome-card">
                        
                        <div class="profile-welcome-body">
                            <!-- Profile Section -->
                            <div class="profile-section">
                                <?php if(!empty($practitioner_data['practitioner_profile_image'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($practitioner_data['practitioner_profile_image']); ?>" alt="Profile" class="practitioner-image">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/150" alt="Profile" class="practitioner-image">
                                <?php endif; ?>
                                <h5 class="mb-2"><?php echo htmlspecialchars($practitioner_data['practitioner_name']); ?></h5>
                                <p class="text-muted mb-2"><?php echo htmlspecialchars($practitioner_data['registration_type'] ?? 'Healthcare Professional'); ?></p>
                                <span class="badge-active mb-3"><i class="fas fa-check-circle mr-1"></i>Active</span>
                                <!-- <a href="profile.php" class="btn btn-profile">View Profile</a> -->
                            </div>
                            
                            <!-- Welcome Section -->
                            <div class="welcome-section">
                                <p>Thank you for accessing the Karnataka State Allied & Healthcare Council Portal. This platform provides you with streamlined access to your professional registration details, certification processes, and essential healthcare resources.</p>
                                <p>As a registered healthcare professional, you can manage your profile, access your certificates, track renewals, and stay updated with the latest announcements from the council.</p>
                                
                               
                              
                                        <h5 class="mb-1"><?php echo htmlspecialchars($president_data['president_name']); ?></h5>
                                        <p class="text-muted mb-1">President, KSAHC</p>
                                        <!-- <p class="president-term">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            Term: <?php echo date('d M Y', strtotime($president_data['president_from_date'])); ?> - 
                                            <?php echo date('d M Y', strtotime($president_data['president_to_date'])); ?>
                                        </p> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preloader
        window.addEventListener('load', function() {
            const preloader = document.querySelector('.preloader-container');
            setTimeout(() => {
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
            }, 2000);
        });

        // Initialize Bootstrap tooltips
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
</body>
</html>