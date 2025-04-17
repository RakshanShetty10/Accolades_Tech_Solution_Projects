<?php
session_start();
require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

$current_date = date("Y-m-d");
$current_year = date("Y");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $site_full;?> - Practitioner Profile</title>
    <?php require_once 'include/header-link.php';?>
    <link href="assets/css/module-css/sidebar.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <?php
        if (empty($_SESSION['_id'])) {
            
            echo "<script>swal.fire({text: 'Kindly login to proceed!',type: 'error',icon: 'error'}).then(function(){window.location = 'login.php';});</script>";
        } else{

            $id = $_SESSION['_id'];

            $renewalCheckQuery = "SELECT receipt_id, receipt_validity FROM receipt WHERE practitioner_id = '$id' 
                                  AND receipt_status = 'Active' AND receipt_for_id = 1 AND receipt_validity >= '$current_date'";
            $resRenewal = mysqli_query($conn, $renewalCheckQuery);
            $isRenewed = mysqli_num_rows($resRenewal) > 0;

            $resWelcome = mysqli_query($conn, "SELECT practitioner_title, practitioner_name, 
                practitioner_change_of_name, practitioner_profile_image FROM practitioner 
                WHERE practitioner_id = '$id'");
            if(mysqli_num_rows($resWelcome)>0){

                $resWelcome = mysqli_fetch_assoc($resWelcome);

                $practitioneer_name = "";

                if(!empty($resWelcome['practitioner_change_of_name'])){
                    $practitioneer_name = $resWelcome['practitioner_change_of_name'] . ' ' . $resWelcome['practitioner_name'];
                } else{
                    $practitioneer_name = $resWelcome['practitioner_title'] . ' ' . $resWelcome['practitioner_name'];
                }

                if(isset($_POST['payment'])){
                    
                    if($_POST['source']=='renewal'){
                        
                        echo "<script>location.href='renewal-payment.php';</script>";
                    } else if($_POST['source']=='goodstanding'){

                        echo "<script>location.href='good-standing.php';</script>";
                    } else if($_POST['source']=='smartcard'){

                        echo "<script>location.href='smart-card.php';</script>";
                    }
                }
    ?>
    <div class="boxed_wrapper ltr">

        <?php require_once 'include/pre-loader.php';?>
        <?php require_once 'include/header.php';?>

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
                                            <a href="receipts.php">Receipts</a>
                                        </li>
                                        <li>
                                            <h6><a href="payments.php">Payments</a></h6>
                                        </li>
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
                                        <form class="widget-title" method="POST">
                                            <h4 style="font-weight: 500;">Hello <?php echo $practitioneer_name;?>,</h4>
                                            <p class="mt_10">Please choose anyone option
                                                below and make payment.</p>
                                                <div class="form-check mt_30">
                                                    <input class="form-check-input" type="radio" name="source"
                                                        value="renewal" id="renewal" checked>
                                                    <label class="form-check-label" for="renewal">
                                                        Renewal Payment
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="source"
                                                        id="goodstanding" value="goodstanding" <?php echo !$isRenewed ? 'disabled' : ''; ?>>
                                                    <label class="form-check-label" for="goodstanding">
                                                        Good Standing Certificate Payment
                                                    </label>
                                                </div>
                                            <?php
                                            $resState = mysqli_query($conn, "SELECT practitioner_address_id 
                                                FROM practitioner_address WHERE state_name = 'Karnataka' 
                                                AND practitioner_address_type = 'Residential' AND practitioner_id = '$id'");
                                            if(mysqli_num_rows($resState)>0){
                                            ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="source"
                                                    id="smartcard" value="smartcard" <?php echo !$isRenewed ? 'disabled' : ''; ?>>
                                                <label class="form-check-label" for="smartcard">
                                                    Smart Card Payment
                                                </label>
                                            </div>
                                            <?php
                                            }
                                            ?>
                                            <button type="submit" name="payment"
                                                style="padding: 11px 33px;border: 1px solid var(--theme-color);"
                                                class="theme-btn btn-three mt_30">Continue</button>
                                            
                                                <?php if(!$isRenewed) { ?>
                                                <div class="mt_30">
                                                    <p class="text-danger">You need to renew for the current year before accessing Good Standing Certificate and Smart Card options.</p>
                                                </div>
                                            <?php } ?>
                                            
                                                <div class="mt_30">
                                                <span class="text-danger">Note: </span>
                                                <small>If you find any problem , then kindly try
                                                    with " KARNATAKA STATE DENTAL COUNCIL" Mobile Application.
                                                    Available for both Android and IOS Mobiles.</small><br><br>
                                                <p>Please contact council to update your
                                                    address for smart card</p>
                                            </div>
                                        </form>
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
        
        } else {
    
            echo "<script>swal.fire({text: 'Unable to process your request.',type: 'error',icon: 'error'}).then(function(){window.location = 'login.php';});</script>";
        }
    }

    ?>

</body>

</html>