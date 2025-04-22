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

// Handle bulk registration number generation
if(isset($_POST['generate_reg_id']) && isset($_POST['selected_practitioners']) && !empty($_POST['selected_practitioners'])) {
    $selected_ids = $_POST['selected_practitioners'];
    $message = "";
    $alert_type = "success";
    $success_count = 0;
    $error_count = 0;
    
    // Get the highest current registration number
    $max_reg_query = "SELECT MAX(CAST(SUBSTRING(registration_number, 6) AS UNSIGNED)) as max_num FROM practitioner WHERE registration_number LIKE 'KSAHC%'";
    $max_result = $conn->query($max_reg_query);
    $max_row = $max_result->fetch_assoc();
    $current_max = ($max_row['max_num']) ? $max_row['max_num'] : 0;
    
    // Prepare update statement
    $update_sql = "UPDATE practitioner SET registration_number = ?, registration_status = 'Active', 
                   practitioner_username = ?, practitioner_password = ?, is_first_login = 'Yes' 
                   WHERE practitioner_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $reg_number, $reg_number, $hashed_password, $practitioner_id);
    
    foreach($selected_ids as $practitioner_id) {
        // Check if practitioner already has a registration number
        $check_sql = "SELECT p.registration_number, p.practitioner_name, p.practitioner_email_id, p.practitioner_birth_date 
                     FROM practitioner p WHERE p.practitioner_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $practitioner_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $practitioner_data = $check_result->fetch_assoc();
        
        if(!empty($practitioner_data['registration_number'])) {
            $error_count++;
            continue; // Skip if already has a registration number
        }
        
        // Generate new registration number with 4 digits
        $new_num = ++$current_max;
        $reg_number = 'KSAHC' . str_pad($new_num, 4, '0', STR_PAD_LEFT);
        
        // Create password hash using date of birth (format: YYYY-MM-DD)
        $dob = $practitioner_data['practitioner_birth_date'];
        $hashed_password = password_hash($dob, PASSWORD_DEFAULT);
        
        // Update the practitioner record
        $update_stmt->execute();
        
        if($update_stmt->affected_rows > 0) {
            $success_count++;
            
            // Send email notification using PHPMailer
            // require '../../vendor/autoload.php'; // Include PHPMailer autoload file
            require_once '../mail-config.php';   // Include mail configuration
            
            try {
                // Get configured mailer
                $mail = getConfiguredMailer();
                
                // Add recipient
                $mail->addAddress($practitioner_data['practitioner_email_id'], $practitioner_data['practitioner_name']);
                
                // Email subject
                $mail->Subject = "Your KSAHC Registration Number and Login Information";
                
                // Email body
                $message_body = "
                <html>
                <head>
                    <title>KSAHC Registration</title>
                    <style>
                        body { font-family: Arial, sans-serif; line-height: 1.6; }
                        .container { padding: 20px; max-width: 600px; margin: 0 auto; }
                        .header { background-color: #4e73df; color: white; padding: 15px; text-align: center; }
                        .content { padding: 20px; border: 1px solid #ddd; }
                        .footer { font-size: 12px; text-align: center; margin-top: 20px; color: #777; }
                        .info-box { background-color: #f8f9fc; padding: 15px; margin: 15px 0; border-left: 4px solid #4e73df; }
                    </style>
                </head>
                <body>
                    <div class='container'>
                        <div class='header'>
                            <h2>Karnataka State Allied & Healthcare Council</h2>
                        </div>
                        <div class='content'>
                            <p>Dear " . htmlspecialchars($practitioner_data['practitioner_name']) . ",</p>
                            <p>Congratulations! Your registration with Karnataka State Allied & Healthcare Council has been approved and a registration number has been generated for you.</p>
                            
                            <div class='info-box'>
                                <p><strong>Registration Number:</strong> " . htmlspecialchars($reg_number) . "</p>
                                <p><strong>Status:</strong> Active</p>
                            </div>
                            
                            <h3>Login Information</h3>
                            <p>You can now login to your KSAHC account using the following credentials:</p>
                            <div class='info-box'>
                                <p><strong>Username:</strong> " . htmlspecialchars($reg_number) . "</p>
                                <p><strong>Initial Password:</strong> Your date of birth (YYYY-MM-DD format)</p>
                            </div>
                            
                            <p><strong>Important:</strong> For security reasons, you will be required to change your password on your first login.</p>
                            
                            <p>To login, please visit our website at <a href='https://ksahc.in/login'>https://ksahc.in/login</a></p>
                            
                            <p>Thank you for registering with KSAHC.</p>
                            
                            <p>Best regards,<br>
                            Karnataka State Allied & Healthcare Council</p>
                        </div>
                        <div class='footer'>
                            <p>This is an automated email. Please do not reply to this message.</p>
                        </div>
                    </div>
                </body>
                </html>
                ";
                
                $mail->Body = $message_body;
                $mail->send();
            } catch (Exception $e) {
                // Log email sending error but continue with registration process
                error_log("Failed to send registration email to practitioner ID {$practitioner_id}: " . $e->getMessage());
            }
        } else {
            $error_count++;
        }
    }
    
    if($success_count > 0) {
        $message = "Successfully generated $success_count registration numbers.";
        if($error_count > 0) {
            $message .= " Failed to generate for $error_count practitioners (they may already have registration numbers).";
        }
        $message .= " Email notifications sent with login instructions.";
    } else {
        $message = "Failed to generate any registration numbers. All selected practitioners may already have registration numbers.";
        $alert_type = "danger";
    }
}

// Fetch Practitioners with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$recordsPerPage = 10;
$offset = ($page - 1) * $recordsPerPage;

// Get search parameter
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// SQL query for practitioners with 'Approved' status - without state filter
$sql = "SELECT p.*, rt.registration_type 
        FROM practitioner p
        LEFT JOIN registration_type_master rt ON p.registration_type_id = rt.registration_type_id
        WHERE p.registration_status = 'Approved' OR (p.registration_status = 'Active' AND p.registration_number IS NOT NULL)";

// Add search conditions if search term is provided
if($search_term) {
    $search_param = "%$search_term%";
    $sql .= " AND (p.practitioner_name LIKE ? OR 
                   p.practitioner_email_id LIKE ? OR 
                   p.practitioner_mobile_number LIKE ?)";
}

$sql .= " ORDER BY p.practitioner_id DESC LIMIT ?, ?";

// Total records query with search
$countSql = "SELECT COUNT(*) as total FROM practitioner WHERE registration_status = 'Approved' OR (registration_status = 'Active' AND registration_number IS NOT NULL)";
if($search_term) {
    $countSql .= " AND (practitioner_name LIKE ? OR 
                        practitioner_email_id LIKE ? OR 
                        practitioner_mobile_number LIKE ?)";
}

// Prepare and execute SQL statement with parameters
$stmt = $conn->prepare($sql);

if($search_term) {
    $search_param = "%$search_term%";
    $stmt->bind_param("sssii", $search_param, $search_param, $search_param, $offset, $recordsPerPage);
} else {
    $stmt->bind_param("ii", $offset, $recordsPerPage);
}

$stmt->execute();
$result = $stmt->get_result();

// Get total records for pagination
$countStmt = $conn->prepare($countSql);
if($search_term) {
    $search_param = "%$search_term%";
    $countStmt->bind_param("sss", $search_param, $search_param, $search_param);
}
$countStmt->execute();
$totalRecords = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $recordsPerPage);

