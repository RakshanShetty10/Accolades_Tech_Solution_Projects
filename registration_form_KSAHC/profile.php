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

// Fetch practitioner information
$practitioner_sql = "SELECT p.*, rt.registration_type 
                    FROM practitioner p
                    LEFT JOIN registration_type_master rt ON p.registration_type_id = rt.registration_type_id
                    WHERE p.practitioner_id = ?";
$practitioner_stmt = $conn->prepare($practitioner_sql);
$practitioner_stmt->bind_param("i", $user_id);
$practitioner_stmt->execute();
$practitioner_result = $practitioner_stmt->get_result();
$practitioner_data = $practitioner_result->fetch_assoc();

// Fetch address information
$address_sql = "SELECT * FROM practitioner_address WHERE practitioner_id = ?";
$address_stmt = $conn->prepare($address_sql);
$address_stmt->bind_param("i", $user_id);
$address_stmt->execute();
$address_result = $address_stmt->get_result();
$address_data = $address_result->fetch_assoc();

// Fetch education information
$education_sql = "SELECT e.*, c.college_name, u.university_name
                 FROM education_information e
                 LEFT JOIN college_master c ON e.college_id = c.college_id
                 LEFT JOIN university_master u ON e.university_id = u.university_id
                 WHERE e.practitioner_id = ?";
$education_stmt = $conn->prepare($education_sql);
$education_stmt->bind_param("i", $user_id);
$education_stmt->execute();
$education_result = $education_stmt->get_result();
$education_data = $education_result->fetch_assoc();

// Fetch remarks
// $remarks_sql = "SELECT * FROM remarks WHERE practitioner_id = ? ORDER BY created_at DESC";
// $remarks_stmt = $conn->prepare($remarks_sql);
// $remarks_stmt->bind_param("i", $user_id);
// $remarks_stmt->execute();
// $remarks_result = $remarks_stmt->get_result();

