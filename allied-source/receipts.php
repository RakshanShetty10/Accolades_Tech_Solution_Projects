<?php
session_start();
require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

if (empty($_GET['page'])) {

    $current_page = 1;
} else {

    $current_page = $_GET['page'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $site_full; ?> - Practitioner Profile</title>
    <?php require_once 'include/header-link.php'; ?>
    <link href="assets/css/module-css/sidebar.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .vr-btn,
        .vr-btn:hover,
        .vr-btn:focus,
        .vr-btn:active {
            padding: 10px 15px;
            margin-right: 15px;
            background: rgb(25 39 53 / 90%);
            color: white;
            font-size: 14px;
        }

        .rc-btn,
        .rc-btn:hover,
        .rc-btn:focus,
        .rc-btn:active {
            padding: 10px 15px;
            margin-right: 15px;
            background: rgb(227 110 0 / 90%);
            color: white;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <?php
    if (empty($_SESSION['_id'])) {

        echo "<script>swal.fire({text: 'Kindly login to proceed!',type: 'error',icon: 'error'}).then(function(){window.location = 'login.php';});</script>";
    } else {

        $id = $_SESSION['_id'];
    ?>
        <div class="boxed_wrapper ltr">

            <?php require_once 'include/pre-loader.php'; ?>
            <?php require_once 'include/header.php'; ?>

            <section class="sidebar-page-container pt_100 pb_100 border-top">
                <div class="auto-container">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-12 col-sm-12 sidebar-side">
                            <div class="blog-sidebar ml_20">
                                <div class="sidebar-widget category-widget">
                                    <div class="widget-content">
                                        <ul class="category-list clearfix">
                                            <li>
                                                <a href="welcome.php">Welcome</a>
                                            </li>
                                            <li>
                                                <a href="profile.php">My Profile</a>
                                            </li>
                                            <li>
                                                <h6><a href="receipts.php">Receipts</a></h6>
                                            </li>
                                            <li><a href="payments.php">Payments</a></li>
                                            <!-- <li><a href="ksdc-journal.php">KSDC Journal</a></li> -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-12 col-sm-12 content-side pl_60">
                            <div class="blog-standard-content">
                                <div class="news-block-three">
                                    <div class="inner-box">
                                        <div class="lower-content">
                                            <div class="widget-title mb_40">
                                            
                                                    <?php
                                                    $resReceipt = mysqli_query($conn, "SELECT receipt_validity FROM
                                                        receipt WHERE (receipt_for_id = 1 OR receipt_for_id = 2 
                                                        OR receipt_for_id = 4) AND practitioner_id = '$id'
                                                        ORDER BY receipt_id DESC");
                                                    if (mysqli_num_rows($resReceipt) > 0) {
                                                        $resReceipt = mysqli_fetch_assoc($resReceipt);

                                                        if (!empty($resReceipt['receipt_validity'])) {
                                                            echo date_format(date_create($resReceipt['receipt_validity']), 'd M Y');
                                                        }
                                                    }
                                                    ?>
                                                <h5 style="font-weight: 500; color:var(--primary-color);">Your payment valid upto </h5>
                                            </div>

                                            <?php

                                            $records_per_page = 5;
                                            $offset = ($current_page - 1) * $records_per_page;

                                            $sql_total = "SELECT COUNT(receipt_id) AS total_records FROM receipt  
                                                WHERE practitioner_id = '$id' AND receipt_status = 'Active'";

                                            $result_total = mysqli_query($conn, $sql_total);
                                            $total_records = mysqli_fetch_array($result_total);
                                            $total_records = $total_records['total_records'];
                                            $total_pages = ceil($total_records / $records_per_page);

                                            $resReceipt = mysqli_query($conn, "SELECT * FROM receipt  
                                                WHERE practitioner_id = '$id' AND receipt_status = 'Active' 
                                                ORDER BY receipt_id DESC LIMIT $offset, $records_per_page");

                                            if (mysqli_num_rows($resReceipt) > 0) {

                                                $i = 0;

                                                while ($rowReceipt = mysqli_fetch_assoc($resReceipt)) {
                                                    if ($i != 0) {
                                                        echo "<hr>";
                                                    }

                                                    $param = urlencode(base64_encode($rowReceipt['receipt_number']));
                                            ?>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">Payment For</label>
                                                                <div class="col-sm-8">
                                                                    <span class="form-control-plaintext">
                                                                        <?php
                                                                        $resFor = mysqli_query($conn, "SELECT receipt_for FROM receipt_for_master 
                                                                    WHERE receipt_for_id = '$rowReceipt[receipt_for_id]'");
                                                                        if (mysqli_num_rows($resFor) > 0) {
                                                                            $resFor = mysqli_fetch_assoc($resFor);
                                                                            echo $resFor['receipt_for'];
                                                                        }
                                                                        ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">Receipt No</label>
                                                                <div class="col-sm-8">
                                                                    <span
                                                                        class="form-control-plaintext"><?php echo $rowReceipt['receipt_number']; ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-4 col-form-label">Bank</label>
                                                                <div class="col-sm-8">
                                                                    <span class="form-control-plaintext"><?php
                                                                                                            $resBank = mysqli_query($conn, "SELECT bank_name FROM bank_master 
                                                                    WHERE bank_id = '$rowReceipt[bank_id]'");
                                                                                                            if (mysqli_num_rows($resBank) > 0) {
                                                                                                                $resBank = mysqli_fetch_assoc($resBank);
                                                                                                                echo $resBank['bank_name'];
                                                                                                            }
                                                                                                            ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label
                                                                    class="col-sm-4 col-form-label"><?php if ($rowReceipt['payment_mode_id'] == 1) {
                                                                                                        echo 'DD Number';
                                                                                                    } else {
                                                                                                        echo 'Reference No';
                                                                                                    } ?></label>
                                                                <div class="col-sm-8">
                                                                    <span
                                                                        class="form-control-plaintext"><?php echo $rowReceipt['receipt_reference_number']; ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-md-12 col-sm-12">
                                                            <div class="form-group row">
                                                                <label class="col-sm-6 col-form-label">Mode</label>
                                                                <div class="col-sm-6">
                                                                    <span
                                                                        class="form-control-plaintext"><?php echo $rowReceipt['receipt_type']; ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-6 col-form-label">Receipt Date</label>
                                                                <div class="col-sm-6">
                                                                    <span
                                                                        class="form-control-plaintext"><?php echo date_format(date_create($rowReceipt['receipt_date']), 'd M Y'); ?></span>
                                                                </div>
                                                            </div>
                                                            <?php if ($rowReceipt['payment_mode_id'] == 1) { ?>
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">DD Date</label>
                                                                    <div class="col-sm-6">
                                                                        <span
                                                                            class="form-control-plaintext"><?php echo date_format(date_create($rowReceipt['dd_date']), 'd M Y'); ?></span>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                            <div class="form-group row">
                                                                <label class="col-sm-6 col-form-label">Amount</label>
                                                                <div class="col-sm-6">
                                                                    <span class="form-control-plaintext"><?php
                                                                                                            $total_amount = 0;
                                                                                                            // if (is_null($id)) {
                                                                                                            //     $receiptTempQuery = "SELECT total_amount FROM receipt_temp WHERE receipt_number = '$rowReceipt[receipt_number]' AND NOT fees_id = 19";
                                                                                                            // } else {
                                                                                                            //     $receiptTempQuery = "SELECT total_amount FROM receipt_temp WHERE PractitionerID = '$id' AND receipt_number = '$rowReceipt[receipt_number]' AND NOT fees_id = 19";
                                                                                                            // }

                                                                                                            $receiptTempQuery = "SELECT total_amount FROM receipt_temp WHERE (PractitionerID = '$id' OR PractitionerID IS NULL OR LENGTH(PractitionerID) > 10) AND receipt_number = '$rowReceipt[receipt_number]' AND NOT fees_id = 19";

                                                                                                            $resAmount = mysqli_query($conn, $receiptTempQuery);
                                                                                                            if (mysqli_num_rows($resAmount) > 0) {
                                                                                                                while ($rowAmount = mysqli_fetch_assoc($resAmount)) {
                                                                                                                    $total_amount += $rowAmount['total_amount'];
                                                                                                                }
                                                                                                            }
                                                                                                            echo number_format($total_amount, 2);
                                                                                                            ?></span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-12 col-md-12 col-sm-12 mt_15 mb_10">
                                                            <a class="vr-btn" href="view-receipt.php?source=<?php echo urlencode(base64_encode($rowReceipt['receipt_number'])); ?>&practitioner=<?php echo urlencode(base64_encode($id)); ?>" target="_blank">Receipt</a>
                                                            <?php
                                                            // if (is_null($id)) {
                                                            //     $receiptTempQuery = "SELECT * FROM receipt_temp WHERE receipt_number = '$rowReceipt[receipt_number]' AND fees_id = 7";
                                                            // } else {
                                                            //     $receiptTempQuery = "SELECT * FROM receipt_temp WHERE PractitionerID = '$id' AND receipt_number = '$rowReceipt[receipt_number]' AND fees_id = 7";
                                                            // }


                                                            $receiptTempQuery = "SELECT * FROM receipt_temp WHERE (PractitionerID = '$id' OR PractitionerID IS NULL OR LENGTH(PractitionerID) > 10) AND receipt_number = '$rowReceipt[receipt_number]' AND fees_id = 7";

                                                            $resRenCheck = mysqli_query($conn, $receiptTempQuery);
                                                            if (mysqli_num_rows($resRenCheck) > 0) { ?>
                                                                <a class="rc-btn" href="renewal-certificate.php?source=<?php echo urlencode(base64_encode($rowReceipt['receipt_number'])); ?>&practitioner=<?php echo urlencode(base64_encode($id)); ?>" target="_blank">Certificate</a>
                                                            <?php }
                                                            // $receiptTempQuery = is_null($id) ? 
                                                            //     "SELECT receipt_temp_id FROM receipt_temp WHERE receipt_number = '$rowReceipt[receipt_number]' AND fees_id = 10" : 
                                                            //     "SELECT receipt_temp_id FROM receipt_temp WHERE PractitionerID = '$id' AND receipt_number = '$rowReceipt[receipt_number]' AND fees_id = 10";

                                                            $receiptTempQuery = "SELECT receipt_temp_id FROM receipt_temp WHERE (PractitionerID = '$id' OR PractitionerID IS NULL OR LENGTH(PractitionerID) > 10) AND receipt_number = '$rowReceipt[receipt_number]' AND fees_id = 10";
                                                            $resGoodCheck = mysqli_query($conn, $receiptTempQuery);
                                                            if (mysqli_num_rows($resGoodCheck) > 0) { ?>
                                                                <a class="rc-btn" href="goodstanding-certificate.php?source=<?php echo urlencode(base64_encode($rowReceipt['receipt_number'])); ?>&practitioner=<?php echo urlencode(base64_encode($id)); ?>" target="_blank">Certificate</a>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php $i++;
                                                }
                                                ?>
                                                <div class="pagination-wrapper centred">
                                                    <ul class="pagination">
                                                        <?php
                                                        if ($current_page > 1) {
                                                        ?>
                                                            <li><a href="?page=<?php echo $current_page - 1; ?>"><i
                                                                        class="icon-40"></i></a></li>
                                                        <?php
                                                        }
                                                        if ($current_page < $total_pages) {
                                                        ?>
                                                            <li><a href="?page=<?php echo $current_page + 1; ?>"><i
                                                                        class="icon-41"></i></a></li>
                                                        <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            <?php
                                            } ?>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <?php
            require_once 'include/footer.php';
            ?>

        </div>

    <?php
        require_once 'include/footer-js.php';
    }

    ?>

</body>

</html>