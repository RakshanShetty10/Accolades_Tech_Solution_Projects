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

// Process status update if requested
if(isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    
    $update_sql = "UPDATE practitioner SET registration_status = ? WHERE practitioner_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $new_status, $practitioner_id);
    
    if($update_stmt->execute()) {
        $message = "Status updated successfully.";
        $alert_type = "success";
        
        // Refresh practitioner data
        $practitioner['registration_status'] = $new_status;
    } else {
        $message = "Error updating status: " . $conn->error;
        $alert_type = "danger";
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
        .profile-header {
            background-color: #f8f9fc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            position: relative;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            
        }
        
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;

            
        }
        
        .status-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            top: -20px;

        }
        
        .info-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-5px);
        }
        
        .info-card .card-header {
            background-color: #4e73df;
            color: white;
            padding: 15px 20px;
            font-weight: bold;
            font-size: 16px;
        }
        
        .info-item {
            padding: 12px 20px;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.3s ease;
        }
        
        .info-item:hover {
            background-color: #f8f9fc;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 14px;
        }
        
        .signature-img {
            max-width: 200px;
            max-height: 100px;
            border: 1px solid #ddd;
            padding: 5px;
            background-color: #fff;
            border-radius: 4px;
        }

        /* Badge styles */
        .badge-success {
            background-color: #1cc88a;
        }
        
        .badge-warning {
            background-color: #f6c23e;
        }
        
        .badge-danger {
            background-color: #e74a3b;
        }
        
        .badge-info {
            background-color: #36b9cc;
        }

        /* Button styles */
        .btn-block {
            padding: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }
        
        .btn-warning {
            background-color: #f6c23e;
            border-color: #f6c23e;
            color: #fff;
        }
        
        .btn-warning:hover {
            background-color: #f4b619;
            border-color: #f4b619;
            color: #fff;
        }

        /* Form styles */
        .form-control {
            border-radius: 4px;
            padding: 10px 15px;
            border: 1px solid #d1d3e2;
        }
        
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-img {
                width: 100px;
                height: 100px;
            }
            
            .status-badge {
                position: static;
                margin-top: 10px;
                display: inline-block;
            }
            
            .info-label {
                margin-bottom: 5px;
            }
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
            
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                    <h1>Practitioner Details</h1>
                    <div>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
                        </a>
                        <!-- <a href="edit_practitioner.php?id=<?php echo $practitioner_id; ?>" class="btn btn-primary">
                            <i class="fas fa-edit mr-1"></i> Edit Practitioner
                        </a> -->
                    </div>
                </div>
                
                <?php if(isset($message)): ?>
                <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
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
                            <h3 class="mb-2"><?php echo htmlspecialchars($practitioner['practitioner_name']); ?></h3>
                            <p class="text-muted mb-2">
                                <i class="fas fa-id-card mr-2"></i> Registration ID: <?php echo $practitioner['practitioner_id']; ?>
                            </p>
                            <p class="text-muted mb-2">
                                <i class="fas fa-calendar-alt mr-2"></i> Registered on: <?php echo date('d M Y', strtotime($practitioner['registration_date'])); ?>
                            </p>
                            <p class="text-muted mb-0">
                                <i class="fas fa-clipboard-list mr-2"></i> Registration Type: <?php echo htmlspecialchars($practitioner['registration_type']); ?>
                            </p>
                        </div>
                        <div class="col-md-3 text-right">
                            <?php if($practitioner['registration_status'] == 'Approved'): ?>
                                <span class="badge badge-success status-badge">Approved</span>
                            <?php elseif($practitioner['registration_status'] == 'Inactive'): ?>
                                <span class="badge badge-danger status-badge">Inactive</span>
                            <?php elseif($practitioner['registration_status'] == 'Active'): ?>
                                <span class="badge badge-info status-badge">Active</span>
                            <?php else: ?>
                                <span class="badge badge-warning status-badge">Pending</span>
                            <?php endif; ?>
                            
                            <!-- Status Update Form - Only show for Pending or Approved status -->
                            <?php if($practitioner['registration_status'] != 'Active'): ?>
                            <form method="POST" class="mt-5">
                                <div class="form-group">
                                    <label for="status"><strong>Update Status:</strong></label>
                                    <select name="status" id="status" class="form-control">
                                        <?php if($practitioner['registration_status'] == 'Pending'): ?>
                                            <option value="Approved" <?php echo ($practitioner['registration_status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                            <option value="Inactive" <?php echo ($practitioner['registration_status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                        <?php elseif($practitioner['registration_status'] == 'Approved'): ?>
                                            <option value="Active" <?php echo ($practitioner['registration_status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                            <option value="Inactive" <?php echo ($practitioner['registration_status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                        <?php else: ?>
                                            <option value="Pending" <?php echo ($practitioner['registration_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                            <option value="Approved" <?php echo ($practitioner['registration_status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <button type="submit" name="update_status" class="btn btn-primary btn-block">Update Status</button>
                            </form>
                            <?php endif; ?>
                            
                            <!-- Send Remark Button - Always visible -->
                            <button class="btn btn-warning btn-block mt-3" id="openRemarkModal">
                                <i class="fas fa-envelope mr-1"></i> Send Remark to Practitioner
                            </button>
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
                                            <?php elseif($practitioner['registration_status'] == 'Inactive'): ?>
                                                <span class="badge badge-danger">Inactive</span>
                                            <?php elseif($practitioner['registration_status'] == 'Active'): ?>
                                                <span class="badge badge-info">Active</span>
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
    
    <!-- Remark Modal -->
    <div class="modal fade" id="remarkModal" tabindex="-1" role="dialog" aria-labelledby="remarkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="remarkModalLabel">
                        <i class="fas fa-envelope mr-2"></i> Send Remark to <?php echo htmlspecialchars($practitioner['practitioner_name']); ?>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="remarkForm">
                        <div class="email-header bg-light p-3 mb-3 rounded">
                            <div class="form-group">
                                <label for="recipientEmail" class="font-weight-bold text-muted">
                                    <i class="fas fa-at mr-1"></i> To:
                                </label>
                                <input type="email" class="form-control-plaintext" id="recipientEmail" value="<?php echo htmlspecialchars($practitioner['practitioner_email_id']); ?>" readonly>
                            </div>
                            <div class="form-group mb-0">
                                <label for="recipientName" class="font-weight-bold text-muted">
                                    <i class="fas fa-user mr-1"></i> Recipient:
                                </label>
                                <input type="text" class="form-control-plaintext" id="recipientName" value="<?php echo htmlspecialchars($practitioner['practitioner_name']); ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remarkMessage" class="font-weight-bold">
                                <i class="fas fa-comment-alt mr-1"></i> Remark
                            </label>
                            <textarea class="form-control" id="remarkMessage" rows="6" 
                                placeholder="Enter your detailed instructions or comments to the practitioner..."></textarea>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i> Provide clear instructions about what actions the practitioner needs to take.
                            </small>
                        </div>
                    </form>
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-4" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Sending...</span>
                        </div>
                        <p class="mt-2">Sending email, please wait...</p>
                    </div>
                    <!-- Success Animation -->
                    <div id="successAnimation" class="text-center my-4" style="display: none;">
                        <div class="mb-3">
                            <i class="fas fa-check-circle text-success" style="font-size: 60px;"></i>
                        </div>
                        <h4 class="text-success">Email Sent Successfully!</h4>
                        <p class="text-muted">Your remark has been delivered to the practitioner.</p>
                    </div>
                    <!-- Error Message -->
                    <div id="errorMessage" class="alert alert-danger" style="display: none;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span id="errorText"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" id="sendRemarkBtn">
                        <i class="fas fa-paper-plane mr-1"></i> Send Remark
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Dark mode toggle -->
    <div class="dark-mode-toggle" id="darkModeToggle">
        <i class="fas fa-moon"></i>
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
        
        // Open Remark Modal
        $("#openRemarkModal").click(function() {
            // Reset form and UI elements
            $('#remarkForm').show();
            $('#loadingSpinner').hide();
            $('#successAnimation').hide();
            $('#errorMessage').hide();
            $('#sendRemarkBtn').prop('disabled', false);
            $('#remarkMessage').val(''); // Clear previous message
            
            // Show modal
            $('#remarkModal').modal('show');
        });
        
        // Send Remark via AJAX
        $('#sendRemarkBtn').click(function() {
            var recipientEmail = $('#recipientEmail').val();
            var recipientName = $('#recipientName').val();
            var message = $('#remarkMessage').val();
            var practitionerId = <?php echo $practitioner_id; ?>;

            // Validate message
            if (!message.trim()) {
                $('#errorMessage').show().find('#errorText').text('Please enter a remark message.');
                return;
            }

            // Show loading spinner, hide form
            $('#remarkForm').show();
            $('#loadingSpinner').show();
            $('#errorMessage').hide();
            $('#sendRemarkBtn').prop('disabled', true);

            // Send AJAX request
            $.ajax({
                type: 'POST',
                url: 'send_remark.php',
                data: {
                    email: recipientEmail,
                    name: recipientName,
                    message: message,
                    practitioner_id: practitionerId
                },
                success: function(response) {
                    $('#loadingSpinner').hide();
                    
                    if (response.includes('successfully')) {
                        // Show success animation
                        $('#remarkForm').hide();
                        $('#successAnimation').fadeIn();
                        
                        // Close modal after delay
                        setTimeout(function() {
                            $('#remarkModal').modal('hide');
                            $('#remarkForm').show();
                            $('#successAnimation').hide();
                            $('#sendRemarkBtn').prop('disabled', false);
                        }, 2000);
                    } else {
                        // Show error message
                        $('#errorMessage').show().find('#errorText').text(response);
                        $('#sendRemarkBtn').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    $('#loadingSpinner').hide();
                    $('#errorMessage').show().find('#errorText').text('Error: ' + error);
                    $('#sendRemarkBtn').prop('disabled', false);
                }
            });
        });

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