// Fetch documents
// $documents_sql = "SELECT * FROM documents WHERE practitioner_id = ? ORDER BY uploaded_at DESC";
// $documents_stmt = $conn->prepare($documents_sql);
// $documents_stmt->bind_param("i", $user_id);
// $documents_stmt->execute();
// $documents_result = $documents_stmt->get_result();
// ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practitioner Profile | KSAHC</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --dark-color: #3a3b45;
            --light-color: #f8f9fc;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            min-height: 100vh;
        }

        /* Preloader Styles */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }

        .preloader.fade-out {
            opacity: 0;
        }

        .preloader-content {
            text-align: center;
        }

        .preloader-logo {
            width: 120px;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        .preloader-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        .preloader-text {
            color: var(--dark-color);
            font-size: 14px;
            font-weight: 500;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* Navbar Styles */
        .navbar {
            background-color: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 0.5rem 1rem;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        .navbar-nav .nav-link {
            color: var(--dark-color);
            font-weight: 500;
            padding: 0.5rem 1rem;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color);
        }

        .navbar-nav .nav-link.active {
            color: var(--primary-color);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .dropdown-item {
            color: var(--dark-color);
            font-weight: 500;
        }

        .dropdown-item:hover {
            background-color: var(--light-color);
            color: var(--primary-color);
        }

        .dropdown-item i {
            width: 20px;
            margin-right: 8px;
        }

        /* Main Content Styles */
        .main-content {
            margin-top: 60px;
            padding: 20px;
        }
        
        /* Sidebar styles */
        .sidebar {
            display: none; /* Hide the sidebar since we're using the navbar */
        }
        
        .sidebar .nav-link {
            color: var(--dark-color);
            padding: 1rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            color: var(--primary-color);
            background-color: var(--light-color);
        }
        
        .sidebar .nav-link.active {
            color: var(--primary-color);
            background-color: var(--light-color);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Card styles */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
        }
        
        .card-header h5 {
            margin-bottom: 0;
            color: var(--dark-color);
            font-weight: 600;
        }
        
        /* Tab styles */
        .nav-tabs {
            border-bottom: 1px solid #e3e6f0;
        }
        
        .nav-tabs .nav-link {
            color: var(--dark-color);
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            border: none;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border: none;
            border-bottom: 2px solid var(--primary-color);
        }
        
        /* Profile image styles */
        .profile-img-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
        }
        
        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        /* Info item styles */
        .info-item {
            padding: 15px;
            border-bottom: 1px solid #f1f1f1;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #666;
        }
        
        /* Document list styles */
        .document-item {
            padding: 15px;
            border-bottom: 1px solid #f1f1f1;
            display: flex;
            align-items: center;
        }
        
        .document-item:last-child {
            border-bottom: none;
        }
        
        .document-icon {
            width: 40px;
            height: 40px;
            background-color: var(--light-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary-color);
        }
        
        /* Responsive styles */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                padding: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="preloader-content">
            <img src="ksahc_logo.png" alt="KSAHC Logo" class="preloader-logo">
            <div class="preloader-spinner"></div>
            <div class="preloader-text">Loading your profile...</div>
        </div>
    </div>

    <!-- Include Navbar -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Welcome Alert -->
            <div class="alert alert-primary" role="alert">
                <h4 class="alert-heading"><i class="fas fa-user-check mr-2"></i>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h4>
                <p class="mb-0">You are logged in as a registered practitioner. Your registration number is <strong><?php echo htmlspecialchars($username); ?></strong></p>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="personal-tab" data-toggle="tab" href="#personal" role="tab">Personal Information</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab">Contact Information</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="education-tab" data-toggle="tab" href="#education" role="tab">Education</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="remarks-tab" data-toggle="tab" href="#remarks" role="tab">Remarks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab">Documents</a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="profileTabsContent">
                <!-- Personal Information Tab -->
                <div class="tab-pane fade show active" id="personal" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="profile-img-container">
                                        <?php if(!empty($practitioner_data['practitioner_profile_image'])): ?>
                                            <img src="uploads/<?php echo htmlspecialchars($practitioner_data['practitioner_profile_image']); ?>" alt="Profile" class="profile-img">
                                        <?php else: ?>
                                            <img src="https://via.placeholder.com/150" alt="Profile" class="profile-img">
                                        <?php endif; ?>
                                    </div>
                                    <h4 class="mb-1"><?php echo htmlspecialchars($practitioner_data['practitioner_name']); ?></h4>
                                    <p class="text-muted"><?php echo htmlspecialchars($practitioner_data['registration_type']); ?></p>
                                    <div class="mb-3">
                                        <span class="badge badge-success">Active</span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <div class="info-label">Registration Number</div>
                                                <div class="info-value"><?php echo htmlspecialchars($practitioner_data['registration_number']); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <div class="info-label">Registration Type</div>
                                                <div class="info-value"><?php echo htmlspecialchars($practitioner_data['registration_type']); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <div class="info-label">Date of Birth</div>
                                                <div class="info-value"><?php echo date('d M Y', strtotime($practitioner_data['practitioner_birth_date'])); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item">
                                                <div class="info-label">Gender</div>
                                                <div class="info-value"><?php echo htmlspecialchars($practitioner_data['practitioner_gender']); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Tab -->
                <div class="tab-pane fade" id="contact" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <?php if($address_data): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Address Line 1</div>
                                            <div class="info-value"><?php echo htmlspecialchars($address_data['address_line1']); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Address Line 2</div>
                                            <div class="info-value"><?php echo htmlspecialchars($address_data['address_line2']); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">City</div>
                                            <div class="info-value"><?php echo htmlspecialchars($address_data['city']); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">State</div>
                                            <div class="info-value"><?php echo htmlspecialchars($address_data['state']); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Pincode</div>
                                            <div class="info-value"><?php echo htmlspecialchars($address_data['pincode']); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Phone</div>
                                            <div class="info-value"><?php echo htmlspecialchars($practitioner_data['practitioner_mobile_number']); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Email</div>
                                            <div class="info-value"><?php echo htmlspecialchars($practitioner_data['practitioner_email_id']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-info-circle text-muted mb-3" style="font-size: 2rem;"></i>
                                    <p class="text-muted">No contact information available</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Education Tab -->
                <div class="tab-pane fade" id="education" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <?php if($education_data): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Education Name</div>
                                            <div class="info-value"><?php echo htmlspecialchars($education_data['education_name']); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">Year of Passing</div>
                                            <div class="info-value"><?php echo htmlspecialchars($education_data['education_year_of_passing']); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">College</div>
                                            <div class="info-value"><?php echo htmlspecialchars($education_data['college_name']); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <div class="info-label">University</div>
                                            <div class="info-value"><?php echo htmlspecialchars($education_data['university_name']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-info-circle text-muted mb-3" style="font-size: 2rem;"></i>
                                    <p class="text-muted">No education information available</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Remarks Tab -->
                <div class="tab-pane fade" id="remarks" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <?php if($remarks_result->num_rows > 0): ?>
                                <?php while($remark = $remarks_result->fetch_assoc()): ?>
                                    <div class="info-item">
                                        <div class="info-label"><?php echo date('d M Y, h:i A', strtotime($remark['created_at'])); ?></div>
                                        <div class="info-value"><?php echo htmlspecialchars($remark['remark_text']); ?></div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-info-circle text-muted mb-3" style="font-size: 2rem;"></i>
                                    <p class="text-muted">No remarks available</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Documents Tab -->
                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <?php if($documents_result->num_rows > 0): ?>
                                <?php while($document = $documents_result->fetch_assoc()): ?>
                                    <div class="document-item">
                                        <div class="document-icon">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                        <div>
                                            <div class="info-label"><?php echo htmlspecialchars($document['document_name']); ?></div>
                                            <div class="info-value">Uploaded on <?php echo date('d M Y', strtotime($document['uploaded_at'])); ?></div>
                                        </div>
                                        <div class="ml-auto">
                                            <a href="uploads/<?php echo htmlspecialchars($document['document_path']); ?>" class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-info-circle text-muted mb-3" style="font-size: 2rem;"></i>
                                    <p class="text-muted">No documents available</p>
                                </div>
                            <?php endif; ?>
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
            const preloader = document.querySelector('.preloader');
            preloader.classList.add('fade-out');
            setTimeout(() => {
                preloader.style.display = 'none';
            }, 500);
        });

        // Initialize Bootstrap tooltips
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
</body>
</html>
