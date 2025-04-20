<?php
session_start();

// require_once '../config/connection.php';
// require_once '../config/utils.php';
// require_once '../config/mail-helper.php';
// require_once '../config/sms-helper.php';
require_once '../../config/config.php';

// Include email templates and functions for approval notifications
require_once '../email-templates.php';

date_default_timezone_set('Asia/Kolkata');

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
            $message = "Successfully " . ($bulk_action == 'approve' ? 'approved' : 'rejected') . " " . count($selected_practitioners) . " practitioners.";
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
            $message = "Practitioner has been " . ($action == 'approve' ? 'approved' : 'rejected') . ".";
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

if (empty($_SESSION['user_name'])) {

    echo "<script>location.href = 'index.php'</script>";
}

$username = $_SESSION['user_name'];

// $resPermissionCheck = mysqli_query($conn, "SELECT permission_id FROM 
//     permission_manager WHERE permission_username = '$username' AND 
//     (permission_edit_permission = 1 OR permission_view_permission = 1) AND 
//     permission_particular_id = 1");

// if (mysqli_num_rows($resPermissionCheck) == 0) {

//     echo "<script>swal({title: 'Failed', text: 'You do not have permission to access this page!', type: 'error'}).then(function(){window.location = 'home.php';});</script>";
// }

