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
        // 'president_image' => 'president.jpg'
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | KSAHC Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/profile.css">
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
            padding-top: 60px;
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
                <h4 class="main-title">My Profile</h4>
            </div>
            
            <!-- Main Sections -->
            <div class="row">
                <!-- Left Column - Quick Links -->
                <div class="col-lg-4">
                    <div class="info-card">
                        <div class="info-card-header">
                            <h5>Quick Links</h5>
                        </div>
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
                    
                    <!-- Profile Summary Card -->
                  
                </div>
                
                <!-- Right Column - Profile Details -->
                <div class="col-lg-8">
                    <div class="card profile-card">
                        <!-- Profile Tabs Navigation -->
                        <div class="card-header p-0">
                            <ul class="nav nav-tabs profile-tabs" id="profileTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab" aria-controls="personal" aria-selected="true">
                                        <i class="fas fa-user"></i> Personal Info
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">
                                        <i class="fas fa-address-book"></i> Contact
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="education-tab" data-toggle="tab" href="#education" role="tab" aria-controls="education" aria-selected="false">
                                        <i class="fas fa-graduation-cap"></i> Education
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="professional-tab" data-toggle="tab" href="#professional" role="tab" aria-controls="professional" aria-selected="false">
                                        <i class="fas fa-briefcase"></i> Professional
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="false">
                                        <i class="fas fa-file-alt"></i> Documents
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Profile Tabs Content -->
                        <div class="card-body">
                            <div class="tab-content profile-tab-content" id="profileTabsContent">
                                <!-- Personal Info Tab -->
                                <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                                    <h5 class="section-title">Personal Information</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <p class="form-control-static"><?php echo $user_name; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Date of Birth</label>
                                                <p class="form-control-static"><?php echo isset($practitioner_data['practitioner_birth_date']) ? date('d F Y', strtotime($practitioner_data['practitioner_birth_date'])) : '15 June 1985'; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <p class="form-control-static"><?php echo $practitioner_data['practitioner_gender'] ?? 'Male'; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Blood Group</label>
                                                <p class="form-control-static"><?php echo $practitioner_data['practitioner_blood_group'] ?? 'O+'; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Nationality</label>
                                                <p class="form-control-static"><?php echo $practitioner_data['practitioner_nationality'] ?? 'Indian'; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Marital Status</label>
                                                <p class="form-control-static"><?php echo $practitioner_data['practitioner_marital_status'] ?? 'Married'; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Contact Info Tab -->
                                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                    <h5 class="section-title">Contact Information</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email Address</label>
                                                <p class="form-control-static"><?php echo $practitioner_data['practitioner_email_id'] ?? 'practitioner@example.com'; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Mobile Number</label>
                                                <p class="form-control-static"><?php echo $practitioner_data['practitioner_mobile_number'] ?? '+91 9876543210'; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Alternative Number</label>
                                                <p class="form-control-static"><?php echo $practitioner_data['practitioner_alternative_number'] ?? '+91 8765432109'; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Current Address</label>
                                                <p class="form-control-static"><?php echo $practitioner_data['practitioner_address'] ?? '123 Main Street, Apartment 4B, Bangalore, Karnataka - 560001'; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Permanent Address</label>
                                                <p class="form-control-static"><?php echo $practitioner_data['practitioner_permanent_address'] ?? '456 Park Avenue, House No. 7, Mysore, Karnataka - 570001'; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Education Tab -->
                                <div class="tab-pane fade" id="education" role="tabpanel" aria-labelledby="education-tab">
                                    <h5 class="section-title">Educational Qualifications</h5>
                                    
                                    <div class="education-item">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Bachelor of Homeopathic Medicine and Surgery (BHMS)</h6>
                                                <p>Government Homeopathic Medical College</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="text-muted">2005 - 2010</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="text-success">First Class</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="education-item">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>MD in Homeopathy</h6>
                                                <p>National Institute of Homeopathy</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="text-muted">2010 - 2013</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="text-success">Distinction</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="education-item">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Certificate in Advanced Clinical Homeopathy</h6>
                                                <p>International Academy of Classical Homeopathy</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="text-muted">2015</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="text-success">Completed</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Professional Tab -->
                                <div class="tab-pane fade" id="professional" role="tabpanel" aria-labelledby="professional-tab">
                                    <h5 class="section-title">Professional Experience</h5>
                                    
                                    <div class="professional-item">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Senior Homeopathic Physician</h6>
                                                <p>City Homeopathic Hospital</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="text-muted">2018 - Present</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="text-success">Full-time</p>
                                            </div>
                                        </div>
                                        <p>Providing comprehensive homeopathic treatment for various chronic and acute conditions.</p>
                                    </div>
                                    
                                    <div class="professional-item">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Homeopathic Consultant</h6>
                                                <p>Wellness Homeopathy Clinic</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="text-muted">2013 - 2018</p>
                                            </div>
                                            <div class="col-md-3">
                                                <p class="text-success">Full-time</p>
                                            </div>
                                        </div>
                                        <p>Managed patient care and treatment for a wide range of health conditions using homeopathic principles.</p>
                                    </div>
                                </div>
                                
                                <!-- Documents Tab -->
                                <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                    <h5 class="section-title">Uploaded Documents</h5>
                                    
                                    <div class="document-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-1">
                                                <i class="fas fa-file-pdf text-danger fa-2x"></i>
                                            </div>
                                            <div class="col-md-8">
                                                <h6>BHMS Degree Certificate</h6>
                                                <p class="text-muted small mb-0">Uploaded on: 15 Jan 2022</p>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> View</a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="document-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-1">
                                                <i class="fas fa-file-pdf text-danger fa-2x"></i>
                                            </div>
                                            <div class="col-md-8">
                                                <h6>MD Certificate</h6>
                                                <p class="text-muted small mb-0">Uploaded on: 15 Jan 2022</p>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> View</a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="document-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-1">
                                                <i class="fas fa-file-image text-primary fa-2x"></i>
                                            </div>
                                            <div class="col-md-8">
                                                <h6>Registration Certificate</h6>
                                                <p class="text-muted small mb-0">Uploaded on: 16 Jan 2022</p>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> View</a>
                                            </div>
                                        </div>
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
            }, 10);
        });

        // Initialize Bootstrap tooltips and tabs
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            
            // Ensure tabs work correctly
            $('#profileTabs a').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
            });
            
            // Handle URL with hash for direct tab access
            if (window.location.hash) {
                const hash = window.location.hash;
                $('#profileTabs a[href="' + hash + '"]').tab('show');
            }
            
            // Change URL hash when tab changes
            $('#profileTabs a').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash;
            });
        });
    </script>
</body>
</html>