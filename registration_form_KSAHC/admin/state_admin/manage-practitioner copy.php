<?php
session_start();

require_once '../config/connection.php';
require_once '../config/utils.php';
// require_once '../config/mail-helper.php';
// require_once '../config/sms-helper.php';

date_default_timezone_set('Asia/Kolkata');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'include/meta.php'; ?>
    <link href="./vendor/tagify/dist/tagify.css" rel="stylesheet">
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

                    if (sendRenewalSMS($name, $phone, $regno, $password)) {
                        
                        echo "<script>swal({title: 'Success', text: 'Practitioner added successfully!', type: 'success'}).then(function(){window.location = 'edit-personal.php?source=$param';});</script>";
                    } else {
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


    $root_sql = "SELECT * FROM practitioner WHERE registration_status = 'Active'";

    $page_sql = "SELECT COUNT(practitioner_id) As total_records FROM practitioner WHERE registration_status = 'Active'";

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
                                    <!-- <?php
                                        $resPermissionCheck = mysqli_query($conn, "SELECT permission_id FROM 
                                            permission_manager WHERE permission_username = '$username' AND 
                                            permission_edit_permission = 1 AND permission_particular_id = 1");
                                        if(mysqli_num_rows($resPermissionCheck)>0){?>
                                            <a class="btn btn-primary btn-sm" data-bs-toggle='modal' data-bs-target='#add'>+ Add
                                                Practitioner</a>
                                    <?php }?> -->
                                    <a class="btn btn-primary btn-sm" data-bs-toggle='modal' data-bs-target='#add'>+ Add
                                    Practitioner</a>
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
                                    <table class="table table-responsive-md">
                                        <thead>
                                            <tr>
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
                                                    } else {

                                                        echo "badge-warning";
                                                    }

                                                    echo " light border-0'>" . $row['registration_status'] . "</span></td>";

                                                    echo "<td class='text-end'>";
                                                    // $resPermissionCheck = mysqli_query($conn, "SELECT permission_id FROM 
                                                    //     permission_manager WHERE permission_username = '$username' AND 
                                                    //     permission_edit_permission = 1 AND permission_particular_id = 1");
                                                    // if(mysqli_num_rows($resPermissionCheck)>0){
                                                        echo "<form method='post'>
                                                                <a class='btn btn-primary shadow btn-xs sharp me-1' href='edit-personal.php?source=$param'><i class='fa fa-pencil'></i></a>
                                                                <a class='btn btn-warning shadow btn-xs sharp me-1' data-bs-toggle='modal' data-bs-target='#lock$row[practitioner_id]' href='#'><i class='fa fa-lock'></i></a>
                                                                <input autocomplete='off'  type='hidden' name='delete_id' value='$row[practitioner_id]'/>
                                                            </form>";
                                                    // }
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
                                                                                Created On
                                                                            </div>
                                                                            <div class="col-xl-4 mb-2">
                                                                                <h6><?php echo date_format(date_create($row['practitioner_created_on']), 'M d, Y'); ?>
                                                                                </h6>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-2 mb-2">
                                                                        <img width="150"
                                                                            src="" />
                                                                    </div>
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
                            <div class="card-footer">
                                <div class="row ">
                                    <div class="col-12 ">
                                        <div class="table-pagenation">
                                            <nav>
                                                <ul class="pagination pagination-gutter pagination-primary no-bg">
                                                    <?php
                                                    if ($total_no_of_pages <= 10) {

                                                        for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {

                                                            if ($counter == $page_no) {

                                                                echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                                                            } else {

                                                                echo "<li class='page-item page-indicator'><a class='page-link' href='?page_count=$total_records_per_page&search_type=$search_type&search_phone=$search_phone&search_number=$search_number&order_by=$order_by&search_status=$search_status&from_date=$from_date&filter&to_date=$to_date&page_no=$counter'>$counter</a></li>";
                                                            }
                                                        }
                                                    } elseif ($total_no_of_pages > 10) {

                                                        if ($page_no <= 4) {
                                                            for ($counter = 1; $counter < 8; $counter++) {

                                                                if ($counter == $page_no) {

                                                                    echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                                                                } else {

                                                                    echo "<li class='page-item page-indicator'><a class='page-link' href='?page_count=$total_records_per_page&search_type=$search_type&search_phone=$search_phone&search_number=$search_number&order_by=$order_by&search_status=$search_status&from_date=$from_date&filter&to_date=$to_date&page_no=$counter'>$counter</a></li>";
                                                                }
                                                            }
                                                            echo "<li class='page-item page-indicator'><a class='page-link'>...</a></li>";
                                                            echo "<li class='page-item page-indicator'><a class='page-link' href='?page_count=$total_records_per_page&search_type=$search_type&search_phone=$search_phone&search_number=$search_number&order_by=$order_by&search_status=$search_status&from_date=$from_date&filter&to_date=$to_date&page_no=$second_last'>$second_last</a></li>";
                                                            echo "<li class='page-item page-indicator'><a class='page-link' href='?page_count=$total_records_per_page&search_type=$search_type&search_phone=$search_phone&search_number=$search_number&order_by=$order_by&search_status=$search_status&from_date=$from_date&filter&to_date=$to_date&page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                                                        } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {

                                                            echo "<li class='page-item page-indicator'><a class='page-link' href='?page_count=$total_records_per_page&search_type=$search_type&search_phone=$search_phone&search_number=$search_number&order_by=$order_by&search_status=$search_status&from_date=$from_date&filter&to_date=$to_date&page_no=1'>1</a></li>";
                                                            echo "<li class='page-item page-indicator'><a class='page-link' href='?page_count=$total_records_per_page&search_type=$search_type&search_phone=$search_phone&search_number=$search_number&order_by=$order_by&search_status=$search_status&from_date=$from_date&filter&to_date=$to_date&page_no=2'>2</a></li>";
                                                            echo "<li class='page-item page-indicator'><a class='page-link'>...</a></li>";

                                                            for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                                                                if ($counter == $page_no) {

                                                                    echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                                                                } else {

                                                                    echo "<li class='page-item page-indicator'><a class='page-link' href='?page_count=$total_records_per_page&search_type=$search_type&search_phone=$search_phone&search_number=$search_number&order_by=$order_by&search_status=$search_status&from_date=$from_date&filter&to_date=$to_date&page_no=$counter'>$counter</a></li>";
                                                                }
                                                            }
                                                            echo "<li class='page-item page-indicator'><a class='page-link'>...</a></li>";
                                                            echo "<li class='page-item page-indicator'><a class='page-link' href='?page_count=$total_records_per_page&search_type=$search_type&search_phone=$search_phone&search_number=$search_number&order_by=$order_by&search_status=$search_status&from_date=$from_date&filter&to_date=$to_date&page_no=$second_last'>$second_last</a></li>";
                                                            echo "<li class='page-item page-indicator'><a class='page-link' href='?page_count=$total_records_per_page&search_type=$search_type&search_phone=$search_phone&search_number=$search_number&order_by=$order_by&search_status=$search_status&from_date=$from_date&filter&to_date=$to_date&page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                                                        } else {

                                                            echo "<li class='page-item page-indicator'><a class='page-link' href='?page_count=$total_records_per_page&search_type=$search_type&search_phone=$search_phone&search_number=$search_number&order_by=$order_by&search_status=$search_status&from_date=$from_date&filter&to_date=$to_date&page_no=1'>1</a></li>";
                                                            echo "<li class='page-item page-indicator'><a class='page-link' href='?page_count=$total_records_per_page&search_type=$search_type&search_phone=$search_phone&search_number=$search_number&order_by=$order_by&search_status=$search_status&from_date=$from_date&filter&to_date=$to_date&page_no=2'>2</a></li>";
                                                            echo "<li class='page-item page-indicator'><a class='page-link'>...</a></li>";

                                                            for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {

                                                                if ($counter == $page_no) {

                                                                    echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                                                                } else {

                                                                    echo "<li class='page-item page-indicator'><a class='page-link' href='?page_count=$total_records_per_page&search_type=$search_type&search_phone=$search_phone&search_number=$search_number&order_by=$order_by&search_status=$search_status&from_date=$from_date&filter&to_date=$to_date&page_no=$counter'>$counter</a></li>";
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="add" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-center modal-xl">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="addLabel">Add Practitioner - </h1>
                            <h5 class="mt-1 ms-3" id="registration_number"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <!-- <div class="col-xl-3 mb-3">
                                    <label class="form-label">Registration Type<span class="text-danger">
                                            *</span></label>
                                    <select class="form-control" required name="registration_type"
                                        id="registration_type">
                                        <option value="">Choose</option>
                                        <?php
                                            $resType = mysqli_query($conn, "SELECT registration_type_id, registration_type 
                                                FROM registration_type_master WHERE registration_type_status = 'Active'");
                                            if (mysqli_num_rows($resType) > 0) {
                                                while ($rowType = mysqli_fetch_assoc($resType)) {
                                                    echo "<option value='$rowType[registration_type_id]'>$rowType[registration_type]</option>";
                                                }
                                            }
                                            ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please choose a type.
                                    </div>
                                </div> -->
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Registration Date<span class="text-danger">
                                            *</span></label>
                                    <input autocomplete="off" type="date" class="form-control" name="registration_date"
                                        title="Registration Date" required min="<?php echo date('Y-m-d');?>"
                                        value="<?php echo date('Y-m-d');?>">
                                    <div class="invalid-feedback">
                                        Please choose registration date
                                    </div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Title<span class="text-danger"> *</span></label>
                                    <select class="form-control" required name="practitioner_title"
                                        id="practitioner_title">
                                    </select>
                                    <div class="invalid-feedback">
                                        Please choose a title.
                                    </div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Gender<span class="text-danger"> *</span></label>
                                    <select class="form-control" required name="practitioner_gender">
                                        <option value="">Choose</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please choose a gender.
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label class="form-label">Practitioner Name<span class="text-danger">
                                            *</span></label>
                                    <input autocomplete="off" type="text" class="form-control" required
                                        name="practitioner_name" maxlength="250" pattern="^([A-Za-z.]+[ ]?|[A-Za-z.])+$"
                                        title="Your name can only contain letters and spaces and cannot be blank."
                                        style="text-transform: uppercase;">
                                    <div class="invalid-feedback">
                                        Please enter a valid name.
                                    </div>
                                </div>
                                <div class="col-xl-3 mb-3"><label class="form-label">Date of Birth<span
                                            class="text-danger"> *</span></label>
                                    <input autocomplete="off" type="date" class="form-control"
                                        max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>" required
                                        name="practitioner_dob" title="You must be 18 or older.">
                                    <div class="invalid-feedback">
                                        Please choose a valid date of birth.
                                    </div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Nationality<span class="text-danger"> *</span></label>
                                    <select class="form-control" required name="practitioner_nationality">
                                        <option value="">Choose</option>
                                        <option selected value="Indian">Indian</option>
                                        <option value="Over Citizen of India">Over Citizen of India</option>
                                        <option value="Person of Indian Origin">Person of Indian Origin</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please choose a nationality.
                                    </div>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label class="form-label">Father Name</label>
                                    <input autocomplete="off" type="text" class="form-control"
                                        style="text-transform: uppercase;" name="practitioner_spouse_name"
                                        maxlength="250" pattern="^([A-Za-z.]+[ ]?|[A-Za-z.])+$" value="SHRI "
                                        title="Can only contain letters and spaces and cannot be blank.">
                                    <div class="invalid-feedback">
                                        Please enter a valid father name.
                                    </div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Birth Place</label>
                                    <input autocomplete="off" type="text" class="form-control" name="practitioner_birth_place" 
                                        id="practitioner_birth_place" maxlength="100"
                                        pattern="^([A-Za-z]+[ ]?|[A-Za-z])+$"
                                        title="Can only contain letters and spaces and cannot be blank.">
                                    <div id="dropdown-birth-place" class="dropdown-content"></div>
                                    <div class="invalid-feedback">Please enter a valid birth place.</div>
                                </div>

                                <style>
                                    .dropdown-content {
                                        position: absolute;
                                        background-color: white;
                                        border: 1px solid #ddd;
                                        max-height: 200px;
                                        overflow-y: auto;
                                        width: 22%; 
                                        display: none;
                                        z-index: 1000;
                                    }
                                    .dropdown-item {
                                        padding: 8px;
                                        cursor: pointer;
                                    }
                                    .dropdown-item:hover,
                                    .dropdown-item.selected {
                                        background-color: #007bff; 
                                        color: white;
                                    }
                                </style>

                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-control" name="practitioner_category">
                                        <option value="">Choose</option>
                                        <?php
                                            $resCat = mysqli_query($conn, "SELECT category_id, category_name 
                                                FROM category_master WHERE category_status = 'Active'");
                                            if (mysqli_num_rows($resCat) > 0) {
                                                while ($rowCat = mysqli_fetch_assoc($resCat)) {
                                                    echo "<option value='$rowCat[category_id]'>$rowCat[category_name]</option>";
                                                }
                                            }
                                            ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please choose a category.
                                    </div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Email Id<span class="text-danger"> *</span></label>
                                    <input autocomplete="off" type="email" class="form-control" required
                                        name="practitioner_email_id" maxlength="124" title="Enter valid email addrress">
                                    <div class="invalid-feedback">
                                        Please enter a valid email id.
                                    </div>
                                </div>

                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Mobile Number<span class="text-danger"> *</span></label>
                                    <input autocomplete="off" type="text" class="form-control" required
                                        name="practitioner_mobile" maxlength="10" pattern="[0-9]{10}"
                                        title="Accept 10 digit number">
                                    <div class="invalid-feedback">
                                        Please enter a valid mobile number.
                                    </div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">Aadhaar Number</label>
                                    <input autocomplete="off" type="text" class="form-control" name="practitioner_adhar"
                                        maxlength="12" pattern="[0-9]{12}" title="Accept 12 digit number">
                                    <div class="invalid-feedback">
                                        Please enter a valid aadhaar number.
                                    </div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label class="form-label">PAN Number</label>
                                    <input autocomplete="off" style="text-transform: uppercase;" type="text"
                                        class="form-control" name="practitioner_pan" maxlength="10"
                                        pattern="[0-9a-zA-Z]{10}"
                                        title="PAN number must contain only letters and numbers.">
                                    <div class="invalid-feedback">
                                        Please enter a valid pan number.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="offcanvas offcanvas-end" tabindex="-1" id="filter">
            <div class="offcanvas-header">
                <h5 class="modal-title" id="#gridSystemModal1">Filter by</h5>
            </div>
            <div class="offcanvas-body">
                <div class="container-fluid">
                    <form method="GET">
                        <div class="row">
                            <div class="col-xl-6 mb-3">
                                <label class="form-label">Start Date</label>
                                <input autocomplete='off' type="date" class="form-control" id="from_date"
                                    name="from_date" value="<?php if (!empty($from_date)) {
                                                                                                                    echo date_format(date_create($from_date), 'Y-m-d');
                                                                                                                } ?>">
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label class="form-label">End Date</label>
                                <input autocomplete='off' type="date" class="form-control" name="to_date" id="to_date"
                                    value="<?php if (!empty($to_date)) {
                                                                                                                echo date_format(date_create($to_date), 'Y-m-d');
                                                                                                            } ?>">
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label class="form-label">Reg No/Name</label>
                                <input autocomplete='off' name="search_number" maxlength="100" class="form-control"
                                    type="text" value="<?php echo $search_number; ?>">
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input autocomplete='off' name="search_phone" maxlength="100" class="form-control"
                                    type="text" value="<?php echo $search_phone; ?>">
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="search_status">
                                    <option value="">Choose</option>
                                    <option value="Active" <?php if ($search_status == 'Active') {
                                                                echo 'selected';
                                                            } ?>>Active</option>
                                    <option value="In-Active" <?php if ($search_status == 'In-Active') {
                                                                    echo 'selected';
                                                                } ?>>In-Active</option>
                                </select>
                            </div>
                            <div class="col-xl-6 mb-3">
                                <label class="form-label">Order By</label>
                                <select class="form-control" name="order_by">
                                    <option value="">Choose</option>
                                    <option value="date_desc" <?php if ($order_by == 'date_desc') {
                                                                    echo 'selected';
                                                                } ?>>New-Old</option>
                                    <option value="date_asc" <?php if ($order_by == 'date_asc') {
                                                                    echo 'selected';
                                                                } ?>>Old-New</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-primary me-1" name="filter">Set Filter</button>
                            <a class="btn btn-danger light ms-1" href="manage-practitioner.php">Clear</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <?php require_once 'include/footer.php'; ?>
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