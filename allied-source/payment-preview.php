<?php
session_start();

require_once '../config/connection.php';
require_once '../config/utils.php';

date_default_timezone_set('Asia/Kolkata');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'include/meta.php'; ?>
    <link href="./vendor/tagify/dist/tagify.css" rel="stylesheet">
    <title><?php echo $site; ?> - Payment</title>
</head>

<body data-typography="poppins" data-theme-version="light" data-layout="vertical" data-nav-headerbg="black"
    data-headerbg="color_1">

    <?php
    // require_once './include/pre-loader.php';

    if (empty($_SESSION['user_name'])) {

        echo "<script>location.href = 'index.php'</script>";
    }

    $username = $_SESSION['user_name'];

    $receipt_number_temp = "";

    if (!empty($_GET['source'])) {

        $receipt_number_temp = base64_decode(urldecode($_GET['source']));
    }

    $resReceipt = mysqli_query($conn, "SELECT receipt_for_id, receipt_number_temp, receipt_date, 
    practitioner_id, receipt_total, receipt_reference_number, order_id FROM receipt_preview
        WHERE receipt_number_temp = '$receipt_number_temp' AND receipt_status = 'Waiting for response'");
    if(mysqli_num_rows($resReceipt)>0){

        $rowReceipt = mysqli_fetch_assoc($resReceipt);
        $practitioner_id = $rowReceipt['practitioner_id'];
    } else{
        
        echo "<script>swal({title: 'Failed!', text: 'Unable to process your request!', type: 'error'}).then(function(){window.location ='payment-pending.php';});</script>";
    }

 
    $resPracti = mysqli_query($conn, "SELECT registration_type_id, practitioner_username, practitioner_name, 
    practitioner_title, practitioner_change_of_name FROM practitioner WHERE 
    practitioner_id = '$rowReceipt[practitioner_id]'");
    if(mysqli_num_rows($resPracti)>0){

        $rowPracti = mysqli_fetch_assoc($resPracti);

        if(empty($rowPracti['practitioner_change_of_name'])){
            $practitioner_name = $rowPracti['practitioner_title'] . ' ' . $rowPracti['practitioner_name'];
        } else{
            $practitioner_name = $rowPracti['practitioner_title'] . ' ' . $rowPracti['practitioner_change_of_name'];
        }
    } else{
        
        echo "<script>swal({title: 'Failed!', text: 'Unable to process your request!', type: 'error'}).then(function(){window.location ='payment-pending.php';});</script>";
    }

    if (isset($_POST['reject_payment'])) {
        
        if (mysqli_query($conn, "UPDATE receipt_preview SET receipt_status= 'Rejected' WHERE receipt_number_temp = '$receipt_number_temp'")) {

            echo "<script>swal({title: 'Success', text: 'Payment rejected successfully!', type: 'success'}).then(function(){window.location = 'payment-pending.php';});</script>";
        } else {

            echo "<script>swal('Failed!', 'Unable to process your request!', 'error')</script>";
        }
    }

    if(isset($_POST['edit_payment'])){

        $resReceipt = mysqli_query($conn, "SELECT receipt_total, receipt_date, receipt_reference_number, 
            receipt_for_id, receipt_created_on, order_id, receipt_validity FROM receipt_preview
            WHERE receipt_number_temp = '$receipt_number_temp' AND receipt_status = 'Waiting for response'");
        
        if(mysqli_num_rows($resReceipt) > 0) {
            $rowReceipt = mysqli_fetch_assoc($resReceipt);
        
            $resTemp = mysqli_query($conn, "SELECT fees_id, total_amount FROM receipt_temp_preview
                WHERE receipt_number_temp = '$receipt_number_temp'");
            
            if(mysqli_num_rows($resTemp) > 0) {
        
                $currentDate = date('Y-m-d');
                $timestamp = strtotime($currentDate);
        
                $year = date('Y', $timestamp);
                $month = date('m', $timestamp);
        
                if ($month < 4) {
                    $year = $year - 1;
                }
        
                $receipt_number = $year . '00001';
        
                $resGet = mysqli_query($conn, "SELECT MAX(receipt_number) AS receipt_number FROM receipt_number_master");
                if(mysqli_num_rows($resGet) > 0) {
                    $resGet = mysqli_fetch_assoc($resGet);
        
                    $receipt_db_number = $resGet['receipt_number'];
                    $db_year = substr($receipt_db_number, 0, 4);
                    
                    if($db_year == $year) {
                        $receipt_number = $receipt_db_number + 1;
                    }
                }
        
                $resCheck = mysqli_query($conn, "SELECT receipt_number_master_id FROM receipt_number_master 
                    WHERE receipt_number = '$receipt_number'");
                if(mysqli_num_rows($resCheck) == 0) {

                    if(mysqli_query($conn, "INSERT INTO receipt_number_master (receipt_number, created_on, created_by) VALUES ('$receipt_number', 'Self', '$date')")) {
                        
                        while($rowTemp = mysqli_fetch_assoc($resTemp)) {
                            $fees_id = $rowTemp['fees_id'];
                            $total_amount = $rowTemp['total_amount'];
        
                            mysqli_query($conn, "INSERT INTO receipt_temp (fees_id, total_amount, receipt_number, PractitionerID, CreatedBy, CreatedOn) VALUES 
                                ('$fees_id', '$total_amount', '$receipt_number',  '$practitioner_id', 'Self', '$date')");
                        }
        
                        if(mysqli_query($conn, "INSERT INTO receipt (account_id, receipt_total, receipt_date, receipt_reference_number, 
                            receipt_for_id, receipt_type, receipt_created_on, receipt_created_by, 
                            receipt_number, receipt_status, receipt_remit_status, practitioner_id, receipt_validity) VALUES 
                            (1, '$rowReceipt[receipt_total]', '$rowReceipt[receipt_date]', '$rowReceipt[receipt_reference_number]', 
                            '$rowReceipt[receipt_for_id]', 'Online', '$rowReceipt[receipt_created_on]', 'Self', 
                            '$receipt_number', 'Active', 'No', '$practitioner_id', '$rowReceipt[receipt_validity]')")) {
                                
                            mysqli_query($conn, "INSERT INTO razorpay (total_amount, practitioner_id, 
                                payment_status, payment_date, order_id, payment_id, receipt_number) VALUES 
                                ('$rowReceipt[receipt_total]', '$practitioner_id', 'Paid', '$rowReceipt[receipt_created_on]', 
                                '$rowReceipt[order_id]', '$rowReceipt[receipt_reference_number]', '$receipt_number')");

                            mysqli_query($conn, "UPDATE receipt_preview SET receipt_status = 'Successful' 
                                WHERE order_id = '$rowReceipt[order_id]'");

                        echo "<script>swal({title: 'Success', text: 'Payment updated successfully!', type: 'success'}).then(function(){window.location ='payment-pending.php';});</script>";
                        }
                    } else {
                        echo "<script>swal('Failed!', 'Unable to process your request!', 'error')</script>";
                    }
                } else {
                    echo "<script>swal('Failed!', 'Unable to process your request!', 'error')</script>";
                }
            } else {
                echo "<script>swal('Failed!', 'Unable to process your request!', 'error')</script>";
            }
        } else {
            echo "<script>swal('Failed!', 'Unable to process your request!', 'error')</script>";
        }
    
    }

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
                        <h5 class="bc-title">Edit Payment</h5>
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
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Payment</a></li>
                </ol>

            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header flex-wrap border-0">
                                <div>
                                    <h4 class="heading mb-0">Edit Payment [<?php echo $rowReceipt['order_id'];?>]
                                    </h4>
                                </div>
                                <div>
                                    <a class="btn btn-primary btn-sm"
                                        href="payment-pending.php">Back</a>
                                </div>
                                
                            </div>
                            <form method="POST" enctype="multipart/form-data" novalidate
                                class="card-body px-4 pt-0 needs-validation">
                                <div class="row">
                                    
                                    <div class="col-xl-3">
                                        <label class="form-label">Receipt
                                            Date<span class="text-danger">
                                                *</span></label>
                                        <input autocomplete="off" type="date" style="font-size: 12px;"
                                            class="form-control form-control-sm" name="receipt_date"
                                            value="<?php echo $rowReceipt['receipt_date'];?>" readonly>
                                        <div class="invalid-feedback">
                                            Please enter a valid date.
                                        </div>
                                    </div>
                                    <div class="col-xl-3">
                                        <label class="form-label">Payment For<span class="text-danger">
                                                *</span></label>
                                                <?php
                                                $sqlFor = "SELECT receipt_for, receipt_for_id 
                                                FROM receipt_for_master WHERE receipt_for_status = 'Active' AND receipt_for_id = '$rowReceipt[receipt_for_id]'";
                                                    
                                                $resFor = mysqli_query($conn, $sqlFor);
                                                if (mysqli_num_rows($resFor) > 0) {
                                                    $rowFor = mysqli_fetch_assoc($resFor);
                                                        
                                                    $receipt_for = $rowFor['receipt_for'];
                                                    
                                                }
                                            ?>
                                        <input autocomplete="off" type="text" style="font-size: 12px;"
                                            class="form-control form-control-sm"
                                            value="<?php echo $receipt_for;?>" readonly>                                    </div>
                                    <div class="col-xl-3">
                                        <label class="form-label">Practitioner Name<span class="text-danger">
                                                *</span></label>
                                        <input autocomplete="off" type="text" style="font-size: 12px;"
                                            class="form-control form-control-sm" name="practitioner"
                                            value="<?php echo $practitioner_name;?>" readonly>
                                    </div>
                                    <div class="col-xl-3">
                                        <label class="form-label">Payment Id<span class="text-danger">
                                                *</span></label>
                                        <input autocomplete="off" type="text" style="font-size: 12px;"
                                            class="form-control form-control-sm" value="<?php echo $rowReceipt['receipt_reference_number'];?>" readonly>
                                    </div>
                                    <div class="col-xl-3">
                                        <label class="form-label mt-2">Order Id<span class="text-danger">
                                                *</span></label>
                                        <input autocomplete="off" type="text" style="font-size: 12px;"
                                            class="form-control form-control-sm" value="<?php echo $rowReceipt['order_id'];?>" readonly>
                                    </div>
                                    <div class="col-xl-3">
                                        <label class="form-label mt-2">Payment
                                            Date<span class="text-danger">
                                                *</span></label>
                                        <input autocomplete="off" type="date" style="font-size: 12px;"
                                            class="form-control form-control-sm" 
                                            value="<?php echo $rowReceipt['receipt_date'];?>" readonly>
                                        <div class="invalid-feedback">
                                            Please enter a valid date.
                                        </div>
                                    </div>
                                </div>
                                <style>
                                .table td {
                                    padding: 1px;
                                }

                                .table th {
                                    padding: 5px;
                                }

                                input[type="number"]::-webkit-inner-spin-button,
                                input[type="number"]::-webkit-outer-spin-button {
                                    -webkit-appearance: none;
                                    margin: 0;
                                }
                                </style>
                                
                                <div class="row">
                                    <div class="col-xl-8 text-end"><label class="form-label mt-2">
                                            <h6>Total
                                                Amount</h6>
                                        </label></div>
                                    <div class="col-xl-4">

                                        <input autocomplete="off" readonly class="form-control"
                                            name="total_amount" value="<?php echo $rowReceipt['receipt_total'];?>">
                                    </div>

                                    <div class="col-xl-12 text-end mt-4">
                                        <button type="submit" name="reject_payment"
                                            class="btn btn-danger" onClick='return confirm("Are you sure you want to reject?")'>Reject</button>
                                        <button type="submit" name="edit_payment"
                                            class="btn btn-success">Accept</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        <script>
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
        </script>

        <?php require_once 'include/footer.php'; ?>
    </div>

    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/custom.js"></script>
    <script src="./js/deznav-init.js"></script>
</body>

</html>