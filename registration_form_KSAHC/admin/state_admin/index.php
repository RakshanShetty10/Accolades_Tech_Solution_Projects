<?php
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Start session to maintain user state across pages
session_start();

// Include database configuration file
require_once '../../config/config.php';

// Include email templates and functions
require_once '../email-templates.php';

// Check if user is logged in (commented out for development)
// if(!isset($_SESSION['admin_id'])) {
//     header("Location: login.php");
//     exit;
// }

// Auto-correct practitioners with registration numbers but not "Active" status
$auto_correction_sql = "UPDATE practitioner 
                      SET registration_status = 'Active' 
                      WHERE registration_number IS NOT NULL 
                      AND registration_number != '' 
                      AND registration_status != 'Active'";
$correction_result = $conn->query($auto_correction_sql);

// Notify about corrections if any were made
if ($correction_result && $conn->affected_rows > 0) {
    $corrected_count = $conn->affected_rows;
    $auto_message = "$corrected_count practitioner(s) with registration numbers automatically set to Active status.";
    $auto_alert_type = "info";
    
    // Log the correction
    error_log("Auto-corrected $corrected_count practitioners to Active status (they had registration numbers)");
}

// Handle bulk actions (approve/reject multiple practitioners)
if(isset($_POST['bulk_action']) && isset($_POST['selected_practitioners'])) {
    $bulk_action = $_POST['bulk_action'];
    $selected_practitioners = $_POST['selected_practitioners'];
    
    if($bulk_action == 'approve' || $bulk_action == 'reject') {
        $status = ($bulk_action == 'approve') ? 'Approved' : 'Inactive';
        $ids = implode(',', array_map('intval', $selected_practitioners));
        
        $sql = "UPDATE practitioner SET registration_status = ? WHERE practitioner_id IN ($ids)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $status);
        
        if($stmt->execute()) {
            $message = "Successfully " . ($bulk_action == 'approve' ? 'approved' : 'Inactive') . " " . count($selected_practitioners) . " practitioners.";
            $alert_type = "success";
            
            // Send email notifications for approved practitioners
            if($bulk_action == 'approve') {
                // Get practitioner data for emails
                $practitioners_sql = "SELECT practitioner_id, practitioner_name, practitioner_email_id FROM practitioner WHERE practitioner_id IN ($ids)";
                $practitioners_result = $conn->query($practitioners_sql);
                
                $email_sent_count = 0;
                
                if($practitioners_result && $practitioners_result->num_rows > 0) {
                    while($practitioner_data = $practitioners_result->fetch_assoc()) {
                        // Send approval email using the function from email-templates.php
                        if(sendApprovalEmail($practitioner_data['practitioner_email_id'], $practitioner_data['practitioner_name'], $practitioner_data['practitioner_id'])) {
                            $email_sent_count++;
                        }
                    }
                    
                    if($email_sent_count > 0) {
                        $message .= " Email notifications sent to $email_sent_count practitioners.";
                    }
                }
            }
        } else {
            $message = "Error updating status: " . $conn->error;
            $alert_type = "danger";
        }
    }
}

// Handle individual status changes (approve/reject single practitioner)
if(isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if($action == 'approve' || $action == 'reject') {
        $status = ($action == 'approve') ? 'Approved' : 'Inactive';
        
        $sql = "UPDATE practitioner SET registration_status = ? WHERE practitioner_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        
        if($stmt->execute()) {
            $message = "Practitioner has been " . ($action == 'approve' ? 'approved' : 'Inactive') . ".";
            $alert_type = "success";
            
            // Send email notification when status is set to Approved
            if($action == 'approve') {
                // Get practitioner data
                $get_practitioner_sql = "SELECT practitioner_name, practitioner_email_id FROM practitioner WHERE practitioner_id = ?";
                $get_stmt = $conn->prepare($get_practitioner_sql);
                $get_stmt->bind_param("i", $id);
                $get_stmt->execute();
                $result = $get_stmt->get_result();
                
                if($result->num_rows > 0) {
                    $practitioner_data = $result->fetch_assoc();
                    
                    // Send approval email using the function from email-templates.php
                    if(sendApprovalEmail($practitioner_data['practitioner_email_id'], $practitioner_data['practitioner_name'], $id)) {
                        $message .= " An email notification has been sent to the practitioner.";
                    }
                }
            }
        } else {
            $message = "Error updating status: " . $conn->error;
            $alert_type = "danger";
        }
    }
}