// Get counts for dashboard - without state filter
$count_sql = "SELECT 
                 COUNT(*) as total, 
                 SUM(CASE WHEN registration_number IS NOT NULL AND registration_status = 'Active' THEN 1 ELSE 0 END) as registered,
                 SUM(CASE WHEN registration_number IS NULL AND registration_status = 'Approved' THEN 1 ELSE 0 END) as pending_registration
              FROM practitioner 
              WHERE registration_status IN ('Approved', 'Active')";
$count_result = $conn->query($count_sql);
$counts = $count_result->fetch_assoc();

// Page title
$pageTitle = "Central Admin Dashboard | Karnataka State Allied & Healthcare Council";
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 1.5rem;
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-3px);
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            font-weight: bold;
            color: #4e73df;
        }
        
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .btn-success {
            background-color: #1cc88a;
            border-color: #1cc88a;
        }
        
        .btn-info {
            background-color: #36b9cc;
            border-color: #36b9cc;
        }
        
        .table {
            color: #5a5c69;
        }
        
        .table th {
            background-color: #f8f9fc;
            border-top: none;
        }
        
        .table-bordered {
            border: 1px solid #e3e6f0;
        }
        
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #e3e6f0;
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
        
        .pagination .page-item.active .page-link {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .pagination .page-link {
            color: #4e73df;
        }
        
        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
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
        
        /* Custom styling for Select2 */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            min-height: 38px;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #4e73df;
            border: none;
            color: white;
            border-radius: 0.25rem;
            padding: 3px 8px;
            margin-top: 5px;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 5px;
        }
        
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #d1d3e2;
        }
        
        /* Enhanced stats cards */
        .stats-card {
            transition: all 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
        }
        
        /* Custom badge styles */
        .badge-reg-pending {
            background-color: #f6c23e;
            color: white;
        }
        
        .badge-reg-active {
            background-color: #1cc88a;
            color: white;
        }
        
        /* Dropdown animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .dropdown-menu.show {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading text-center text-white py-4">
                <img src="../../ksahc_logo.png" alt="Logo" class="logo-img mb-2" style="max-width: 80px;">
                <div>KSAHC Central Admin</div>
            </div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action bg-dark text-white active">
                    <i class="fas fa-tachometer-alt mr-2"></i> Approve Practitioner
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-user-md mr-2"></i> Central Registry
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-user-md mr-2"></i> NOC
                </a>
                
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-user-md mr-2"></i> Accounts
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-user-md mr-2"></i> HelpLine
                </a>
                
                <a href="settings.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-cog mr-2"></i> Settings
                </a>
                <a href="#" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-user-md mr-2"></i> Reports
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom">
                <button class="btn btn-dark" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                
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
                <h1 class="h3 mb-4 text-gray-800 font-weight-bold">
                    <i class="fas fa-clipboard-list mr-2 text-primary"></i>Approve Practitioner
                </h1>
                
                <?php if(isset($message)): ?>
                <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-<?php echo $alert_type == 'success' ? 'check-circle' : ($alert_type == 'danger' ? 'exclamation-circle' : 'info-circle'); ?> mr-2"></i>
                    <?php echo $message; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <?php endif; ?>
                
                <!-- Advanced Filter Section -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-filter mr-2"></i>Filter Options
        </h6>
        <button class="btn btn-sm btn-link" type="button" data-toggle="collapse" data-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>
    <div class="collapse show" id="filterCollapse">
        <div class="card-body">
            <form method="GET" id="filter-form">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="states"><strong>States</strong></label>
                        <select class="form-control select2-multiple" id="states" name="states[]" multiple="multiple">
                            <option value="Karnataka" selected>Karnataka</option>
                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                            <option value="Assam">Assam</option>
                            <option value="Bihar">Bihar</option>
                            <option value="Chhattisgarh">Chhattisgarh</option>
                            <option value="Goa">Goa</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Haryana">Haryana</option>
                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                            <option value="Jharkhand">Jharkhand</option>
                            <option value="Kerala">Kerala</option>
                            <option value="Madhya Pradesh">Madhya Pradesh</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Manipur">Manipur</option>
                            <option value="Meghalaya">Meghalaya</option>
                            <option value="Mizoram">Mizoram</option>
                            <option value="Nagaland">Nagaland</option>
                            <option value="Odisha">Odisha</option>
                            <option value="Punjab">Punjab</option>
                            <option value="Rajasthan">Rajasthan</option>
                            <option value="Sikkim">Sikkim</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Telangana">Telangana</option>
                            <option value="Tripura">Tripura</option>
                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                            <option value="Uttarakhand">Uttarakhand</option>
                            <option value="West Bengal">West Bengal</option>
                            <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                            <option value="Chandigarh">Chandigarh</option>
                            <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                            <option value="Daman and Diu">Daman and Diu</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                            <option value="Ladakh">Ladakh</option>
                            <option value="Lakshadweep">Lakshadweep</option>
                            <option value="Puducherry">Puducherry</option>
                        </select>
                        <div id="state-statistics" class="mt-2">
                            <div class="alert alert-info p-2 mb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small><strong>Karnataka:</strong> 456 practitioners pending approval</small>
                                    <button type="button" class="close" style="font-size: 1rem;" data-dismiss="alert">&times;</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="registration-year"><strong>Registration Year</strong></label>
                        <select class="form-control" id="registration-year" name="year">
                            <option value="">All Years</option>
                            <option value="2023" selected>2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                            <option value="2020">2020</option>
                            <option value="2019">2019</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="registration-status"><strong>Status</strong></label>
                        <select class="form-control" id="registration-status" name="status">
                            <option value="">All Statuses</option>
                            <option value="Approved">Approved</option>
                            <option value="Active">Active</option>
                            <option value="Pending">Pending</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="registration-type"><strong>Registration Type</strong></label>
                        <select class="form-control" id="registration-type" name="type">
                            <option value="">All Types</option>
                            <option value="1">Medical Laboratory Scientist</option>
                            <option value="2">Radiotherapy Technologist</option>
                            <option value="3">Optometrist</option>
                            <option value="4">Physiotherapist</option>
                            <option value="5">Occupational Therapist</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-9">
                        <div class="form-group mb-0">
                            <label for="search-input"><strong>Search</strong></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="search-input" name="search" placeholder="Search by name, email, registration number...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search mr-1"></i> Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="reset" class="btn btn-secondary btn-block" id="reset-btn">
                            <i class="fas fa-sync-alt mr-1"></i> Reset Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add this script at the end of your file or in the appropriate script section -->
<script>
$(document).ready(function() {
    // Initialize select2 for multiple select
    $('.select2-multiple').select2({
        placeholder: "Select states",
        allowClear: true
    });
    
    // State statistics data (dummy values)
    const stateStats = {
        "Karnataka": "456 practitioners pending approval",
        "Andhra Pradesh": "328 practitioners pending approval",
        "Maharashtra": "612 practitioners pending approval",
        "Tamil Nadu": "275 practitioners active",
        "Delhi": "189 practitioners pending approval",
        "Uttar Pradesh": "521 practitioners pending approval",
        "Kerala": "198 practitioners active",
        "Gujarat": "312 practitioners pending approval",
        "Telangana": "245 practitioners active"
    };
    
    // Function to show state statistics
    function showStateStatistics(selectedStates) {
        $('#state-statistics').empty();
        
        selectedStates.forEach(function(state) {
            if (stateStats[state]) {
                $('#state-statistics').append(
                    `<div class="alert alert-info p-2 mb-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <small><strong>${state}:</strong> ${stateStats[state]}</small>
                            <button type="button" class="close state-remove" data-state="${state}" style="font-size: 1rem;" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>`
                );
            }
        });
    }
    
    // Initial state
    const initialStates = $('#states').val();
    if (initialStates && initialStates.length > 0) {
        showStateStatistics(initialStates);
    }
    
    // On state selection change
    $('#states').on('change', function() {
        const selectedStates = $(this).val() || [];
        showStateStatistics(selectedStates);
    });
    
    // Remove state when clicking the close button
    $(document).on('click', '.state-remove', function() {
        const stateToRemove = $(this).data('state');
        const currentStates = $('#states').val() || [];
        const newStates = currentStates.filter(state => state !== stateToRemove);
        
        $('#states').val(newStates).trigger('change');
    });
    
    // Reset button should clear the statistics as well
    $('#reset-btn').on('click', function() {
        $('#state-statistics').empty();
    });
});
</script>
                
                
                <!-- State-wise Registration Statistics -->
                <!-- <div class="row mb-4">
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-chart-bar mr-2"></i>State-wise Registration Statistics
                                </h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">View Options:</div>
                                        <a class="dropdown-item" href="#">Export as PDF</a>
                                        <a class="dropdown-item" href="#">Export as Excel</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">View Full Report</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-bar">
                                    <div style="height: 300px; background-color: #f8f9fc; display: flex; align-items: center; justify-content: center; border-radius: 5px;">
                                        <span class="text-gray-500"><i class="fas fa-chart-bar mr-2"></i>State-wise registration chart would appear here</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-tasks mr-2"></i>Registration Status
                                </h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">View Options:</div>
                                        <a class="dropdown-item" href="#">Export as PDF</a>
                                        <a class="dropdown-item" href="#">Export as Excel</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">View Full Report</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-pie">
                                    <div style="height: 300px; background-color: #f8f9fc; display: flex; align-items: center; justify-content: center; border-radius: 5px;">
                                        <span class="text-gray-500"><i class="fas fa-chart-pie mr-2"></i>Status distribution chart would appear here</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
                
               



                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list mr-2"></i>Practitioner Management
                        </h6>
                        <div class="d-flex">
                            <!-- <form class="form-inline mr-2" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" placeholder="Search practitioners..." value="<?php echo htmlspecialchars($search_term); ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form> -->
                        </div>
                    </div> 
                    <div class="card-body">
                        <form method="POST" id="bulk-action-form">
                            <div class="bulk-actions mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" name="generate_reg_id" value="1" class="btn btn-primary" onclick="return confirm('Are you sure you want to generate KSAHC registration numbers for the selected practitioners?');">
                                            <i class="fas fa-id-card mr-1"></i> Generate KSAHC Registration Numbers
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="30" class="text-center">
                                                <input type="checkbox" id="select-all">
                                            </th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Registration Type</th>
                                            <th>Contact</th>
                                            <th>Registration Date</th>
                                            <th>Registration Number</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result && $result->num_rows > 0): ?>
                                            <?php while($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="selected_practitioners[]" value="<?php echo $row['practitioner_id']; ?>" class="practitioner-checkbox" <?php echo !empty($row['registration_number']) ? 'disabled title="Already has registration number"' : ''; ?>>
                                                    </td>
                                                    <td><?php echo $row['practitioner_id']; ?></td>
                                                    <td>
                                                        <div class="font-weight-bold"><?php echo htmlspecialchars($row['practitioner_name']); ?></div>
                                                    </td>
                                                    <td><?php echo isset($row['registration_type']) ? htmlspecialchars($row['registration_type']) : htmlspecialchars($row['registration_type_id']); ?></td>
                                                    <td>
                                                        <div><i class="fas fa-envelope text-primary mr-1"></i> <?php echo htmlspecialchars($row['practitioner_email_id']); ?></div>
                                                        <div><i class="fas fa-phone text-success mr-1"></i> <?php echo htmlspecialchars($row['practitioner_mobile_number']); ?></div>
                                                    </td>
                                                    <td><?php echo date('d M Y', strtotime($row['registration_date'])); ?></td>
                                                    <td class="text-center">
                                                        <?php if(!empty($row['registration_number'])): ?>
                                                            <span class="badge badge-success p-2"><?php echo htmlspecialchars($row['registration_number']); ?></span>
                                                        <?php else: ?>
                                                            <span class="badge badge-warning p-2">Not Generated</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                            <a href="view_practitioner.php?id=<?php echo $row['practitioner_id']; ?>" class="btn btn-sm btn-info" title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            
                                                            <?php if(empty($row['registration_number'])): ?>
                                                                <button type="button" class="btn btn-sm btn-success generate-single-reg" data-id="<?php echo $row['practitioner_id']; ?>" title="Generate Registration Number">
                                                                    <i class="fas fa-id-card"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center">No practitioners found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                        
                        <!-- Pagination -->
                        <?php if($totalPages > 1): ?>
                            <nav aria-label="Page navigation" class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $search_term ? '&search='.urlencode($search_term) : ''; ?>" tabindex="-1">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                    
                                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?><?php echo $search_term ? '&search='.urlencode($search_term) : ''; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $search_term ? '&search='.urlencode($search_term) : ''; ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        // Initialize Select2 for state dropdown
        $(document).ready(function() {
            $('.select2-multiple').select2({
                placeholder: "Select states",
                allowClear: true,
                theme: "classic"
            });
            
            // Toggle sidebar
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
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
            
            // Select all checkbox - only allow selection of checkboxes that aren't disabled
            document.getElementById('select-all').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.practitioner-checkbox:not([disabled])');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
            
            // Update "select all" checkbox state - only consider checkboxes that aren't disabled
            const practitionerCheckboxes = document.querySelectorAll('.practitioner-checkbox:not([disabled])');
            practitionerCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const selectAll = document.getElementById('select-all');
                    const enabledCheckboxes = document.querySelectorAll('.practitioner-checkbox:not([disabled])');
                    const allChecked = [...enabledCheckboxes].every(c => c.checked);
                    const anyChecked = [...enabledCheckboxes].some(c => c.checked);
                    selectAll.checked = allChecked;
                    selectAll.indeterminate = anyChecked && !allChecked;
                });
            });

            // Handle single registration number generation
            $('.generate-single-reg').click(function(e) {
                e.preventDefault();
                
                if(!confirm('Are you sure you want to generate a registration number for this practitioner?')) {
                    return;
                }
                
                // Create a temporary form to submit just this practitioner ID
                var tempForm = $('<form method="POST"></form>');
                tempForm.append('<input type="hidden" name="generate_reg_id" value="1">');
                tempForm.append('<input type="hidden" name="selected_practitioners[]" value="' + $(this).data('id') + '">');
                
                $('body').append(tempForm);
                tempForm.submit();
            });
            
            <?php if(isset($message) && $alert_type == 'success' && $success_count > 0): ?>
            // Success animation for registration number generation
            $(document).ready(function() {
                // Show success popup
                const successHtml = `
                    <div id="successPopup">
                        <div class="success-anim">
                            <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                            <h3 class="mt-4 font-weight-bold">Success!</h3>
                            <p class="mt-3">Generated registration numbers for <?php echo $success_count; ?> practitioners</p>
                            <p>Status updated from <span class="badge badge-success">Approved</span> to <span class="badge badge-info">Active</span></p>
                            <p><i class="fas fa-envelope text-primary mr-1"></i> Login credentials sent to practitioner's email</p>
                            <button class="btn btn-primary mt-3" onclick="document.getElementById('successPopup').remove();">
                                <i class="fas fa-check mr-1"></i> OK
                            </button>
                        </div>
                    </div>
                `;
                
                $('body').append(successHtml);
            });
            <?php endif; ?>
            
            // Filter section toggle
            $('#filterCollapse').on('shown.bs.collapse', function () {
                $('#filterCollapse').find('button i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            });
            
            $('#filterCollapse').on('hidden.bs.collapse', function () {
                $('#filterCollapse').find('button i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            });
        });
    </script>
</body>
</html>