if (isset($_POST['password_submit'])) {

    $practitioner_password = trim($_POST['user_password']);
    $id = trim($_POST['id']);
    $practitioner_password = password_hash($practitioner_password, PASSWORD_BCRYPT);

    if(mysqli_query($conn, "UPDATE practitioner SET practitioner_password = '$practitioner_password'
        WHERE practitioner_id = '$id'")){

        echo "<script>swal({title: 'Success', text: 'Practitioner Pssword Changed successfully!', type: 'success'}).then(function(){window.location = 'manage-practitioner.php';});</script>";
    } else{
        
        echo "<script>swal('Failed!', 'Unable to process your request!', 'error');</script>";
    }
}

if (isset($_POST['add_submit'])) {

    $registration_type = addslashes(trim($_POST['registration_type']));
    $practitioner_mobile = addslashes(trim($_POST['practitioner_mobile']));

    // $registration_number = 1;
    
    // $resReg = mysqli_query($conn, "SELECT MAX(registration_number) AS registration_number FROM practitioner 
    //     WHERE registration_type_id = '$registration_type' AND NOT registration_status = 'Deleted'");
    
    // if(mysqli_num_rows($resReg)>0){

    //     $resReg = mysqli_fetch_assoc($resReg);

    //     $registration_number = $resReg['registration_number'] + 1;
    // }

    // $resRegCh = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE 
    //     (registration_number = '$registration_number' AND 
    //     registration_type_id = '$registration_type') OR (practitioner_mobile_number = '$practitioner_mobile') 
    //     AND NOT registration_status = 'Deleted'");

    // if (mysqli_num_rows($resRegCh) > 0) {

    //     echo "<script>swal({title: 'Failed', text: 'Registration or mobile number already in use!', type: 'error'}).then(function(){window.location = 'manage-practitioner.php';});</script>";
    // } else {
        
        $date = date('Y-m-d H:i:s');
        
        $registration_date = addslashes(trim($_POST['registration_date']));
        $practitioner_title = addslashes(trim($_POST['practitioner_title']));
        $practitioner_name = addslashes(trim(strtoupper($_POST['practitioner_name'])));
        $practitioner_dob = addslashes(trim($_POST['practitioner_dob']));
        $practitioner_gender = addslashes(trim($_POST['practitioner_gender']));
        $practitioner_spouse_name = null;
        if(!empty($_POST['practitioner_spouse_name'])){
            $practitioner_spouse_name = addslashes(trim(strtoupper($_POST['practitioner_spouse_name'])));
        }
        $practitioner_category = null;
        if(!empty($_POST['practitioner_category'])){
            $practitioner_category = trim($_POST['practitioner_category']);
        }
        $practitioner_birth_place = null;
        if(!empty($_POST['practitioner_birth_place'])){
            $practitioner_birth_place = addslashes(trim($_POST['practitioner_birth_place']));
        }
        $practitioner_nationality = null;
        if(!empty($_POST['practitioner_nationality'])){
            $practitioner_nationality = addslashes(trim($_POST['practitioner_nationality']));
        }
        $practitioner_email_id = addslashes(trim($_POST['practitioner_email_id']));
        $practitioner_adhar = addslashes(trim($_POST['practitioner_adhar']));
        $practitioner_pan = addslashes(trim($_POST['practitioner_pan']));
        $practitioner_vote_status = 'Eligible';
        $practitioner_password = password_hash(date_format(date_create($practitioner_dob), 'd/m/Y'), PASSWORD_BCRYPT);
        $status = 'Active';
        $remit_status = 'Yes';


        $res_get_type = mysqli_query($conn, "SELECT registration_type FROM registration_type_master WHERE registration_type_id = '$registration_type'");

        if (mysqli_num_rows($res_get_type) > 0) {
            $res_get_type = mysqli_fetch_assoc($res_get_type);

            $registration_number = 1;

            $resReg = mysqli_query($conn, "SELECT MAX(registration_number) AS registration_number FROM practitioner 
            WHERE registration_type_id = '$registration_type' AND NOT registration_status = 'Deleted'");

            if (mysqli_num_rows($resReg) > 0) {
                $resReg = mysqli_fetch_assoc($resReg);
                $registration_number = $resReg['registration_number'] + 1;
            }

            $resRegCh = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE
                (registration_number = '$registration_number' AND 
                registration_type_id = '$registration_type') OR (practitioner_mobile_number = '$practitioner_mobile') 
                AND NOT registration_status = 'Deleted'");

            if (mysqli_num_rows($resRegCh) > 0) {

                echo "<script>swal({title: 'Failed', text: 'Registration or mobile number already in use!', type: 'error'}).then(function(){window.location = 'manage-practitioner.php';});</script>";
            } else {

                $practitioner_username = $registration_number . " " . $res_get_type['registration_type'];

            $sql = "INSERT INTO practitioner (registration_number, registration_date, 
                registration_type_id, practitioner_title, practitioner_name, 
                practitioner_spouse_name, practitioner_birth_place, practitioner_birth_date, 
                practitioner_gender, practitioner_nationality, practitioner_email_id, 
                practitioner_mobile_number, practitioner_username, practitioner_password, 
                is_first_login, practitioner_created_by, practitioner_created_on, 
                practitioner_pan_number, category_id, practitioner_aadhar_number, 
                vote_status, registration_status) VALUES 
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param($stmt,'ssssssssssssssssssssss', $registration_number, 
                $registration_date, $registration_type, $practitioner_title, $practitioner_name, 
                $practitioner_spouse_name, $practitioner_birth_place, $practitioner_dob, $practitioner_gender, 
                $practitioner_nationality, $practitioner_email_id, $practitioner_mobile, $practitioner_username, 
                $practitioner_password, $remit_status, $username, $date, $practitioner_pan, $practitioner_category, $practitioner_adhar, 
                $practitioner_vote_status, $status);

            if (mysqli_stmt_execute($stmt)) {
                
                $practitioner_id = mysqli_insert_id($conn);

                $encodes = base64_encode($practitioner_id);
                $param = urlencode($encodes);

                $resRegCh1 = mysqli_query($conn, "SELECT practitioner_title, practitioner_name, practitioner_change_of_name FROM practitioner WHERE practitioner_id = $practitioner_id");
                    if ($resRegCh1 && mysqli_num_rows($resRegCh1) > 0) {
                        $resRegCh1= mysqli_fetch_assoc($resRegCh1);
                    }


               if (!empty($resRegCh1['practitioner_change_of_name'])) {
                    $name = $resRegCh1['practitioner_title'] . " " . $resRegCh1['practitioner_change_of_name'];
                } else {
                    $name = $resRegCh1['practitioner_title'] . " " . $resRegCh1['practitioner_name'];
                }

                $phone = $practitioner_mobile;
                $regno = $practitioner_username;
                $password = date_format(date_create($practitioner_dob), 'd/m/Y');

                // if (sendRenewalSMS($name, $phone, $regno, $password)) {
                    
                //     echo "<script>swal({title: 'Success', text: 'Practitioner added successfully!', type: 'success'}).then(function(){window.location = 'edit-personal.php?source=$param';});</script>";
                // } else 
                {
                    echo "<script>swal('Failed!', 'Unable to process your request!', 'error');</script>";
                }
            } else {
                echo "<script>swal('Failed!', 'Unable to process your request!', 'error');</script>";
            }
        }
    } else {
        echo "<script>swal('Failed!', 'Unable to process your request!', 'error');</script>";
    }
}
if (empty($_GET['page_no'])) {

    $page_no = 1;
} else {

    $page_no = (int)$_GET['page_no'];
}

if (empty($_GET['search_type'])) {

    $search_type = "";
} else {

    $search_type = $_GET['search_type'];
}

if (empty($_GET['search_number'])) {

    $search_number = "";
} else {

    $search_number = $_GET['search_number'];
}

if (empty($_GET['search_phone'])) {

    $search_phone = "";
} else {

    $search_phone = $_GET['search_phone'];
}

if (empty($_GET['from_date'])) {

    $from_date = "";
} else {

    $from_date = $_GET['from_date'];
}

if (empty($_GET['to_date'])) {

    $to_date = "";
} else {

    $to_date = $_GET['to_date'];
}

if (empty($_GET['search_status'])) {

    $search_status = "";
} else {

    $search_status = $_GET['search_status'];
}

$order_by = '';

if (!empty($_GET['order_by'])) {

    $order_by = $_GET['order_by'];
}


$total_records_per_page = isset($_GET['page_count']) && is_numeric($_GET['page_count']) ? (int)$_GET['page_count'] : 20;
$offset = ($page_no - 1) * $total_records_per_page;
$adjacents = "2";


$root_sql = "SELECT * FROM practitioner";

$page_sql = "SELECT COUNT(practitioner_id) As total_records FROM practitioner";

if (isset($_GET['filter'])) {

    

    if (!empty($search_phone)) {

        $keywords = "%" . $search_phone . "%";

        $root_sql .= " AND practitioner_mobile_number LIKE '$keywords'";
        $page_sql .= " AND practitioner_mobile_number LIKE '$keywords'";
    }

    if (!empty($search_type)) {

        $root_sql .= " AND registration_type_id = '$search_type'";
        $page_sql .= " AND registration_type_id = '$search_type'";
    }

    if (!empty($search_number)) {

        $keywords = "%" . $search_number . "%";

        $root_sql .= " AND (registration_number = '$search_number' OR practitioner_name LIKE '$keywords' OR practitioner_change_of_name LIKE '$keywords')";
        $page_sql .= " AND (registration_number = '$search_number' OR practitioner_name LIKE '$keywords' OR practitioner_change_of_name LIKE '$keywords')";
    }
    if (!empty($from_date) && !empty($to_date)) {


        $from_date = $from_date . ' 00:00:00';
        $to_date = $to_date . ' 23:59:59';

        $root_sql .= " AND registration_date BETWEEN '$from_date' AND '$to_date'";
        $page_sql .= " AND registration_date BETWEEN '$from_date' AND '$to_date'";
    } else if (!empty($from_date)) {

        $from_date = $from_date . ' 00:00:00';

        $root_sql .= " AND registration_date >= '$from_date'";
        $page_sql .= " AND registration_date >= '$from_date'";
    } else if (!empty($to_date)) {

        $to_date = $to_date . ' 23:59:59';
        
        $root_sql .= " AND registration_date <= '$to_date'";
        $page_sql .= " AND registration_date <= '$to_date'";
    }

    if (!empty($search_status)) {

        $root_sql .= " AND registration_status = '$search_status'";
        $page_sql .= " AND registration_status = '$search_status'";
    }
}

if ($order_by == 'date_desc') {

    $root_sql .= " ORDER BY registration_date DESC";
    $page_sql .= " ORDER BY registration_date DESC";
} else {

    $root_sql .= " ORDER BY practitioner_created_on DESC";
    $page_sql .= " ORDER BY practitioner_created_on DESC";
}

$result_count = mysqli_query($conn, $page_sql);
$total_records = mysqli_fetch_array($result_count);
$total_records = $total_records['total_records'];
$total_no_of_pages = ceil($total_records / $total_records_per_page);
$second_last = $total_no_of_pages - 1;

$sql = $root_sql . " LIMIT $offset, $total_records_per_page";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <?php require_once 'include/meta.php'; ?> -->
    <!-- <link href="./vendor/tagify/dist/tagify.css" rel="stylesheet"> -->
    <title><?php echo $site; ?> Practitioner</title>
</head>

<body data-typography="poppins" data-theme-version="light" data-layout="vertical" data-nav-headerbg="black"
    data-headerbg="color_1">

    <?php
    // require_once './include/pre-loader.php';

    if (empty($_SESSION['user_name'])) {

        echo "<script>location.href = 'index.php'</script>";
    }

    $username = $_SESSION['user_name'];

    // $resPermissionCheck = mysqli_query($conn, "SELECT permission_id FROM 
    //     permission_manager WHERE permission_username = '$username' AND 
    //     (permission_edit_permission = 1 OR permission_view_permission = 1) AND 
    //     permission_particular_id = 1");

    // if (mysqli_num_rows($resPermissionCheck) == 0) {

    //     echo "<script>swal({title: 'Failed', text: 'You do not have permission to access this page!', type: 'error'}).then(function(){window.location = 'home.php';});</script>";
    // }

    if (isset($_POST['password_submit'])) {

        $practitioner_password = trim($_POST['user_password']);
        $id = trim($_POST['id']);
        $practitioner_password = password_hash($practitioner_password, PASSWORD_BCRYPT);

        if(mysqli_query($conn, "UPDATE practitioner SET practitioner_password = '$practitioner_password'
            WHERE practitioner_id = '$id'")){

            echo "<script>swal({title: 'Success', text: 'Practitioner Pssword Changed successfully!', type: 'success'}).then(function(){window.location = 'manage-practitioner.php';});</script>";
        } else{
            
            echo "<script>swal('Failed!', 'Unable to process your request!', 'error');</script>";
        }
    }

    if (isset($_POST['add_submit'])) {

        $registration_type = addslashes(trim($_POST['registration_type']));
        $practitioner_mobile = addslashes(trim($_POST['practitioner_mobile']));

        // $registration_number = 1;
        
        // $resReg = mysqli_query($conn, "SELECT MAX(registration_number) AS registration_number FROM practitioner 
        //     WHERE registration_type_id = '$registration_type' AND NOT registration_status = 'Deleted'");
        
        // if(mysqli_num_rows($resReg)>0){

        //     $resReg = mysqli_fetch_assoc($resReg);

        //     $registration_number = $resReg['registration_number'] + 1;
        // }

        // $resRegCh = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE 
        //     (registration_number = '$registration_number' AND 
        //     registration_type_id = '$registration_type') OR (practitioner_mobile_number = '$practitioner_mobile') 
        //     AND NOT registration_status = 'Deleted'");

        // if (mysqli_num_rows($resRegCh) > 0) {

        //     echo "<script>swal({title: 'Failed', text: 'Registration or mobile number already in use!', type: 'error'}).then(function(){window.location = 'manage-practitioner.php';});</script>";
        // } else {
            
            $date = date('Y-m-d H:i:s');
            
            $registration_date = addslashes(trim($_POST['registration_date']));
            $practitioner_title = addslashes(trim($_POST['practitioner_title']));
            $practitioner_name = addslashes(trim(strtoupper($_POST['practitioner_name'])));
            $practitioner_dob = addslashes(trim($_POST['practitioner_dob']));
            $practitioner_gender = addslashes(trim($_POST['practitioner_gender']));
            $practitioner_spouse_name = null;
            if(!empty($_POST['practitioner_spouse_name'])){
                $practitioner_spouse_name = addslashes(trim(strtoupper($_POST['practitioner_spouse_name'])));
            }
            $practitioner_category = null;
            if(!empty($_POST['practitioner_category'])){
                $practitioner_category = trim($_POST['practitioner_category']);
            }
            $practitioner_birth_place = null;
            if(!empty($_POST['practitioner_birth_place'])){
                $practitioner_birth_place = addslashes(trim($_POST['practitioner_birth_place']));
            }
            $practitioner_nationality = null;
            if(!empty($_POST['practitioner_nationality'])){
                $practitioner_nationality = addslashes(trim($_POST['practitioner_nationality']));
            }
            $practitioner_email_id = addslashes(trim($_POST['practitioner_email_id']));
            $practitioner_adhar = addslashes(trim($_POST['practitioner_adhar']));
            $practitioner_pan = addslashes(trim($_POST['practitioner_pan']));
            $practitioner_vote_status = 'Eligible';
            $practitioner_password = password_hash(date_format(date_create($practitioner_dob), 'd/m/Y'), PASSWORD_BCRYPT);
            $status = 'Active';
            $remit_status = 'Yes';


            $res_get_type = mysqli_query($conn, "SELECT registration_type FROM registration_type_master WHERE registration_type_id = '$registration_type'");

            if (mysqli_num_rows($res_get_type) > 0) {
                $res_get_type = mysqli_fetch_assoc($res_get_type);

                $registration_number = 1;

                $resReg = mysqli_query($conn, "SELECT MAX(registration_number) AS registration_number FROM practitioner 
                WHERE registration_type_id = '$registration_type' AND NOT registration_status = 'Deleted'");

                if (mysqli_num_rows($resReg) > 0) {
                    $resReg = mysqli_fetch_assoc($resReg);
                    $registration_number = $resReg['registration_number'] + 1;
                }

                $resRegCh = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE
                    (registration_number = '$registration_number' AND 
                    registration_type_id = '$registration_type') OR (practitioner_mobile_number = '$practitioner_mobile') 
                    AND NOT registration_status = 'Deleted'");

                if (mysqli_num_rows($resRegCh) > 0) {

                    echo "<script>swal({title: 'Failed', text: 'Registration or mobile number already in use!', type: 'error'}).then(function(){window.location = 'manage-practitioner.php';});</script>";
                } else {

                    $practitioner_username = $registration_number . " " . $res_get_type['registration_type'];

                $sql = "INSERT INTO practitioner (registration_number, registration_date, 
                    registration_type_id, practitioner_title, practitioner_name, 
                    practitioner_spouse_name, practitioner_birth_place, practitioner_birth_date, 
                    practitioner_gender, practitioner_nationality, practitioner_email_id, 
                    practitioner_mobile_number, practitioner_username, practitioner_password, 
                    is_first_login, practitioner_created_by, practitioner_created_on, 
                    practitioner_pan_number, category_id, practitioner_aadhar_number, 
                    vote_status, registration_status) VALUES 
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = mysqli_prepare($conn, $sql);

                mysqli_stmt_bind_param($stmt,'ssssssssssssssssssssss', $registration_number, 
                    $registration_date, $registration_type, $practitioner_title, $practitioner_name, 
                    $practitioner_spouse_name, $practitioner_birth_place, $practitioner_dob, $practitioner_gender, 
                    $practitioner_nationality, $practitioner_email_id, $practitioner_mobile, $practitioner_username, 
                    $practitioner_password, $remit_status, $username, $date, $practitioner_pan, $practitioner_category, $practitioner_adhar, 
                    $practitioner_vote_status, $status);

                if (mysqli_stmt_execute($stmt)) {
                    
                    $practitioner_id = mysqli_insert_id($conn);

                    $encodes = base64_encode($practitioner_id);
                    $param = urlencode($encodes);

                    $resRegCh1 = mysqli_query($conn, "SELECT practitioner_title, practitioner_name, practitioner_change_of_name FROM practitioner WHERE practitioner_id = $practitioner_id");
                        if ($resRegCh1 && mysqli_num_rows($resRegCh1) > 0) {
                            $resRegCh1= mysqli_fetch_assoc($resRegCh1);
                        }


                   if (!empty($resRegCh1['practitioner_change_of_name'])) {
                        $name = $resRegCh1['practitioner_title'] . " " . $resRegCh1['practitioner_change_of_name'];
                    } else {
                        $name = $resRegCh1['practitioner_title'] . " " . $resRegCh1['practitioner_name'];
                    }

                    $phone = $practitioner_mobile;
                    $regno = $practitioner_username;
                    $password = date_format(date_create($practitioner_dob), 'd/m/Y');

                    // if (sendRenewalSMS($name, $phone, $regno, $password)) {
                        
                    //     echo "<script>swal({title: 'Success', text: 'Practitioner added successfully!', type: 'success'}).then(function(){window.location = 'edit-personal.php?source=$param';});</script>";
                    // } else 
                    {
                        echo "<script>swal('Failed!', 'Unable to process your request!', 'error');</script>";
                    }
                } else {
                    echo "<script>swal('Failed!', 'Unable to process your request!', 'error');</script>";
                }
            }
        } else {
            echo "<script>swal('Failed!', 'Unable to process your request!', 'error');</script>";
        }
    }
    if (empty($_GET['page_no'])) {

        $page_no = 1;
    } else {

        $page_no = (int)$_GET['page_no'];
    }

    if (empty($_GET['search_type'])) {

        $search_type = "";
    } else {

        $search_type = $_GET['search_type'];
    }

    if (empty($_GET['search_number'])) {

        $search_number = "";
    } else {

        $search_number = $_GET['search_number'];
    }

    if (empty($_GET['search_phone'])) {

        $search_phone = "";
    } else {

        $search_phone = $_GET['search_phone'];
    }

    if (empty($_GET['from_date'])) {

        $from_date = "";
    } else {

        $from_date = $_GET['from_date'];
    }

    if (empty($_GET['to_date'])) {

        $to_date = "";
    } else {

        $to_date = $_GET['to_date'];
    }

    if (empty($_GET['search_status'])) {

        $search_status = "";
    } else {

        $search_status = $_GET['search_status'];
    }

    $order_by = '';
    
    if (!empty($_GET['order_by'])) {

        $order_by = $_GET['order_by'];
    }


    $total_records_per_page = isset($_GET['page_count']) && is_numeric($_GET['page_count']) ? (int)$_GET['page_count'] : 20;
    $offset = ($page_no - 1) * $total_records_per_page;
    $adjacents = "2";


    $root_sql = "SELECT * FROM practitioner";

    $page_sql = "SELECT COUNT(practitioner_id) As total_records FROM practitioner";

    if (isset($_GET['filter'])) {

        

        if (!empty($search_phone)) {

            $keywords = "%" . $search_phone . "%";

            $root_sql .= " AND practitioner_mobile_number LIKE '$keywords'";
            $page_sql .= " AND practitioner_mobile_number LIKE '$keywords'";
        }

        if (!empty($search_type)) {

            $root_sql .= " AND registration_type_id = '$search_type'";
            $page_sql .= " AND registration_type_id = '$search_type'";
        }

        if (!empty($search_number)) {

            $keywords = "%" . $search_number . "%";

            $root_sql .= " AND (registration_number = '$search_number' OR practitioner_name LIKE '$keywords' OR practitioner_change_of_name LIKE '$keywords')";
            $page_sql .= " AND (registration_number = '$search_number' OR practitioner_name LIKE '$keywords' OR practitioner_change_of_name LIKE '$keywords')";
        }
        if (!empty($from_date) && !empty($to_date)) {


            $from_date = $from_date . ' 00:00:00';
            $to_date = $to_date . ' 23:59:59';

            $root_sql .= " AND registration_date BETWEEN '$from_date' AND '$to_date'";
            $page_sql .= " AND registration_date BETWEEN '$from_date' AND '$to_date'";
        } else if (!empty($from_date)) {

            $from_date = $from_date . ' 00:00:00';

            $root_sql .= " AND registration_date >= '$from_date'";
            $page_sql .= " AND registration_date >= '$from_date'";
        } else if (!empty($to_date)) {

            $to_date = $to_date . ' 23:59:59';
            
            $root_sql .= " AND registration_date <= '$to_date'";
            $page_sql .= " AND registration_date <= '$to_date'";
        }

        if (!empty($search_status)) {

            $root_sql .= " AND registration_status = '$search_status'";
            $page_sql .= " AND registration_status = '$search_status'";
        }
    }

    if ($order_by == 'date_desc') {

        $root_sql .= " ORDER BY registration_date DESC";
        $page_sql .= " ORDER BY registration_date DESC";
    } else {

        $root_sql .= " ORDER BY practitioner_created_on DESC";
        $page_sql .= " ORDER BY practitioner_created_on DESC";
    }

    $result_count = mysqli_query($conn, $page_sql);
    $total_records = mysqli_fetch_array($result_count);
    $total_records = $total_records['total_records'];
    $total_no_of_pages = ceil($total_records / $total_records_per_page);
    $second_last = $total_no_of_pages - 1;

    $sql = $root_sql . " LIMIT $offset, $total_records_per_page";
    ?>

    <div id="main-wrapper">

        <?php
        require_once './include/bar.php';
        require_once './include/header.php';
        require_once './include/sidebar.php';
        ?>

        <div class="content-body">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li>
                        <h5 class="bc-title">Manage Practitioner</h5>
                    </li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">
                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z"
                                    stroke="#2C2C2C" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="#2C2C2C" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            Home </a>
                    </li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Manage Practitioner</a></li>
                </ol>
                <a class="text-primary fs-16" data-bs-toggle="offcanvas" href="#filter" role="button"
                    aria-controls="filter">
                    Filter by
                </a>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header flex-wrap border-0">
                            <div class="d-flex align-items-center">
                                    <h4 class="heading mb-0" style="margin-right: 10px;">Manage Practitioner</h4>
                                    
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <form id="searchForm" method="GET" style="display: flex; align-items: center; gap: 0.5rem;">
                                        <select name="page_count" class="form-control form-control-sm"
                                            style="min-height: 2.3rem; padding: 0.25rem 0.5rem; font-size: 0.76563rem; border-radius: 0.5rem; border: 1px solid #CCCCCC; width: 150%;"
                                            onchange="this.form.submit();">
                                            <option value="20" <?php echo (isset($_GET['page_count']) && $_GET['page_count'] == '20') ? 'selected' : ''; ?>>-- 20 Records --</option>
                                            <option value="50" <?php echo (isset($_GET['page_count']) && $_GET['page_count'] == '50') ? 'selected' : ''; ?>>-- 50 Records --</option>
                                            <option value="100" <?php echo (isset($_GET['page_count']) && $_GET['page_count'] == '100') ? 'selected' : ''; ?>>-- 100 Records --</option>
                                            <option value="200" <?php echo (isset($_GET['page_count']) && $_GET['page_count'] == '200') ? 'selected' : ''; ?>>-- 200 Records --</option>
                                            <option value="500" <?php echo (isset($_GET['page_count']) && $_GET['page_count'] == '500') ? 'selected' : ''; ?>>-- 500 Records --</option>
                                        </select>
                                        <input autocomplete='off' name="search_number" maxlength="100"
                                            class="form-control form-control-sm" type="text"
                                            style="min-height: 2.3rem; padding: 0.25rem 0.5rem; font-size: 0.76563rem; border-radius: 0.5rem; border: 1px solid #CCCCCC; width: 200%;"
                                            value="<?php echo $search_number; ?>" placeholder="Registration No/Name">
                                        <button class="btn btn-primary btn-sm me-1" name="filter">Search</button>
                                        <a href="manage-practitioner.php"
                                            class="btn btn-outline-danger btn-sm">Clear</a>
                                    </form>
                                </div>

                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive active-projects style-1">
                                    <!-- Bulk Actions Form -->
                                    <form method="POST" id="bulk-action-form">
                                        <!-- Bulk Action Buttons -->
                                        <div class="bulk-actions mb-3 ms-3 mt-3">
                                            <div class="btn-group" role="group">
                                                <button type="submit" name="bulk_action" value="approve" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve the selected practitioners?');">
                                                    <i class="fas fa-check mr-1"></i> Approve Selected
                                                </button>
                                                <button type="submit" name="bulk_action" value="reject" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject the selected practitioners?');">
                                                    <i class="fas fa-times mr-1"></i> Reject Selected
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <!-- Display notifications -->
                                        <?php if(isset($message)): ?>
                                        <div class="alert alert-<?php echo $alert_type; ?> alert-dismissible fade show mx-3" role="alert">
                                            <i class="fas fa-<?php echo $alert_type == 'success' ? 'check-circle' : ($alert_type == 'danger' ? 'exclamation-circle' : 'info-circle'); ?> mr-2"></i>
                                            <?php echo $message; ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if(isset($auto_message)): ?>
                                        <div class="alert alert-<?php echo $auto_alert_type; ?> alert-dismissible fade show mx-3" role="alert">
                                            <i class="fas fa-sync-alt mr-2"></i>
                                            <?php echo $auto_message; ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <table class="table table-responsive-md">
                                        <thead>
                                            <tr>
                                                <th width="30"><input type="checkbox" id="select-all"></th>
                                                <th>Sl. No</th>
                                                <th>Reg Number</th>
                                                <th>Registration Date</th>
                                                <th>Practitioner Name</th>
                                                <th>Contact Number</th>
                                                <th>Registration Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $res = mysqli_query($conn, $sql);

                                            if (mysqli_num_rows($res) > 0) {

                                                $i = (($page_no - 1) * $total_records_per_page) + 1;

                                                while ($row = mysqli_fetch_assoc($res)) {

                                                    echo "<tr>";
                                                    echo "<td><input type=\"checkbox\" name=\"selected_practitioners[]\" value=\"" . $row['practitioner_id'] . "\" class=\"practitioner-checkbox\"></td>";
                                                    echo "<td>$i</td>";
                                                    echo "<td><a data-bs-toggle='modal' data-bs-target='#view$row[practitioner_id]' href='#' class='link-primary'>$row[practitioner_username]</a></td>";
                                                    $encodes = base64_encode($row['practitioner_id']);
                                                    $param = urlencode($encodes);
                                                    
                                                    echo "<td>";
                                                        if (!empty($row['registration_date'])) {
                                                            echo date_format(date_create($row['registration_date']), 'M d, Y');
                                                        }
                                                    echo "</td>";
                                                    echo "<td><h6>";
                                                    echo $row['practitioner_title'] . " ";
                                                    if (empty($row['practitioner_change_of_name'])) {
                                                        echo $row['practitioner_name'];
                                                    } else {
                                                        echo $row['practitioner_change_of_name'];
                                                    }
                                                    echo "</h6></td>";
                                                    echo "<td>$row[practitioner_mobile_number]</td>";
                                                    
                                                    echo "<td><span class='badge ";
                                                    if ($row['registration_status'] == 'Active') {
                                                        echo "badge-success";
                                                    } elseif ($row['registration_status'] == 'Approved') {
                                                        echo "badge-success";
                                                    } elseif ($row['registration_status'] == 'Inactive') {
                                                        echo "badge-danger";
                                                    } else {
                                                        echo "badge-warning";
                                                    }

                                                    echo " light border-0'>" . $row['registration_status'] . "</span></td>";

                                                    echo "<td class='text-end'>";
                                                        echo "<div class='btn-group'>";
                                                        // View button (modal)
                                                        echo "<a class='btn btn-info shadow btn-xs sharp me-1' data-bs-toggle='modal' data-bs-target='#view$row[practitioner_id]' href='#' title='View Details'><i class='fa fa-eye'></i></a>";
                                                        
                                                        // Edit button
                                                        echo "<a class='btn btn-primary shadow btn-xs sharp me-1' href='edit-personal.php?source=$param' title='Edit'><i class='fa fa-pencil'></i></a>";
                                                        
                                                        // Only show Approve/Reject if not already approved/active
                                                        if($row['registration_status'] != 'Active' && $row['registration_status'] != 'Approved') {
                                                            // Approve button
                                                            echo "<a href='?action=approve&id=$row[practitioner_id]' class='btn btn-success shadow btn-xs sharp me-1' title='Approve' onclick=\"return confirm('Are you sure you want to approve this practitioner?');\"><i class='fa fa-check'></i></a>";
                                                            
                                                            // Reject button
                                                            echo "<a href='?action=reject&id=$row[practitioner_id]' class='btn btn-danger shadow btn-xs sharp me-1' title='Reject' onclick=\"return confirm('Are you sure you want to reject this practitioner?');\"><i class='fa fa-times'></i></a>";
                                                        }
                                                        
                                                        // Send remark button 
                                                        echo "<button class='btn btn-warning shadow btn-xs sharp me-1 send-remark-btn' title='Send Remark' 
                                                            data-email='" . htmlspecialchars($row['practitioner_email_id']) . "' 
                                                            data-name='" . htmlspecialchars($row['practitioner_name']) . "' 
                                                            data-id='" . $row['practitioner_id'] . "'><i class='fa fa-comment'></i></button>";
                                                        
                                                        // Password button (lock)
                                                        echo "<a class='btn btn-secondary shadow btn-xs sharp me-1' data-bs-toggle='modal' data-bs-target='#lock$row[practitioner_id]' href='#' title='Change Password'><i class='fa fa-lock'></i></a>";
                                                        
                                                        echo "</div>";
                                                    echo "</td>";
                                                    
                                                    echo "</tr>";

                                                    $i++;

                                                    ?>

                                                    <div class="modal fade" id="lock<?php echo $row['practitioner_id']; ?>"
                                                        tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-center">
                                                            <form method="POST" enctype="multipart/form-data">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5" id="editLabel">Update
                                                                            password</h1>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-xl-12 mb-3">
                                                                                <label class="form-label">Password<span
                                                                                        class="text-danger">*</span></label>
                                                                                <input autocomplete='off' type="password"
                                                                                    maxlength="74" class="form-control"
                                                                                    name="user_password" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <input autocomplete='off' type="hidden" name="id"
                                                                        value="<?php echo $row['practitioner_id']; ?>" />
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger light"
                                                                            data-bs-dismiss="modal">Close</button>
                                                                        <button type="submit" name="password_submit"
                                                                            class="btn btn-primary">Save changes</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
        
                                                    <div class="modal fade" id="view<?php echo $row['practitioner_id']; ?>"
                                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-center modal-xl">
                                                            <form method="POST" enctype="multipart/form-data">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                                            <?php echo $row['practitioner_title'] . " ";
                                                                                            if (empty($row['practitioner_change_of_name'])) {
                                                                                                echo $row['practitioner_name'];
                                                                                            } else {
                                                                                                echo $row['practitioner_change_of_name'];
                                                                                            } ?>
                                                                        </h1>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-xl-12 mb-2">
                                                                                <h5>Personal Informartion</h5>
                                                                            </div>
                                                                            <div class="col-xl-10 mb-2">
                                                                                <div class="row mt-3">
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Full Name
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo $row['practitioner_title'] . " ";
                                                                                            if (empty($row['practitioner_change_of_name'])) {
                                                                                                echo $row['practitioner_name'];
                                                                                            } else {
                                                                                                echo $row['practitioner_change_of_name'];
                                                                                            } ?></h6>
                                                                                    </div>
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Registration Number
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo $row['practitioner_username']; ?>
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Registration Date
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo date_format(date_create($row['registration_date']), 'M d, Y'); ?>
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Phone Number
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo $row['practitioner_mobile_number']; ?>
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Email Id
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo $row['practitioner_email_id']; ?>
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Date of Birth
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo date_format(date_create($row['practitioner_birth_date']), 'M d, Y'); ?>
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Birth Place
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo $row['practitioner_birth_place']; ?>
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Gender
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo $row['practitioner_gender']; ?>
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Father Name
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo $row['practitioner_spouse_name']; ?>
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Nationality
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo $row['practitioner_nationality']; ?>
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Category
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php $resCat = mysqli_query($conn, "SELECT category_name FROM 
                                                                                    category_master WHERE category_id = '$row[category_id]'");
                                                                                    if(mysqli_num_rows($resCat)>0){
                                                                                        $resCat = mysqli_fetch_assoc($resCat);
                                                                                        echo $resCat['category_name'];
                                                                                    } ?></h6>
                                                                                    </div>
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Aadhaar Number
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo $row['practitioner_aadhar_number']; ?>
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        PAN Number
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo $row['practitioner_pan_number']; ?>
                                                                                        </h6>
                                                                                    </div>
        
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Registration Status
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo $row['registration_status']; ?>
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Created By
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo $row['practitioner_created_by']; ?>
                                                                                        </h6>
                                                                                    </div>
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Used CDE Points
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo 0; ?>
                                                                                        </h6>
                                                                                    </div>
        
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Created On
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo date_format(date_create($row['practitioner_created_on']), 'M d, Y'); ?>
                                                                                        </h6>
                                                                                    </div>
        
        
                                                                                    <div class="col-xl-2 mb-2">
                                                                                        Balance CDE Points
                                                                                    </div>
                                                                                    <div class="col-xl-4 mb-2">
                                                                                        <h6><?php echo 0; ?>
                                                                                        </h6>
                                                                                    </div>
        
        
        
        
                                                                                </div>
                                                                            </div>
                                                                            <!-- <div class="col-xl-2 mb-2">
                                                                                <img width="150"
                                                                                    src="<?php if (!empty($row['practitioner_profile_image'])) {
                                                                                        echo 'images/dentist/' . $row['practitioner_profile_image'];
                                                                                    } else{echo 'images/dentist/user.png';}?>" />
                                                                            </div> -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
        
                                                    <?php
        
                                                        }
                                                    }
                                                    ?>
                                                </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php require_once 'include/footer.php'; ?>
    </div>

    <!-- Email Modal -->
    <div class="modal fade" id="remarkModal" tabindex="-1" role="dialog" aria-labelledby="remarkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="remarkModalLabel">
                        <i class="fas fa-envelope mr-2"></i> Send Remark to <span id="recipientNameTitle">Practitioner</span>
                    </h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" id="sendRemarkBtn">
                        <i class="fas fa-paper-plane mr-1"></i> Send Remark
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/custom.js"></script>
    <script src="./js/deznav-init.js"></script>

    <script>
    var fromDate = document.getElementById("from_date");
    var toDate = document.getElementById("to_date");

    toDate.addEventListener("input", function() {
        if (toDate.value < fromDate.value) {
            toDate.setCustomValidity("End date cannot be less than start date.");
        } else {
            toDate.setCustomValidity("");
        }
    });

    fromDate.addEventListener("input", function() {
        if (toDate.value < fromDate.value) {
            toDate.setCustomValidity("End date cannot be less than start date.");
        } else {
            toDate.setCustomValidity("");
        }
    });
    </script>
   
    <script>
    $(document).ready(function() {

        $('#registration_type').change(function() {

            var registrationType = this.value;
            var practitioner_title = document.getElementById('practitioner_title');
            var registration_number = document.getElementById('registration_number');

            if (registrationType == 1) {

                practitioner_title.innerHTML =
                    '<option value="Dr.">Dr.</option>';
            } else {

                practitioner_title.innerHTML =
                    '<option value="">Choose</option><option value="Mr.">Mr.</option><option value="Mrs.">Mrs.</option><option value="Miss.">Miss.</option>';
            }

            $.ajax({
                type: 'POST',
                url: 'ajax/get-registration-number.php',
                data: {
                    registrationType: registrationType
                },
                success: function(response) {

                    registration_number.innerText = response;
                }
            });
        });
        
        // Select all checkbox functionality
        $("#select-all").change(function() {
            $(".practitioner-checkbox").prop('checked', $(this).prop('checked'));
        });
        
        // Update "select all" checkbox state when individual checkboxes change
        $(document).on('change', '.practitioner-checkbox', function() {
            const allChecked = $('.practitioner-checkbox:checked').length === $('.practitioner-checkbox').length;
            const anyChecked = $('.practitioner-checkbox:checked').length > 0;
            $('#select-all').prop('checked', allChecked);
            $('#select-all').prop('indeterminate', anyChecked && !allChecked);
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
            $('#remarkModal').modal('show');
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
    });
    </script>
    <script>
        function setupAutofill(inputId, dropdownId, fetchUrl) {
            const input = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);

            let items = [];
            let selectedIndex = -1;

            fetch(fetchUrl)
                .then(response => response.json())
                .then(data => {
                    items = data;
                })
                .catch(error => console.error('Error fetching items:', error));

            input.addEventListener('input', function () {
                const value = this.value.toLowerCase();
                dropdown.innerHTML = '';
                selectedIndex = -1; 

                if (value.length > 0) {
                    const filteredItems = items
                        .filter(item => item.toLowerCase().includes(value))
                        .slice(0, 5);

                    filteredItems.forEach((item, index) => {
                        const div = document.createElement('div');
                        div.textContent = item;
                        div.classList.add('dropdown-item');
                        div.dataset.index = index;
                        div.addEventListener('click', function () {
                            input.value = item;
                            closeDropdown();
                        });

                        dropdown.appendChild(div);
                    });

                    showDropdown();
                } else {
                    closeDropdown();
                }
            });

            input.addEventListener('keydown', function (event) {
                const dropdownItems = dropdown.querySelectorAll('.dropdown-item');
                if (dropdownItems.length > 0) {
                    if (event.key === 'ArrowDown') {
                        event.preventDefault();
                        selectedIndex = (selectedIndex + 1) % dropdownItems.length;
                        updateSelection(dropdownItems);
                    } else if (event.key === 'ArrowUp') {
                        event.preventDefault();
                        selectedIndex = (selectedIndex - 1 + dropdownItems.length) % dropdownItems.length;
                        updateSelection(dropdownItems);
                    } else if (event.key === 'Enter') {
                        event.preventDefault();
                        if (selectedIndex >= 0) {
                            input.value = dropdownItems[selectedIndex].textContent;
                            closeDropdown();
                        }
                    } else if (event.key === 'Tab') {
                        closeDropdown();
                    }
                }
            });

            function updateSelection(dropdownItems) {
                dropdownItems.forEach((item, index) => {
                    if (index === selectedIndex) {
                        item.classList.add('selected');
                        item.scrollIntoView({ block: 'nearest' });
                    } else {
                        item.classList.remove('selected');
                    }
                });
            }

            function showDropdown() {
                dropdown.style.display = 'block';
            }

            function closeDropdown() {
                dropdown.style.display = 'none';
            }

            document.addEventListener('click', function (event) {
                if (!input.contains(event.target) && !dropdown.contains(event.target)) {
                    closeDropdown();
                }
            });
        }

        setupAutofill('practitioner_birth_place', 'dropdown-birth-place', 'ajax/get-birthplaces.php');
    </script>
</body>

</html>