// Get total counts for dashboard statistics
$total_sql = "SELECT 
    COUNT(*) as total, 
    SUM(CASE WHEN registration_status = 'Approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN registration_status = 'Active' THEN 1 ELSE 0 END) as active,
    SUM(CASE WHEN registration_status = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN registration_status = 'Inactive' THEN 1 ELSE 0 END) as Inactive
FROM practitioner";

$total_result = $conn->query($total_sql);
$counts = $total_result->fetch_assoc();

// Set up pagination parameters
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Get filter parameters from URL
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Build WHERE clause for filtering
$where_clause = "WHERE 1=1";
if($status_filter) {
    $where_clause .= " AND registration_status = '" . $conn->real_escape_string($status_filter) . "'";
}

if($search_term) {
    $where_clause .= " AND (practitioner_name LIKE '%" . $conn->real_escape_string($search_term) . "%' OR 
                           practitioner_email_id LIKE '%" . $conn->real_escape_string($search_term) . "%' OR
                           practitioner_mobile_number LIKE '%" . $conn->real_escape_string($search_term) . "%')";
}

// Fetch practitioners with pagination and filters
$sql = "SELECT p.*, rt.registration_type 
        FROM practitioner p
        LEFT JOIN registration_type_master rt ON p.registration_type_id = rt.registration_type_id
        $where_clause
        ORDER BY p.practitioner_id DESC 
        LIMIT $offset, $limit";

$result = $conn->query($sql);

// Calculate total pages for pagination
$total_sql = "SELECT COUNT(*) as total FROM practitioner $where_clause";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $limit);

