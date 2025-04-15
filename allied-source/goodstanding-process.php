<?php
session_start();
require_once 'config/connection.php';
require_once 'config/utils.php';
include('ccavenue/Crypto.php');
date_default_timezone_set('Asia/Kolkata');
require_once 'config/mail-helper.php';
require_once 'config/sms-helper.php';

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
    <div class="boxed_wrapper ltr">

        <?php #require_once 'include/pre-loader.php';?>
        <?php require_once 'include/header.php';?>
        <section class="faq-form-section pt_80 pb_80" style="background-color: whitesmoke;">

            <?php
        if (empty($_GET['pref'])) {
            
            echo "<script>swal.fire({text: 'Unable to process your request!',type: 'error',icon: 'error'}).then(function(){window.location = 'login.php';});</script>";
        } else{
            
            if($_GET['pref'] == 'Failure'){
                
                $name = "";
                $email = "";
                $phone = "";
                $trans = "";
                $order = "";
                $amount = "";
                $currency = "";

                if (isset($_GET['source'])) {
                    
                    $encodedData = $_GET['source'];
                    $jsonData = base64_decode($encodedData);
                    $clientInfo = json_decode($jsonData, true);
                    
                    $name = $clientInfo['name'];
                    $email = $clientInfo['email'];
                    $phone = $clientInfo['phone'];
                    $trans = $clientInfo['trans'];
                    $order = $clientInfo['order'];
                    $amount = $clientInfo['amount'];
                    $currency = $clientInfo['currency'];
                }

                ?>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-5 shadow p-5">
                        <div class="message-box _success" style="text-align: center;">
                            <i style="font-size: 55px; color: red;" class="fa fa-times-circle" aria-hidden="true"></i>
                            <h4 class="mt-3"> Payment Failed!</h4>
                            <p class="pt-2">The transaction has been declined or failed.</p>
                            <table class="mt-3 table">
                                <tr>
                                    <td class="text-start">Name</td>
                                    <td class="text-start"><?php echo $name;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Phone Number</td>
                                    <td class="text-start"><?php echo $phone;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Email Id</td>
                                    <td class="text-start"><?php echo $email;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Transaction Id</td>
                                    <td class="text-start"><?php echo $trans;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Order Id</td>
                                    <td class="text-start"><?php echo $order;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Total Amount</td>
                                    <td class="text-start"><?php echo $amount;?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="text-end">
                            <a class="mt-4" style="color:#e36e00" href="payments.php">Back to payment</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            } else if($_GET['pref'] == 'Aborted'){
                
                $name = "";
                $email = "";
                $phone = "";
                $trans = "";
                $order = "";
                $amount = "";
                $currency = "";

                if (isset($_GET['source'])) {
                    
                    $encodedData = $_GET['source'];
                    $jsonData = base64_decode($encodedData);
                    $clientInfo = json_decode($jsonData, true);
                    
                    $name = $clientInfo['name'];
                    $email = $clientInfo['email'];
                    $phone = $clientInfo['phone'];
                    $trans = $clientInfo['trans'];
                    $order = $clientInfo['order'];
                    $amount = $clientInfo['amount'];
                    $currency = $clientInfo['currency'];
                }

                ?>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-5 shadow p-5">
                        <div class="message-box _success" style="text-align: center;">
                            <i style="font-size: 55px; color: red;" class="fa fa-times-circle" aria-hidden="true"></i>
                            <h4 class="mt-3"> Payment Failed!</h4>
                            <p class="pt-2">The transaction has been aborted or cancelled.</p>
                            <table class="mt-3 table">
                                <tr>
                                    <td class="text-start">Name</td>
                                    <td class="text-start"><?php echo $name;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Phone Number</td>
                                    <td class="text-start"><?php echo $phone;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Email Id</td>
                                    <td class="text-start"><?php echo $email;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Transaction Id</td>
                                    <td class="text-start"><?php echo $trans;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Order Id</td>
                                    <td class="text-start"><?php echo $order;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Total Amount</td>
                                    <td class="text-start"><?php echo $amount;?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="text-end">
                            <a class="mt-4" style="color:#e36e00" href="payments.php">Back to payment</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            } else if($_GET['pref']=="Success") {

                $name = "";
                $email = "";
                $phone = "";
                $trans = "";
                $order = "";
                $amount = "";
                $currency = "";
                $receipt = "";

                if (isset($_GET['source'])) {
                    
                    $encodedData = $_GET['source'];
                    $jsonData = base64_decode($encodedData);
                    $clientInfo = json_decode($jsonData, true);
                    
                    $name = $clientInfo['name'];
                    $email = $clientInfo['email'];
                    $phone = $clientInfo['phone'];
                    $trans = $clientInfo['trans'];
                    $order = $clientInfo['order'];
                    $amount = $clientInfo['amount'];
                    $currency = $clientInfo['currency'];
                    $receipt = $clientInfo['receipt'];

                    $param = urlencode(base64_encode($receipt));
                }

                ?>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-5 shadow p-5">
                        <div class="message-box _success" style="text-align: center;">
                            <i style="font-size: 55px; color: #28a745;" class="fa fa-check-circle"
                                aria-hidden="true"></i>
                            <h4 class="mt-3"> Payment Success!</h4>
                            <p class="pt-2">Your payment successful! Thank you.</p>
                            <table class="mt-3 table">
                                <tr>
                                    <td class="text-start">Name</td>
                                    <td class="text-start"><?php echo $name;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Phone Number</td>
                                    <td class="text-start"><?php echo $phone;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Email Id</td>
                                    <td class="text-start"><?php echo $email;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Transaction Id</td>
                                    <td class="text-start"><?php echo $trans;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Order Id</td>
                                    <td class="text-start"><?php echo $order;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Total Amount</td>
                                    <td class="text-start"><?php echo $amount;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Receipt</td>
                                    <td class="text-start"><a
                                            href="view-receipt.php?source=<?php echo $param;?>">View</a></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Certificate</td>
                                    <td class="text-start"><a
                                            href="goodstanding-certificate.php?source=<?php echo $param;?>">View</a></td>
                                </tr>
                            </table>
                        </div>
                        <div class="text-end">
                            <a class="mt-4" style="color:#e36e00" href="payments.php">Back to payment</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            
            } else if($_GET['pref']=="Unkown"){
                
                $name = "";
                $email = "";
                $phone = "";
                $trans = "";
                $order = "";
                $amount = "";
                $currency = "";

                if (isset($_GET['source'])) {
                    
                    $encodedData = $_GET['source'];
                    $jsonData = base64_decode($encodedData);
                    $clientInfo = json_decode($jsonData, true);
                    
                    $name = $clientInfo['name'];
                    $email = $clientInfo['email'];
                    $phone = $clientInfo['phone'];
                    $trans = $clientInfo['trans'];
                    $order = $clientInfo['order'];
                    $amount = $clientInfo['amount'];
                    $currency = $clientInfo['currency'];
                }

                ?>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-5 shadow p-5">
                        <div class="message-box _success" style="text-align: center;">
                            <i style="font-size: 55px; color: red;" class="fa fa-times-circle" aria-hidden="true"></i>
                            <h4 class="mt-3"> Payment Failed!</h4>
                            <p class="pt-2">We're unable to process your payment at this time. Kindly contact us.</p>
                            <table class="mt-3 table">
                                <tr>
                                    <td class="text-start">Name</td>
                                    <td class="text-start"><?php echo $name;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Phone Number</td>
                                    <td class="text-start"><?php echo $phone;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Email Id</td>
                                    <td class="text-start"><?php echo $email;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Transaction Id</td>
                                    <td class="text-start"><?php echo $trans;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Order Id</td>
                                    <td class="text-start"><?php echo $order;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Total Amount</td>
                                    <td class="text-start"><?php echo $amount;?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="text-end">
                            <a class="mt-4" style="color:#e36e00" href="payments.php">Back to payment</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            } else{
                
                $name = "";
                $email = "";
                $phone = "";
                $trans = "";
                $order = "";
                $amount = "";
                $currency = "";

                if (isset($_GET['source'])) {
                    
                    $encodedData = $_GET['source'];
                    $jsonData = base64_decode($encodedData);
                    $clientInfo = json_decode($jsonData, true);
                    
                    $name = $clientInfo['name'];
                    $email = $clientInfo['email'];
                    $phone = $clientInfo['phone'];
                    $trans = $clientInfo['trans'];
                    $order = $clientInfo['order'];
                    $amount = $clientInfo['amount'];
                    $currency = $clientInfo['currency'];
                }

                ?>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-5 shadow p-5">
                        <div class="message-box _success" style="text-align: center;">
                            <i style="font-size: 55px; color: red;" class="fa fa-times-circle" aria-hidden="true"></i>
                            <h4 class="mt-3"> Payment Failed!</h4>
                            <p class="pt-2">An unexpected error occurred while processing your payment.</p>
                            <table class="mt-3 table">
                                <tr>
                                    <td class="text-start">Name</td>
                                    <td class="text-start"><?php echo $name;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Phone Number</td>
                                    <td class="text-start"><?php echo $phone;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Email Id</td>
                                    <td class="text-start"><?php echo $email;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Transaction Id</td>
                                    <td class="text-start"><?php echo $trans;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Order Id</td>
                                    <td class="text-start"><?php echo $order;?></td>
                                </tr>
                                <tr>
                                    <td class="text-start">Total Amount</td>
                                    <td class="text-start"><?php echo $amount;?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="text-end">
                            <a class="mt-4" style="color:#e36e00" href="payments.php">Back to payment</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            }
        
        }

    ?>
        </section>
        <?php 
            require_once 'include/footer.php';
        ?>

    </div>

    <?php 
        require_once 'include/footer-js.php';
    ?>

</body>

</html>