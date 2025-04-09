<?php
// Start session
session_start();

// Database connection
require_once '../../config/config.php';

// Check if user is logged in
// if(!isset($_SESSION['admin_id'])) {
//     header("Location: login.php");
//     exit;
// }

// Check if ID is provided
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$practitioner_id = intval($_GET['id']);

// Fetch practitioner details
$sql = "SELECT p.*, rt.registration_type 
        FROM practitioner p
        LEFT JOIN registration_type_master rt ON p.registration_type_id = rt.registration_type_id
        WHERE p.practitioner_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $practitioner_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0) {
    header("Location: index.php");
    exit;
}

$practitioner = $result->fetch_assoc();

// Fetch education information
$edu_sql = "SELECT e.*, c.college_name, u.university_name
            FROM education_information e
            LEFT JOIN college_master c ON e.college_id = c.college_id
            LEFT JOIN university_master u ON e.university_id = u.university_id
            WHERE e.practitioner_id = ?";
$edu_stmt = $conn->prepare($edu_sql);
$edu_stmt->bind_param("i", $practitioner_id);
$edu_stmt->execute();
$edu_result = $edu_stmt->get_result();
$education = $edu_result->fetch_assoc();

// Fetch address information
$addr_sql = "SELECT * FROM practitioner_address 
             WHERE practitioner_id = ? 
             ORDER BY practitioner_address_type";
$addr_stmt = $conn->prepare($addr_sql);
$addr_stmt->bind_param("i", $practitioner_id);
$addr_stmt->execute();
$addr_result = $addr_stmt->get_result();

$addresses = [];
while($row = $addr_result->fetch_assoc()) {
    $addresses[$row['practitioner_address_type']] = $row;
}

// Process registration number generation if requested
if(isset($_POST['generate_registration_number']) && $practitioner['registration_status'] == 'Approved') {
    // Check if practitioner already has registration number
    if(!empty($practitioner['registration_number'])) {
        $message = "Practitioner already has a registration number.";
        $alert_type = "warning";
    } else {
        // Get the highest current registration number
        $max_sql = "SELECT MAX(CAST(SUBSTRING(registration_number, 6) AS UNSIGNED)) as max_num FROM practitioner WHERE registration_number LIKE 'KSAHC%'";
        $max_result = $conn->query($max_sql);
        $max_row = $max_result->fetch_assoc();
        $next_num = 1; // Default start
        
        if ($max_row && $max_row['max_num'] !== null) {
            $next_num = $max_row['max_num'] + 1;
        }
        
        // Format the new registration number with 4 digits leading zeros
        $new_reg_id = 'KSAHC' . str_pad($next_num, 4, '0', STR_PAD_LEFT);
        
        // Double-check that this registration number doesn't already exist
        $check_dup_sql = "SELECT COUNT(*) as count FROM practitioner WHERE registration_number = ?";
        $check_dup_stmt = $conn->prepare($check_dup_sql);
        $check_dup_stmt->bind_param("s", $new_reg_id);
        $check_dup_stmt->execute();
        $check_dup_result = $check_dup_stmt->get_result();
        $check_dup_row = $check_dup_result->fetch_assoc();
        
        // If somehow this number already exists, increment until we find an unused one
        while ($check_dup_row['count'] > 0) {
            $next_num++;
            $new_reg_id = 'KSAHC' . str_pad($next_num, 4, '0', STR_PAD_LEFT);
            
            $check_dup_stmt->bind_param("s", $new_reg_id);
            $check_dup_stmt->execute();
            $check_dup_result = $check_dup_stmt->get_result();
            $check_dup_row = $check_dup_result->fetch_assoc();
        }
        
        // Update the practitioner with registration number and change status to Active
        $update_sql = "UPDATE practitioner SET registration_number = ?, registration_status = 'Active' WHERE practitioner_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $new_reg_id, $practitioner_id);
        
        if($update_stmt->execute()) {
            $message = "Registration number generated successfully: " . $new_reg_id;
            $alert_type = "success";
            
            // Refresh practitioner data
            $practitioner['registration_number'] = $new_reg_id;
            $practitioner['registration_status'] = 'Active';
        } else {
            $message = "Error generating registration number: " . $conn->error;
            $alert_type = "danger";
        }
    }
}