// Set page title
$pageTitle = "Admin Dashboard | Karnataka State Allied & Healthcare Council";
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
        #successAnimation {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            0% { opacity: 0; transform: scale(0.8); }
            100% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading text-center text-white py-4">
                <img src="../../ksahc_logo.png" alt="Logo" class="logo-img mb-2" style="max-width: 100px;">
                <div>KSAHC State Admin</div>
            </div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action bg-dark text-white active">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="practitioners.php" class="list-group-item list-group-item-action bg-dark text-white">
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
                <h1 class="mt-4 mb-4">Applied Practitioner List For Approval</h1>
                
                <!-- Display notifications -->
                <?php if(isset($message)): ?>
                <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show" role="alert">
                    <i class="fas fa-<?php echo $alert_type == 'success' ? 'check-circle' : ($alert_type == 'danger' ? 'exclamation-circle' : 'info-circle'); ?> mr-2"></i>
                    <?php echo $message; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif; ?>
                
                <?php if(isset($auto_message)): ?>
                <div class="alert alert-<?php echo $auto_alert_type; ?> alert-dismissible fade show" role="alert">
                    <i class="fas fa-sync-alt mr-2"></i>
                    <?php echo $auto_message; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php endif; ?>
                
                <!-- Status Tabs -->
                <ul class="nav nav-tabs mb-4" id="statusTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab">
                            <i class="fas fa-clock"></i> Pending Applications
                            <span class="badge badge-warning"><?php echo $counts['pending']; ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab">
                            <i class="fas fa-check-circle"></i> Approved
                            <span class="badge badge-success"><?php echo $counts['approved']; ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="active-tab" data-toggle="tab" href="#active" role="tab">
                            <i class="fas fa-user-check"></i> Active
                            <span class="badge badge-info"><?php echo $counts['active']; ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="inactive-tab" data-toggle="tab" href="#inactive" role="tab">
                            <i class="fas fa-ban"></i> Inactive/Rejected
                            <span class="badge badge-danger"><?php echo $counts['Inactive']; ?></span>
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="statusTabsContent">
                    <!-- Pending Tab -->
                    <div class="tab-pane fade show active" id="pending" role="tabpanel">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 font-weight-bold text-primary">Pending Applications</h6>
                                <div class="d-flex">
                                    <form class="form-inline mr-2" method="GET">
                                        <input type="hidden" name="status" value="Pending">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search_term); ?>">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" id="bulk-action-form">
                                    <div class="bulk-actions mb-3">
                                        <div class="btn-group" role="group">
                                            <button type="submit" name="bulk_action" value="approve" class="btn btn-success" onclick="return confirm('Are you sure you want to approve the selected practitioners?');">
                                                <i class="fas fa-check mr-1"></i> Approve Selected
                                            </button>
                                            <button type="submit" name="bulk_action" value="reject" class="btn btn-danger" onclick="return confirm('Are you sure you want to reject the selected practitioners?');">
                                                <i class="fas fa-times mr-1"></i> Reject Selected
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="30"><input type="checkbox" id="select-all-pending"></th>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Registration Type</th>
                                                    <th>Contact</th>
                                                    <th>Registration Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $pending_sql = "SELECT p.*, rt.registration_type 
                                                              FROM practitioner p
                                                              LEFT JOIN registration_type_master rt ON p.registration_type_id = rt.registration_type_id
                                                              WHERE p.registration_status = 'Pending'
                                                              ORDER BY p.practitioner_id DESC";
                                                $pending_result = $conn->query($pending_sql);
                                                if ($pending_result && $pending_result->num_rows > 0):
                                                    while($row = $pending_result->fetch_assoc()):
                                                ?>
                                                <tr>
                                                    <td><input type="checkbox" name="selected_practitioners[]" value="<?php echo $row['practitioner_id']; ?>" class="practitioner-checkbox"></td>
                                                    <td><?php echo $row['practitioner_id']; ?></td>
                                                    <td><?php echo htmlspecialchars($row['practitioner_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['registration_type']); ?></td>
                                                    <td>
                                                        <div><i class="fas fa-envelope text-muted mr-1"></i> <?php echo htmlspecialchars($row['practitioner_email_id']); ?></div>
                                                        <div><i class="fas fa-phone text-muted mr-1"></i> <?php echo htmlspecialchars($row['practitioner_mobile_number']); ?></div>
                                                    </td>
                                                    <td><?php echo date('d M Y', strtotime($row['registration_date'])); ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="view_practitioner.php?id=<?php echo $row['practitioner_id']; ?>" class="btn btn-sm btn-info" title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="?action=approve&id=<?php echo $row['practitioner_id']; ?>" class="btn btn-sm btn-success" title="Approve" onclick="return confirm('Are you sure you want to approve this practitioner?');">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                            <a href="?action=reject&id=<?php echo $row['practitioner_id']; ?>" class="btn btn-sm btn-danger" title="Reject" onclick="return confirm('Are you sure you want to reject this practitioner?');">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                            <button class="btn btn-sm btn-warning send-remark-btn" title="Send Remark" 
                                                                data-email="<?php echo htmlspecialchars($row['practitioner_email_id']); ?>" 
                                                                data-name="<?php echo htmlspecialchars($row['practitioner_name']); ?>" 
                                                                data-id="<?php echo $row['practitioner_id']; ?>">
                                                                <i class="fas fa-comment"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endwhile; ?>
                                                <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center">No pending applications found</td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Tab -->
                    <div class="tab-pane fade" id="approved" role="tabpanel">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Approved Applications</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Registration Type</th>
                                                <th>Contact</th>
                                                <th>Registration Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $approved_sql = "SELECT p.*, rt.registration_type 
                                                           FROM practitioner p
                                                           LEFT JOIN registration_type_master rt ON p.registration_type_id = rt.registration_type_id
                                                           WHERE p.registration_status = 'Approved'
                                                           ORDER BY p.practitioner_id DESC";
                                            $approved_result = $conn->query($approved_sql);
                                            if ($approved_result && $approved_result->num_rows > 0):
                                                while($row = $approved_result->fetch_assoc()):
                                            ?>
                                            <tr>
                                                <td><?php echo $row['practitioner_id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['practitioner_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['registration_type']); ?></td>
                                                <td>
                                                    <div><i class="fas fa-envelope text-muted mr-1"></i> <?php echo htmlspecialchars($row['practitioner_email_id']); ?></div>
                                                    <div><i class="fas fa-phone text-muted mr-1"></i> <?php echo htmlspecialchars($row['practitioner_mobile_number']); ?></div>
                                                </td>
                                                <td><?php echo date('d M Y', strtotime($row['registration_date'])); ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="view_practitioner.php?id=<?php echo $row['practitioner_id']; ?>" class="btn btn-sm btn-info" title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-warning send-remark-btn" title="Send Remark" 
                                                            data-email="<?php echo htmlspecialchars($row['practitioner_email_id']); ?>" 
                                                            data-name="<?php echo htmlspecialchars($row['practitioner_name']); ?>" 
                                                            data-id="<?php echo $row['practitioner_id']; ?>">
                                                            <i class="fas fa-comment"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                            <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No approved applications found</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Tab -->
                    <div class="tab-pane fade" id="active" role="tabpanel">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Active Practitioners</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Registration Type</th>
                                                <th>Contact</th>
                                                <th>Registration Number</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $active_sql = "SELECT p.*, rt.registration_type 
                                                         FROM practitioner p
                                                         LEFT JOIN registration_type_master rt ON p.registration_type_id = rt.registration_type_id
                                                         WHERE p.registration_status = 'Active'
                                                         ORDER BY p.practitioner_id DESC";
                                            $active_result = $conn->query($active_sql);
                                            if ($active_result && $active_result->num_rows > 0):
                                                while($row = $active_result->fetch_assoc()):
                                            ?>
                                            <tr>
                                                <td><?php echo $row['practitioner_id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['practitioner_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['registration_type']); ?></td>
                                                <td>
                                                    <div><i class="fas fa-envelope text-muted mr-1"></i> <?php echo htmlspecialchars($row['practitioner_email_id']); ?></div>
                                                    <div><i class="fas fa-phone text-muted mr-1"></i> <?php echo htmlspecialchars($row['practitioner_mobile_number']); ?></div>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['registration_number']); ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="view_practitioner.php?id=<?php echo $row['practitioner_id']; ?>" class="btn btn-sm btn-info" title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-warning send-remark-btn" title="Send Remark" 
                                                            data-email="<?php echo htmlspecialchars($row['practitioner_email_id']); ?>" 
                                                            data-name="<?php echo htmlspecialchars($row['practitioner_name']); ?>" 
                                                            data-id="<?php echo $row['practitioner_id']; ?>">
                                                            <i class="fas fa-comment"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                            <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No active practitioners found</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inactive/Rejected Tab -->
                    <div class="tab-pane fade" id="inactive" role="tabpanel">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Inactive/Rejected Applications</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Registration Type</th>
                                                <th>Contact</th>
                                                <th>Registration Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $inactive_sql = "SELECT p.*, rt.registration_type 
                                                           FROM practitioner p
                                                           LEFT JOIN registration_type_master rt ON p.registration_type_id = rt.registration_type_id
                                                           WHERE p.registration_status = 'Inactive'
                                                           ORDER BY p.practitioner_id DESC";
                                            $inactive_result = $conn->query($inactive_sql);
                                            if ($inactive_result && $inactive_result->num_rows > 0):
                                                while($row = $inactive_result->fetch_assoc()):
                                            ?>
                                            <tr>
                                                <td><?php echo $row['practitioner_id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['practitioner_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['registration_type']); ?></td>
                                                <td>
                                                    <div><i class="fas fa-envelope text-muted mr-1"></i> <?php echo htmlspecialchars($row['practitioner_email_id']); ?></div>
                                                    <div><i class="fas fa-phone text-muted mr-1"></i> <?php echo htmlspecialchars($row['practitioner_mobile_number']); ?></div>
                                                </td>
                                                <td><?php echo date('d M Y', strtotime($row['registration_date'])); ?></td>
                                                <td><span class="badge badge-danger">Inactive</span></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="view_practitioner.php?id=<?php echo $row['practitioner_id']; ?>" class="btn btn-sm btn-info" title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-warning send-remark-btn" title="Send Remark" 
                                                            data-email="<?php echo htmlspecialchars($row['practitioner_email_id']); ?>" 
                                                            data-name="<?php echo htmlspecialchars($row['practitioner_name']); ?>" 
                                                            data-id="<?php echo $row['practitioner_id']; ?>">
                                                            <i class="fas fa-comment"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                            <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No inactive/rejected applications found</td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Email Modal -->
    <div class="modal fade" id="remarkModal" tabindex="-1" role="dialog" aria-labelledby="remarkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="remarkModalLabel">
                        <i class="fas fa-envelope mr-2"></i> Send Remark to <span id="recipientNameTitle">Practitioner</span>
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
                                <input type="email" class="form-control-plaintext" id="recipientEmail" readonly>
                            </div>
                            <div class="form-group mb-0">
                                <label for="recipientName" class="font-weight-bold text-muted">
                                    <i class="fas fa-user mr-1"></i> Recipient:
                                </label>
                                <input type="text" class="form-control-plaintext" id="recipientName" readonly>
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
                        <input type="hidden" id="practitionerId" value="">
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
        
        // Select all checkbox
        document.getElementById('select-all-pending').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.practitioner-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
        
        // Update "select all" checkbox state
        const practitionerCheckboxes = document.querySelectorAll('.practitioner-checkbox');
        practitionerCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const selectAll = document.getElementById('select-all-pending');
                const allChecked = [...practitionerCheckboxes].every(c => c.checked);
                const anyChecked = [...practitionerCheckboxes].some(c => c.checked);
                selectAll.checked = allChecked;
                selectAll.indeterminate = anyChecked && !allChecked;
            });
        });

        // Handle Remark button click
        $('.send-remark-btn').click(function(e) {
            e.preventDefault(); // Prevent default behavior
            
            // Reset form and UI elements
            $('#remarkForm').show();
            $('#loadingSpinner').hide();
            $('#successAnimation').hide();
            $('#errorMessage').hide();
            $('#sendRemarkBtn').prop('disabled', false);
            
            // Set recipient information
            var email = $(this).data('email');
            var name = $(this).data('name');
            var practitionerId = $(this).data('id');
            
            // Make sure values are populated and log them to console for debugging
            console.log("Email: " + email + ", Name: " + name + ", ID: " + practitionerId);
            
            $('#recipientEmail').val(email);
            $('#recipientName').val(name);
            $('#recipientNameTitle').text(name);
            $('#practitionerId').val(practitionerId);
            $('#remarkMessage').val(''); // Clear previous message
            
            // Make sure the modal shows after updating content
            setTimeout(function() {
                $('#remarkModal').modal('show');
            }, 100);
        });

        // Send Remark via AJAX
        $('#sendRemarkBtn').click(function() {
            var recipientEmail = $('#recipientEmail').val();
            var recipientName = $('#recipientName').val();
            var message = $('#remarkMessage').val();
            var practitionerId = $('#practitionerId').val();

            // Validate message
            if (!message.trim()) {
                $('#errorMessage').show().find('#errorText').text('Please enter a remark message.');
                return;
            }

            // Show loading spinner, hide error message
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
    </script>
</body>
</html> 