<?php
session_start();
require_once 'config/connection.php';
require_once 'config/utils.php';
include('ccavenue/Crypto.php');

date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $site_full;?> - Payment</title>
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

            $resWelcome = mysqli_query($conn, "SELECT practitioner_title, practitioner_name, 
                practitioner_change_of_name, practitioner_email_id, practitioner_mobile_number FROM practitioner 
                WHERE practitioner_id = '$id'");
            if(mysqli_num_rows($resWelcome)>0){

                $resWelcome = mysqli_fetch_assoc($resWelcome);

                $practitioneer_name = "";

                if(!empty($resWelcome['practitioner_change_of_name'])){
                    $practitioneer_name = $resWelcome['practitioner_change_of_name'] . ' ' . $resWelcome['practitioner_name'];
                } else{
                    $practitioneer_name = $resWelcome['practitioner_title'] . ' ' . $resWelcome['practitioner_name'];
                } 

                $phone_no = $resWelcome['practitioner_mobile_number'];
                $email_id = $resWelcome['practitioner_email_id'];
    ?>
    <div class="boxed_wrapper ltr">
        <?php require_once 'include/header.php';?>

        <section class="sidebar-page-container pt_100 pb_100 border-top">
            <div class="auto-container">
                <div class="row clearfix">
                    <?php

                            $resGood = mysqli_query($conn, "SELECT fees_amount FROM fees_master WHERE 
                                fees_id = 10");
                            if(mysqli_num_rows($resGood)>0){

                                $resGood = mysqli_fetch_assoc($resGood);

                                $good_standing_amount = $resGood['fees_amount'];
                                $platform_rate = 0;

                                if(!empty($platform_persentage)){
                                    $platfrate = $platform_persentage;
                                }

                                $platform_charge = floor(($good_standing_amount * $platfrate) / 100);

                                $grand_total = $good_standing_amount + $platform_charge;

                                $order_number = 'order_'.uniqid();
                                $transaction_number = time();
                                $param = urlencode(base64_encode($id));

                                $merchant_data = 'currency=INR&tid='.$transaction_number.'&merchant_id=22&order_id='.$order_number.'&amount='.$grand_total.'&redirect_url=https://www.ksdc.in/goodstanding-response.php?source='.$param.'&language=EN&billing_name='.$practitioneer_name.'&billing_tel='.$phone_no.'&billing_email='.$email_id;

                                $encrypted_data=encrypt($merchant_data,$working_key);
                            ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 content-side pl_60">
                        <div class="blog-standard-content">
                            <div class="news-block-three">
                                <div class="inner-box">
                                    <div class="lower-content">
                                        <div class="widget-title">
                                            <h4 style="font-weight: 500;">Hello <?php echo $practitioneer_name;?>,</h4>

                                            <table class="mt_40 table table-hover">
                                                <tr>
                                                    <td>Good Standing Fee</td>
                                                    <td class="text-end">
                                                        <?php echo number_format($good_standing_amount, 2);?></td>
                                                </tr>
                                                <tr>
                                                    <td>Platform Charges</td>
                                                    <td class="text-end">
                                                        <?php echo number_format($platform_charge, 2);?></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h6>Grand Total</h6>
                                                    </td>
                                                    <td class="text-end">
                                                        <h6><?php echo number_format($grand_total, 2);?></h6>
                                                    </td>
                                                </tr>
                                            </table>
                                            <form method="post" name="redirect" id="paymentForm" 
                                                action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction">
                                                <?php
                                                    echo "<input type=hidden name=encRequest value=$encrypted_data>";
                                                    echo "<input type=hidden name=access_code value=$access_code>";
                                                ?>

                                                <button type="submit"
                                                    style="padding: 11px 33px;border: 1px solid var(--theme-color);"
                                                    class="theme-btn btn-three mt_30">Pay Now</button>
                                            </form>
                                            <div class="mt_30">
                                                <span class="text-danger">Note: </span>
                                                <small>The payment process may take a moment, so kindly wait for
                                                    confirmation before navigating away to ensure your transaction is
                                                    successfully processed. Thank you for your patience and
                                                    cooperation!</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    } else{
                            echo "<script>swal.fire({text: 'Unable to process your request.',type: 'error',icon: 'error'}).then(function(){window.location = 'payments.php';});</script>";
                        }
                    ?>
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
<script>
    $(document).ready(function() {
        $('#paymentForm').on('submit', function(e) {
            e.preventDefault();
            
            let practitioner_id = '<?php echo $id; ?>';
            let order_number = '<?php echo $order_number; ?>';
            let transaction_number = '<?php echo $transaction_number; ?>';

            $.ajax({
                url: 'ajax/good-standing-preview.php', 
                type: 'POST',
                data: {
                    practitioner_id: practitioner_id,
                    order_number: order_number,
                    transaction_number: transaction_number
                }, 
                success: function(response) {
                    let res = JSON.parse(response);

                    if (res.success) {
                        $('#paymentForm')[0].submit(); 
                    } else {
                        Swal.fire({
                            text: 'Unable to process your request.',
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        text: 'Unable to process your request.',
                        icon: 'error'
                    });
                }
            });
        });
    });
</script>
</body>

</html>