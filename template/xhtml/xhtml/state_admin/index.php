<?php
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Start session to maintain user state across pages
session_start();

// Include database configuration file
require_once '../config/config.php';

// Include email templates and functions
require_once 'email-templates.php';

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
    
	    <!-- Title -->
	<title>W3CRM - Bootstrap Admin Dashboard Template</title>
	
	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="DexignZone">
	<meta name="robots" content="index, follow">
	<meta name="format-detection" content="telephone=no">
	
	<meta name="keywords" content="admin, admin dashboard, bootstrap, template, analytics, dark mode, modern, responsive admin dashboard, sass, ui kit">
	<meta name="description" content="Elevate your administrative efficiency and enhance productivity with the W3CRM Bootstrap Admin Dashboard Template. Designed to streamline your tasks, this powerful tool provides a user-friendly interface, robust features, and customizable options, making it the ideal choice for managing your data and operations with ease.">
	
	<meta property="og:title" content="W3CRM - Bootstrap Admin Dashboard Template">
	<meta property="og:description" content="Elevate your administrative efficiency and enhance productivity with the W3CRM Bootstrap Admin Dashboard Template. Designed to streamline your tasks, this powerful tool provides a user-friendly interface, robust features, and customizable options, making it the ideal choice for managing your data and operations with ease.">
	<meta property="og:image" content="https://w3crm.dexignzone.com/xhtml/social-image.png">
	
	<!-- TWITTER META -->
	<meta name="twitter:title" content="W3CRM - Bootstrap Admin Dashboard Template">
	<meta name="twitter:description" content="Elevate your administrative efficiency and enhance productivity with the W3CRM Bootstrap Admin Dashboard Template. Designed to streamline your tasks, this powerful tool provides a user-friendly interface, robust features, and customizable options, making it the ideal choice for managing your data and operations with ease.">
	<meta name="twitter:image" content="https://w3crm.dexignzone.com/xhtml/social-image.png">
	<meta name="twitter:card" content="summary_large_image">
	
	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="../assets/images/favicon.png">
	
	<!-- MOBILE SPECIFIC -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Canonical URL -->
	<link rel="canonical" href="https://w3crm.dexignzone.com/xhtml/task.html">
	
	<!-- Plugins Stylesheet -->
	<link href="../assets/css/jquery.localizationTool.css" rel="stylesheet">
	<link href="../assets/vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
	
	
	<link href="../assets/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
	<link href="../assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">	
	<link href="../assets/vendor/tagify/tagify.css" rel="stylesheet">
	
    <!-- Style CSS -->
	<link href="../assets/css/plugins.css" rel="stylesheet">
	<link href="../assets/css/style.css" rel="stylesheet">
	