// Page title
$pageTitle = "View Practitioner Details | Karnataka State Allied & Healthcare Council";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        #wrapper {
            overflow-x: hidden;
        }
        
        #sidebar-wrapper {
            min-height: 100vh;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: margin 0.25s ease-out;
        }
        
        #sidebar-wrapper .sidebar-heading {
            padding: 1.2rem 1.25rem;
        }
        
        #sidebar-wrapper .list-group-item {
            border: none;
            transition: all 0.3s;
        }
        
        #sidebar-wrapper .list-group-item.active {
            background-color: #4e73df;
            border-left: 4px solid #fff;
        }
        
        #sidebar-wrapper .list-group-item:hover:not(.active) {
            background-color: #3a3b45;
            border-left: 4px solid #4e73df;
        }
        
        #page-content-wrapper {
            min-width: 80vw;
        }
        
        .profile-header {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 30px;
            position: relative;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            transition: transform 0.2s;
        }
        
        .profile-header:hover {
            transform: translateY(-5px);
        }
        
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.15);
        }
        
        .status-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .info-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
            border: none;
            transition: transform 0.2s;
        }
        
        .info-card:hover {
            transform: translateY(-3px);
        }
        
        .info-card .card-header {
            background-color: #4e73df;
            color: white;
            padding: 15px 20px;
            font-weight: bold;
            border: none;
        }
        
        .info-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s;
        }
        
        .info-item:hover {
            background-color: #f8f9fc;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: bold;
            color: #4e73df;
        }
        
        .signature-img {
            max-width: 200px;
            max-height: 100px;
            border: 1px solid #ddd;
            padding: 5px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border-radius: 5px;
        }
        
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
            box-shadow: 0 3px 5px rgba(0,0,0,0.1);
        }
        
        .btn-success {
            background-color: #1cc88a;
            border-color: #1cc88a;
            box-shadow: 0 3px 5px rgba(0,0,0,0.1);
        }
        
        .btn-info {
            background-color: #36b9cc;
            border-color: #36b9cc;
            box-shadow: 0 3px 5px rgba(0,0,0,0.1);
        }
        
        .btn-secondary {
            background-color: #858796;
            border-color: #858796;
            box-shadow: 0 3px 5px rgba(0,0,0,0.1);
        }
        
        .badge {
            font-size: 85%;
            font-weight: 600;
            padding: 0.35em 0.65em;
            border-radius: 10rem;
        }
        
        .badge-success {
            background-color: #1cc88a;
        }
        
        .badge-warning {
            background-color: #f6c23e;
            color: #fff;
        }
        
        .badge-info {
            background-color: #36b9cc;
        }
        
        .badge-danger {
            background-color: #e74a3b;
        }
        
        /* Animation styles for success popup */
        @keyframes success-animation {
            0% { transform: scale(0.5); opacity: 0; }
            70% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .success-anim {
            animation: success-animation 1s ease-out forwards;
        }
        
        #successPopup {
            position: fixed; 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%); 
            background-color: white; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 0 30px rgba(0,0,0,0.2); 
            text-align: center; 
            z-index: 9999;
        }
        
        .navbar {
            padding: 0.5rem 1rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading text-center text-white py-4">
                <img src="../../ksahc_logo.png" alt="Logo" class="logo-img mb-2" style="max-width: 60px;">
                <div>KSAHC Admin</div>
            </div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="practitioners.php" class="list-group-item list-group-item-action bg-dark text-white active">
                    <i class="fas fa-user-md mr-2"></i> Practitioners
                </a>
                <a href="colleges.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-university mr-2"></i> Colleges
                </a>
                <a href="universities.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-graduation-cap mr-2"></i> Universities
                </a>
                <a href="settings.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-cog mr-2"></i> Settings
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom">
                <button class="btn btn-dark" id="menu-toggle"><i class="fas fa-bars"></i></button>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user-circle mr-1"></i> <?php echo $_SESSION['admin_name'] ?? 'Admin'; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="profile.php"><i class="fas fa-user mr-1"></i> Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt mr-1"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mt-2 mb-4">
                    <h1 class="h3 text-gray-800 font-weight-bold">
                        <i class="fas fa-user-md mr-2 text-primary"></i> Practitioner Details
                    </h1>
                    <div>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
                
                <?php if(isset($message)): ?>
                <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-<?php echo $alert_type == 'success' ? 'check-circle' : ($alert_type == 'danger' ? 'exclamation-circle' : 'info-circle'); ?> mr-2"></i>
                    <?php echo $message; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif; ?>
                
                <!-- Profile Header -->
                <div class="profile-header shadow">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            <?php if(!empty($practitioner['practitioner_profile_image']) && file_exists('../../uploads/' . $practitioner['practitioner_profile_image'])): ?>
                                <img src="../../uploads/<?php echo htmlspecialchars($practitioner['practitioner_profile_image']); ?>" alt="Profile Image" class="profile-img">
                            <?php else: ?>
                                <img src="https://via.placeholder.com/100x100" alt="Profile Image" class="profile-img">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-7">
                            <h3 class="mb-2 font-weight-bold"><?php echo htmlspecialchars($practitioner['practitioner_name']); ?></h3>
                            <p class="text-muted mb-2">
                                <i class="fas fa-id-card mr-2 text-primary"></i> Registration ID: <?php echo $practitioner['practitioner_id']; ?>
                            </p>
                            <p class="text-muted mb-2">
                                <i class="fas fa-calendar-alt mr-2 text-success"></i> Registered on: <?php echo date('d M Y', strtotime($practitioner['registration_date'])); ?>
                            </p>
                            <p class="text-muted mb-0">
                                <i class="fas fa-clipboard-list mr-2 text-info"></i> Registration Type: <?php echo htmlspecialchars($practitioner['registration_type']); ?>
                            </p>
                        </div>
                        <div class="col-md-3 text-right">
                            <?php if($practitioner['registration_status'] == 'Approved'): ?>
                                <span class="badge badge-success status-badge">Approved</span>
                            <?php elseif($practitioner['registration_status'] == 'Active'): ?>
                                <span class="badge badge-info status-badge">Active</span>
                            <?php elseif($practitioner['registration_status'] == 'Inactive'): ?>
                                <span class="badge badge-danger status-badge">Inactive</span>
                            <?php else: ?>
                                <span class="badge badge-warning status-badge">Pending</span>
                            <?php endif; ?>
                            
                            <!-- Registration Number Section -->
                            <div class="mt-5">
                                <?php if(!empty($practitioner['registration_number'])): ?>
                                    <div class="alert alert-success p-3 text-center shadow-sm">
                                        <strong><i class="fas fa-id-card mr-2"></i> Registration Number:</strong>
                                        <h3 class="mt-2 font-weight-bold"><?php echo htmlspecialchars($practitioner['registration_number']); ?></h3>
                                    </div>
                                <?php elseif($practitioner['registration_status'] == 'Approved'): ?>
                                    <form method="POST">
                                        <button type="submit" name="generate_registration_number" class="btn btn-success btn-block btn-lg shadow" id="generateRegBtn">
                                            <i class="fas fa-id-card mr-1"></i> Generate Registration Number
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <div class="alert alert-warning shadow-sm">
                                        <i class="fas fa-exclamation-triangle mr-2"></i> Registration number can only be generated for approved practitioners.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="card-header">
                                <i class="fas fa-user mr-2"></i> Personal Information
                            </div>
                            <div class="card-body p-0">
                                <div class="info-item">
                                    <div class="row">
                                        <div class="col-md-5 info-label">Full Name</div>
                                        <div class="col-md-7"><?php echo htmlspecialchars($practitioner['practitioner_name']); ?></div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="row">
                                        <div class="col-md-5 info-label">Father Name</div>
                                        <div class="col-md-7"><?php echo !empty($practitioner['practitioner_spouse_name']) ? htmlspecialchars($practitioner['practitioner_spouse_name']) : '<span class="text-muted">Not Provided</span>'; ?></div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="row">
                                        <div class="col-md-5 info-label">Date of Birth</div>
                                        <div class="col-md-7"><?php echo date('d M Y', strtotime($practitioner['practitioner_birth_date'])); ?></div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="row">
                                        <div class="col-md-5 info-label">Gender</div>
                                        <div class="col-md-7"><?php echo htmlspecialchars($practitioner['practitioner_gender']); ?></div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="row">
                                        <div class="col-md-5 info-label">Nationality</div>
                                        <div class="col-md-7"><?php echo htmlspecialchars($practitioner['practitioner_nationality']); ?></div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="row">
                                        <div class="col-md-5 info-label">Aadhar Number</div>
                                        <div class="col-md-7"><?php echo !empty($practitioner['practitioner_aadhar_number']) ? htmlspecialchars($practitioner['practitioner_aadhar_number']) : '<span class="text-muted">Not Provided</span>'; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="info-card">
                            <div class="card-header">
                                <i class="fas fa-address-book mr-2"></i> Contact Information
                            </div>
                            <div class="card-body p-0">
                                <div class="info-item">
                                    <div class="row">
                                        <div class="col-md-5 info-label">Mobile Number</div>
                                        <div class="col-md-7"><?php echo htmlspecialchars($practitioner['practitioner_mobile_number']); ?></div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="row">
                                        <div class="col-md-5 info-label">Email</div>
                                        <div class="col-md-7"><?php echo htmlspecialchars($practitioner['practitioner_email_id']); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Documents -->
                        <div class="info-card">
                            <div class="card-header">
                                <i class="fas fa-file-alt mr-2"></i> Documents
                            </div>
                            <div class="card-body p-0">
                                <div class="info-item">
                                    <div class="row">
                                        <div class="col-md-5 info-label">Signature</div>
                                        <div class="col-md-7">
                                            <?php if(!empty($practitioner['practitioner_signature']) && file_exists('../../uploads/' . $practitioner['practitioner_signature'])): ?>
                                                <img src="../../uploads/<?php echo htmlspecialchars($practitioner['practitioner_signature']); ?>" alt="Signature" class="signature-img">
                                            <?php else: ?>
                                                <span class="text-muted">Not Uploaded</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Address Information -->
                        <div class="info-card">
                            <div class="card-header">
                                <i class="fas fa-map-marker-alt mr-2"></i> Address Information
                            </div>
                            <div class="card-body p-0">
                                <?php if(isset($addresses['Permanent'])): ?>
                                    <div class="info-item">
                                        <h6 class="font-weight-bold">Permanent Address</h6>
                                        <p class="mb-0">
                                            <?php echo htmlspecialchars($addresses['Permanent']['practitioner_address_line1']); ?><br>
                                            <?php if(!empty($addresses['Permanent']['practitioner_address_line2'])): ?>
                                                <?php echo htmlspecialchars($addresses['Permanent']['practitioner_address_line2']); ?><br>
                                            <?php endif; ?>
                                            <span class="text-muted">Phone: <?php echo htmlspecialchars($addresses['Permanent']['practitioner_address_phoneno']); ?></span>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if(isset($addresses['Correspondence'])): ?>
                                    <div class="info-item">
                                        <h6 class="font-weight-bold">Correspondence Address</h6>
                                        <p class="mb-0">
                                            <?php echo htmlspecialchars($addresses['Correspondence']['practitioner_address_line1']); ?><br>
                                            <?php if(!empty($addresses['Correspondence']['practitioner_address_line2'])): ?>
                                                <?php echo htmlspecialchars($addresses['Correspondence']['practitioner_address_line2']); ?><br>
                                            <?php endif; ?>
                                            <span class="text-muted">Phone: <?php echo htmlspecialchars($addresses['Correspondence']['practitioner_address_phoneno']); ?></span>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Education Information -->
                        <div class="info-card">
                            <div class="card-header">
                                <i class="fas fa-graduation-cap mr-2"></i> Education Information
                            </div>
                            <div class="card-body p-0">
                                <?php if($education): ?>
                                    <div class="info-item">
                                        <div class="row">
                                            <div class="col-md-5 info-label">Education Name</div>
                                            <div class="col-md-7"><?php echo htmlspecialchars($education['education_name']); ?></div>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="row">
                                            <div class="col-md-5 info-label">Year of Passing</div>
                                            <div class="col-md-7"><?php echo htmlspecialchars($education['education_year_of_passing']); ?></div>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="row">
                                            <div class="col-md-5 info-label">Month of Passing</div>
                                            <div class="col-md-7"><?php echo htmlspecialchars($education['education_month_of_passing']); ?></div>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="row">
                                            <div class="col-md-5 info-label">College</div>
                                            <div class="col-md-7"><?php echo htmlspecialchars($education['college_name']); ?></div>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="row">
                                            <div class="col-md-5 info-label">University</div>
                                            <div class="col-md-7"><?php echo htmlspecialchars($education['university_name']); ?></div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="info-item">
                                        <p class="text-muted mb-0">No education information provided.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Registration Information -->
                        <div class="info-card">
                            <div class="card-header">
                                <i class="fas fa-clipboard-check mr-2"></i> Registration Information
                            </div>
                            <div class="card-body p-0">
                                <div class="info-item">
                                    <div class="row">
                                        <div class="col-md-5 info-label">Registration ID</div>
                                        <div class="col-md-7"><?php echo $practitioner['practitioner_id']; ?></div>
                                    </div>
                                </div>
                                <?php if(!empty($practitioner['registration_number'])): ?>
                                <div class="info-item">
                                    <div class="row">
                                        <div class="col-md-5 info-label">Registration Number</div>
                                        <div class="col-md-7">
                                            <span class="badge badge-success p-2"><?php echo htmlspecialchars($practitioner['registration_number']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="info-item">
                                    <div class="row">
                                        <div class="col-md-5 info-label">Registration Type</div>
                                        <div class="col-md-7"><?php echo htmlspecialchars($practitioner['registration_type']); ?></div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="row">
                                        <div class="col-md-5 info-label">Registration Date</div>
                                        <div class="col-md-7"><?php echo date('d M Y', strtotime($practitioner['registration_date'])); ?></div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="row">
                                        <div class="col-md-5 info-label">Status</div>
                                        <div class="col-md-7">
                                            <?php if($practitioner['registration_status'] == 'Approved'): ?>
                                                <span class="badge badge-success">Approved</span>
                                            <?php elseif($practitioner['registration_status'] == 'Active'): ?>
                                                <span class="badge badge-info">Active</span>
                                            <?php elseif($practitioner['registration_status'] == 'Inactive'): ?>
                                                <span class="badge badge-danger">Inactive</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Pending</span>
                                            <?php endif; ?>
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
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        // Toggle menu
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
        
        <?php if(isset($message) && $alert_type == 'success' && strpos($message, 'Registration number generated') !== false): ?>
        // Success animation for registration number generation
        $(document).ready(function() {
            // Show success popup
            const regNumber = "<?php echo !empty($practitioner['registration_number']) ? $practitioner['registration_number'] : ''; ?>";
            const successHtml = `
                <div id="successPopup">
                    <div class="success-anim">
                        <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                        <h3 class="mt-4 font-weight-bold">Registration Number Generated!</h3>
                        <h2 class="text-success mt-3 font-weight-bold">${regNumber}</h2>
                        <p class="mt-3">Practitioner status updated to <span class="badge badge-info p-2">Active</span></p>
                        <button class="btn btn-primary mt-3 px-4 py-2" onclick="document.getElementById('successPopup').remove();">
                            <i class="fas fa-check mr-1"></i> OK
                        </button>
                    </div>
                </div>
            `;
            
            $('body').append(successHtml);
        });
        <?php endif; ?>
    </script>
    
    <!-- Dark mode toggle -->
    <div class="dark-mode-toggle" id="darkModeToggle">
        <i class="fas fa-moon"></i>
    </div>

    <script>
        // Dark mode toggle functionality
        $(document).ready(function() {
            // Check for saved dark mode preference
            if (localStorage.getItem('darkMode') === 'enabled') {
                enableDarkMode();
            }
            
            // Toggle dark mode
            $("#darkModeToggle").click(function() {
                if ($('body').hasClass('dark-mode')) {
                    disableDarkMode();
                } else {
                    enableDarkMode();
                }
            });
            
            function enableDarkMode() {
                $('body').addClass('dark-mode');
                $('#darkModeToggle i').removeClass('fa-moon').addClass('fa-sun');
                localStorage.setItem('darkMode', 'enabled');
            }
            
            function disableDarkMode() {
                $('body').removeClass('dark-mode');
                $('#darkModeToggle i').removeClass('fa-sun').addClass('fa-moon');
                localStorage.setItem('darkMode', null);
            }
        });
    </script>
</body>
</html> 