</head>
<body>

    	<!-- Start - Preloader -->
	<div id="preloader">
		<div class="lds-ripple">
			<div></div>
			<div></div>
            </div>
	</div>
	<!-- End - Preloader -->

	<!-- Start - Main Wrapper -->
    <div id="main-wrapper">
        
		<!--**********************************
		Nav header start
		***********************************-->
		<div class="nav-header">
			<a href="index.php" class="brand-logo" aria-label="Brand Logo">
				<svg class="logo-abbr" width="39" height="23" viewBox="0 0 39 23" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path class="w3" d="M32.0362 22H19.0466L20.7071 18.7372C20.9559 18.2484 21.455 17.9378 22.0034 17.9305L31.1036 17.8093C33.0753 17.6497 33.6571 15.9246 33.7015 15.0821C33.7015 13.2196 32.1916 12.5765 31.4367 12.4878H23.7095L25.9744 8.49673H30.4375C31.8763 8.3903 32.236 7.03332 32.236 6.36814C32.3426 4.93133 30.9482 4.61648 30.2376 4.63865H28.6955C28.2646 4.63865 27.9788 4.19212 28.1592 3.8008L29.7047 0.44798C31.0903 0.394765 32.8577 0.780573 33.5683 0.980129C38.6309 3.42801 37.0988 7.98676 35.6999 9.96014C38.1513 11.9291 38.4976 14.3282 38.3644 15.2816C38.098 20.1774 34.0346 21.8005 32.0362 22Z" fill="var(--primary)"/>
					<path class="react-w" d="M9.89261 21.4094L0 2.80536H4.86354C5.41354 2.80536 5.91795 3.11106 6.17246 3.59864L12.4032 15.5355C12.6333 15.9762 12.6261 16.5031 12.3842 16.9374L9.89261 21.4094Z" fill="white"/>
					<path class="react-w" d="M17.5705 21.4094L7.67786 2.80536H12.5372C13.0894 2.80536 13.5954 3.11351 13.8489 3.60412L20.302 16.0939L17.5705 21.4094Z" fill="white"/>
					<path class="react-w" d="M17.6443 21.4094L28.2751 0H23.4513C22.8806 0 22.361 0.328884 22.1168 0.844686L14.8271 16.2416L17.6443 21.4094Z" fill="white"/>
					<path class="react-w" d="M9.89261 21.4094L0 2.80536H4.86354C5.41354 2.80536 5.91795 3.11106 6.17246 3.59864L12.4032 15.5355C12.6333 15.9762 12.6261 16.5031 12.3842 16.9374L9.89261 21.4094Z" stroke="white"/>
					<path class="react-w" d="M17.5705 21.4094L7.67786 2.80536H12.5372C13.0894 2.80536 13.5954 3.11351 13.8489 3.60412L20.302 16.0939L17.5705 21.4094Z" stroke="white"/>
					<path class="react-w" d="M17.6443 21.4094L28.2751 0H23.4513C22.8806 0 22.361 0.328884 22.1168 0.844686L14.8271 16.2416L17.6443 21.4094Z" stroke="white"/>
				</svg>
				<svg class="brand-title" width="47" height="16" viewBox="0 0 47 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M8.36 15.2C7.2933 15.2 6.3 15.0267 5.38 14.68C4.4733 14.32 3.68 13.82 3 13.18C2.3333 12.5267 1.8133 11.76 1.44 10.88C1.0667 9.99999 0.880005 9.03999 0.880005 7.99999C0.880005 6.95999 1.0667 5.99999 1.44 5.11999C1.8133 4.23999 2.34 3.47999 3.02 2.83999C3.7 2.18666 4.49331 1.68666 5.40001 1.33999C6.30671 0.979988 7.3 0.799988 8.38 0.799988C9.5267 0.799988 10.5733 0.999988 11.52 1.39999C12.4667 1.78666 13.2667 2.36666 13.92 3.13999L12.24 4.71999C11.7333 4.17332 11.1667 3.76666 10.54 3.49999C9.9133 3.21999 9.2333 3.07999 8.5 3.07999C7.7667 3.07999 7.0933 3.19999 6.48 3.43999C5.88 3.67999 5.35331 4.01999 4.90001 4.45999C4.46001 4.89999 4.1133 5.41999 3.86 6.01999C3.62 6.61999 3.5 7.27999 3.5 7.99999C3.5 8.71999 3.62 9.37999 3.86 9.97999C4.1133 10.58 4.46001 11.1 4.90001 11.54C5.35331 11.98 5.88 12.32 6.48 12.56C7.0933 12.8 7.7667 12.92 8.5 12.92C9.2333 12.92 9.9133 12.7867 10.54 12.52C11.1667 12.24 11.7333 11.82 12.24 11.26L13.92 12.86C13.2667 13.62 12.4667 14.2 11.52 14.6C10.5733 15 9.52 15.2 8.36 15.2ZM16.4113 15V0.999988H22.1713C23.4113 0.999988 24.4713 1.19999 25.3513 1.59999C26.2446 1.99999 26.9313 2.57332 27.4113 3.31999C27.8913 4.06666 28.1313 4.95332 28.1313 5.97999C28.1313 7.00669 27.8913 7.89329 27.4113 8.63999C26.9313 9.37329 26.2446 9.93999 25.3513 10.34C24.4713 10.7267 23.4113 10.92 22.1713 10.92H17.8513L19.0113 9.73999V15H16.4113ZM25.5713 15L22.0313 9.91999H24.8112L28.3713 15H25.5713ZM19.0113 10.02L17.8513 8.77999H22.0513C23.1979 8.77999 24.0579 8.53329 24.6312 8.03999C25.2179 7.54669 25.5113 6.85999 25.5113 5.97999C25.5113 5.08666 25.2179 4.39999 24.6312 3.91999C24.0579 3.43999 23.1979 3.19999 22.0513 3.19999H17.8513L19.0113 1.91999V10.02ZM31.0402 15V0.999988H33.1802L39.3002 11.22H38.1802L44.2002 0.999988H46.3402L46.3602 15H43.9002L43.8802 4.85999H44.4002L39.2802 13.4H38.1202L32.9202 4.85999H33.5202V15H31.0402Z" fill="black"/>
				</svg>
			</a>
			<div class="nav-control">
				<div class="hamburger">
					<span class="line"></span>
					<span class="line"></span>
					<span class="line"></span>
				</div>
			</div>
		</div>
		<!--**********************************
		Nav header end
		***********************************-->
		
		<!-- Start - Sidebar Chat Box  -->
		<div class="chatbox">
			<div class="chatbox-close"></div>
			<div class="clearfix">
				<ul class="nav nav-underline">
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="#notes">Notes</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="#alerts">Alerts</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" data-bs-toggle="tab" href="#chat">Chat</a>
					</li>
				</ul>
				<div class="tab-content">
					<!-- Chat Box Content from task.html - omitted for brevity -->
					<!-- You can copy the chat box content from task.html if needed -->
				</div>
			</div>
		</div>
		<!-- End - Sidebar Chat Box  -->
		
		<!-- Start - Header -->
		<div class="header">
			<div class="header-content">
				<nav class="navbar navbar-expand">
					<div class="collapse navbar-collapse justify-content-between">
						<div class="header-left">
							<form>
								<div class="header-search input-group">
									<span class="input-group-text">
										<button class="bg-transparent border-0" aria-label="Header Search">
											<svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
												<circle cx="8.78605" cy="8.78605" r="8.23951" stroke="var(--bs-body-color)" stroke-linecap="round" stroke-linejoin="round"/>
												<path d="M14.5168 14.9447L17.7471 18.1667" stroke="var(--bs-body-color)" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
										</button>
									</span>
									<input type="text" class="form-control" placeholder="Search">
								</div>
							</form>	
						</div>
						<ul class="navbar-nav header-right">
							<li class="nav-item dropdown header-profile-dropdown">
								<a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									<div class="profile-head">
										<div class="profile-media">
											<img src="../assets/images/tab/1.jpg" alt="">
										</div>
										<div class="header-info">
											<h6 class="author-name"><?php echo $_SESSION['admin_name'] ?? 'Admin'; ?></h6>
											<small><?php echo $_SESSION['admin_email'] ?? 'admin@example.com'; ?></small>
										</div>
									</div>
								</a>
								<ul class="dropdown-menu dropdown-menu-end">
									<li>
										<div class="py-2 d-flex px-3">
											<img src="../assets/images/tab/1.jpg" class="avatar avatar-sm rounded-circle" alt="">
											<div class="ms-2">
												<h6 class="mb-0"><?php echo $_SESSION['admin_name'] ?? 'Admin'; ?></h6>
												<small>Administrator</small>
											</div>	
										</div>
									</li>
									<li><hr class="dropdown-divider"></li>
									<li>
										<a class="dropdown-item" href="profile.php">
											<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" clip-rule="evenodd" d="M11.9848 15.3462C8.11714 15.3462 4.81429 15.931 4.81429 18.2729C4.81429 20.6148 8.09619 21.2205 11.9848 21.2205C15.8524 21.2205 19.1543 20.6348 19.1543 18.2938C19.1543 15.9529 15.8733 15.3462 11.9848 15.3462Z" stroke="var(--bs-primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
												<path fill-rule="evenodd" clip-rule="evenodd" d="M11.9848 12.0059C14.5229 12.0059 16.58 9.94779 16.58 7.40969C16.58 4.8716 14.5229 2.81445 11.9848 2.81445C9.44667 2.81445 7.38857 4.8716 7.38857 7.40969C7.38 9.93922 9.42381 11.9973 11.9524 12.0059H11.9848Z" stroke="var(--bs-primary)" stroke-width="1.42857" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
											<span class="ms-2">Profile</span>
										</a>
									</li>
									<li>
										<a class="dropdown-item" href="settings.php">
											<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" clip-rule="evenodd" d="M20.8066 7.62355L20.1842 6.54346C19.6576 5.62954 18.4907 5.31426 17.5755 5.83866V5.83866C17.1399 6.09528 16.6201 6.16809 16.1307 6.04103C15.6413 5.91396 15.2226 5.59746 14.9668 5.16131C14.8023 4.88409 14.7139 4.56833 14.7105 4.24598V4.24598C14.7254 3.72916 14.5304 3.22834 14.17 2.85761C13.8096 2.48688 13.3145 2.2778 12.7975 2.27802H11.5435C11.0369 2.27801 10.5513 2.47985 10.194 2.83888C9.83666 3.19791 9.63714 3.68453 9.63958 4.19106V4.19106C9.62457 5.23686 8.77245 6.07675 7.72654 6.07664C7.40418 6.07329 7.08843 5.98488 6.8112 5.82035V5.82035C5.89603 5.29595 4.72908 5.61123 4.20251 6.52516L3.53432 7.62355C3.00838 8.53633 3.31937 9.70255 4.22997 10.2322V10.2322C4.82187 10.574 5.1865 11.2055 5.1865 11.889C5.1865 12.5725 4.82187 13.204 4.22997 13.5457V13.5457C3.32053 14.0719 3.0092 15.2353 3.53432 16.1453V16.1453L4.16589 17.2345C4.41262 17.6797 4.82657 18.0082 5.31616 18.1474C5.80575 18.2865 6.33061 18.2248 6.77459 17.976V17.976C7.21105 17.7213 7.73116 17.6515 8.21931 17.7821C8.70746 17.9128 9.12321 18.233 9.37413 18.6716C9.53867 18.9488 9.62708 19.2646 9.63043 19.5869V19.5869C9.63043 20.6435 10.4869 21.5 11.5435 21.5H12.7975C13.8505 21.5 14.7055 20.6491 14.7105 19.5961V19.5961C14.7081 19.088 14.9088 18.6 15.2681 18.2407C15.6274 17.8814 16.1154 17.6806 16.6236 17.6831C16.9451 17.6917 17.2596 17.7797 17.5389 17.9393V17.9393C18.4517 18.4653 19.6179 18.1543 20.1476 17.2437V17.2437L20.8066 16.1453C21.0617 15.7074 21.1317 15.1859 21.0012 14.6963C20.8706 14.2067 20.5502 13.7893 20.111 13.5366V13.5366C19.6717 13.2839 19.3514 12.8665 19.2208 12.3769C19.0902 11.8872 19.1602 11.3658 19.4153 10.9279C19.5812 10.6383 19.8213 10.3981 20.111 10.2322V10.2322C21.0161 9.70283 21.3264 8.54343 20.8066 7.63271V7.63271V7.62355Z" stroke="var(--bs-primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
												<circle cx="12.1747" cy="11.889" r="2.63616" stroke="var(--bs-primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
											</svg>
											<span class="ms-2">Settings </span>
										</a>
									</li>
									<li><hr class="dropdown-divider"></li>
									<li>
										<a href="logout.php" class="dropdown-item">
											<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--bs-danger)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
												<path stroke="var(--bs-danger)" d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
												<polyline stroke="var(--bs-danger)" points="16 17 21 12 16 7"></polyline>
												<line x1="21" y1="12" x2="9" y2="12"></line>
											</svg>
											<span class="ms-2 text-danger">Logout </span>
										</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</div>
		<!-- End - Header -->
		
		<!-- Start - Sidebar Navigation -->
		<div class="deznav">
			<div class="deznav-scroll">
				<ul class="metismenu" id="menu">
					<li class="menu-title">KSAHC ADMIN PANEL</li>
					<li>
						<a href="index.php" aria-expanded="false">
							<div class="menu-icon">
								<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M2.5 7.49999L10 1.66666L17.5 7.49999V16.6667C17.5 17.1087 17.3244 17.5326 17.0118 17.8452C16.6993 18.1577 16.2754 18.3333 15.8333 18.3333H4.16667C3.72464 18.3333 3.30072 18.1577 2.98816 17.8452C2.67559 17.5326 2.5 17.1087 2.5 16.6667V7.49999Z" stroke="#888888" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M7.5 18.3333V10H12.5V18.3333" stroke="#888888" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</div>	
							<span class="nav-text">Dashboard</span>
						</a>
					</li>
					<li>
						<a href="practitioners.php" aria-expanded="false">
							<div class="menu-icon">
								<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M10.986 14.0673C7.4407 14.0673 4.41309 14.6034 4.41309 16.7501C4.41309 18.8969 7.4215 19.4521 10.986 19.4521C14.5313 19.4521 17.5581 18.9152 17.5581 16.7693C17.5581 14.6234 14.5505 14.0673 10.986 14.0673Z" stroke="#888888" stroke-linecap="round" stroke-linejoin="round"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M10.986 11.0054C13.3126 11.0054 15.1983 9.11881 15.1983 6.79223C15.1983 4.46564 13.3126 2.57993 10.986 2.57993C8.65944 2.57993 6.77285 4.46564 6.77285 6.79223C6.76499 9.11096 8.63849 10.9975 10.9563 11.0054H10.986Z" stroke="#888888" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
            </div>
							<span class="nav-text">Practitioners</span>
						</a>
					</li>
					<li>
						<a href="colleges.php" aria-expanded="false">
							<div class="menu-icon">
								<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M15.8381 12.7317C16.4566 12.7317 16.9757 13.2422 16.8811 13.853C16.3263 17.4463 13.2502 20.1143 9.54009 20.1143C5.43536 20.1143 2.10834 16.7873 2.10834 12.6835C2.10834 9.30245 4.67693 6.15297 7.56878 5.44087C8.19018 5.28745 8.82702 5.72455 8.82702 6.36429C8.82702 10.6987 8.97272 11.8199 9.79579 12.4297C10.6189 13.0396 11.5867 12.7317 15.8381 12.7317Z" stroke="#888888" stroke-linecap="round" stroke-linejoin="round"/>
									<path fill-rule="evenodd" clip-rule="evenodd" d="M19.8848 9.1223C19.934 6.33756 16.5134 1.84879 12.345 1.92599C12.0208 1.93178 11.7612 2.20195 11.7468 2.5252C11.6416 4.81493 11.7834 7.78204 11.8626 9.12713C11.8867 9.5459 12.2157 9.87493 12.6335 9.89906C14.0162 9.97818 17.0914 10.0862 19.3483 9.74467C19.6552 9.69835 19.88 9.43204 19.8848 9.1223Z" stroke="#888888" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
        </div>
							<span class="nav-text">Colleges</span>
						</a>
					</li>
					<li>
						<a href="universities.php" aria-expanded="false">
							<div class="menu-icon">
								<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M6.64111 13.5497L9.38482 9.9837L12.5145 12.4421L15.1995 8.97684" stroke="#888888" stroke-linecap="round" stroke-linejoin="round"/>
									<ellipse cx="18.3291" cy="3.85021" rx="1.76201" ry="1.76201" stroke="#888888" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M13.6808 2.86012H7.01867C4.25818 2.86012 2.54651 4.81512 2.54651 7.57561V14.9845C2.54651 17.7449 4.22462 19.6915 7.01867 19.6915H14.9058C17.6663 19.6915 19.3779 17.7449 19.3779 14.9845V8.53213" stroke="#888888" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</div>	
							<span class="nav-text">Universities</span>
						</a>
					</li>
					<li>
						<a href="settings.php" aria-expanded="false">
							<div class="menu-icon">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M20.8066 7.62355L20.1842 6.54346C19.6576 5.62954 18.4907 5.31426 17.5755 5.83866V5.83866C17.1399 6.09528 16.6201 6.16809 16.1307 6.04103C15.6413 5.91396 15.2226 5.59746 14.9668 5.16131C14.8023 4.88409 14.7139 4.56833 14.7105 4.24598V4.24598C14.7254 3.72916 14.5304 3.22834 14.17 2.85761C13.8096 2.48688 13.3145 2.2778 12.7975 2.27802H11.5435C11.0369 2.27801 10.5513 2.47985 10.194 2.83888C9.83666 3.19791 9.63714 3.68453 9.63958 4.19106V4.19106C9.62457 5.23686 8.77245 6.07675 7.72654 6.07664C7.40418 6.07329 7.08843 5.98488 6.8112 5.82035V5.82035C5.89603 5.29595 4.72908 5.61123 4.20251 6.52516L3.53432 7.62355C3.00838 8.53633 3.31937 9.70255 4.22997 10.2322V10.2322C4.82187 10.574 5.1865 11.2055 5.1865 11.889C5.1865 12.5725 4.82187 13.204 4.22997 13.5457V13.5457C3.32053 14.0719 3.0092 15.2353 3.53432 16.1453V16.1453L4.16589 17.2345C4.41262 17.6797 4.82657 18.0082 5.31616 18.1474C5.80575 18.2865 6.33061 18.2248 6.77459 17.976V17.976C7.21105 17.7213 7.73116 17.6515 8.21931 17.7821C8.70746 17.9128 9.12321 18.233 9.37413 18.6716C9.53867 18.9488 9.62708 19.2646 9.63043 19.5869V19.5869C9.63043 20.6435 10.4869 21.5 11.5435 21.5H12.7975C13.8505 21.5 14.7055 20.6491 14.7105 19.5961V19.5961C14.7081 19.088 14.9088 18.6 15.2681 18.2407C15.6274 17.8814 16.1154 17.6806 16.6236 17.6831C16.9451 17.6917 17.2596 17.7797 17.5389 17.9393V17.9393C18.4517 18.4653 19.6179 18.1543 20.1476 17.2437V17.2437L20.8066 16.1453C21.0617 15.7074 21.1317 15.1859 21.0012 14.6963C20.8706 14.2067 20.5502 13.7893 20.111 13.5366V13.5366C19.6717 13.2839 19.3514 12.8665 19.2208 12.3769C19.0902 11.8872 19.1602 11.3658 19.4153 10.9279C19.5812 10.6383 19.8213 10.3981 20.111 10.2322V10.2322C21.0161 9.70283 21.3264 8.54343 20.8066 7.63271V7.63271V7.62355Z" stroke="#888888" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
									<circle cx="12.1747" cy="11.889" r="2.63616" stroke="#888888" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</div>	
							<span class="nav-text">Settings</span>
						</a>
					</li>
					<li>
						<a href="logout.php" aria-expanded="false">
							<div class="menu-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--bs-danger)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
									<polyline points="16 17 21 12 16 7"></polyline>
									<line x1="21" y1="12" x2="9" y2="12"></line>
								</svg>
							</div>	
							<span class="nav-text">Logout</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<!-- End - Sidebar Navigation -->
        
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
    
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    
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



  <!-- Required Vendors -->
  <script src="../assets/vendor/global/global.min.js"></script>
	
	<!-- Script For Datatables -->
	<script src="../assets/vendor/datatables/js/jquery.dataTables.min.js"></script>
	<script src="../assets/vendor/datatables/js/dataTables.buttons.min.js"></script>
	<script src="../assets/vendor/datatables/js/buttons.html5.min.js"></script>
	<script src="../assets/vendor/datatables/js/jszip.min.js"></script>
	
	<!-- Script For Bootstrap Datepicker -->
	<script src="../assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
	
	<!-- Script For Dashboard -->
	<script src="../assets/js/dashboard/dashboard.js"></script>
	
	<!-- Script For Multiple Languages -->
	<script src="../assets/js/jquery.localizationTool.js"></script>
    <script src="../assets/js/translator.js"></script>
	
	<!-- Script For Custom JS -->
	<script src="../assets/js/deznav-init.js"></script>
    <script src="../assets/js/custom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" crossorigin="anonymous"></script>
</body>
</html> 