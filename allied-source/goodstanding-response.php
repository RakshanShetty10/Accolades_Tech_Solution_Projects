<?php
session_start();
require_once 'config/connection.php';
require_once 'config/utils.php';
include('ccavenue/Crypto.php');
date_default_timezone_set('Asia/Kolkata');
require_once 'config/mail-helper.php';
require_once 'config/sms-helper.php';

if (empty($_GET['source'])) {
    
    echo "<script>location.href='goodstanding-process.php';</script>";
} else{

    $practitioner_id = urldecode(base64_decode($_GET['source']));

    $res = mysqli_query($conn, "SELECT practitioner_id FROM practitioner
        WHERE practitioner_id = '$practitioner_id'");
        
    if(mysqli_num_rows($res)>0){
        
        $res = mysqli_fetch_assoc($res);

        $date = date('Y-m-d H:i:s');
        $_SESSION['_id'] = $practitioner_id;
        $_SESSION['_logged'] = $date;

        $encResponse=$_POST["encResp"];	
        $rcvdString=decrypt($encResponse,$working_key);	
        $order_status="";
        $order_name="";
        $order_email="";
        $order_phone="";
        $order_trans="";
        $order_id="";
        $order_amount="";
        $order_currency="";
        $decryptValues=explode('&', $rcvdString);
        $dataSize=sizeof($decryptValues);  

        for($i = 0; $i < $dataSize; $i++)   
        {
            $information=explode('=',$decryptValues[$i]);
            if($i==0)	$order_id=$information[1];
            if($i==1)	$order_trans=$information[1];
            if($i==3)	$order_status=$information[1];
            if($i==9)	$order_currency=$information[1];
            if($i==10)	$order_amount=$information[1];
            if($i==11)	$order_name=$information[1];
            if($i==18)	$order_email=$information[1];
            if($i==17)	$order_phone=$information[1];
        }

        if($order_status==="Success")
        {
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
                $receipt_total = $good_standing_amount + $platform_charge;

                $currentDate = date('Y-m-d');
                $timestamp = strtotime($currentDate);

                $year = date('Y', $timestamp);
                $month = date('m', $timestamp);

                if ($month < 4) {
                    $year = $year - 1;
                }

                $receipt_number = $year . '00001';

                $resGet = mysqli_query($conn, "SELECT MAX(receipt_number) AS receipt_number FROM receipt_number_master");
                if(mysqli_num_rows($resGet)>0){
                    $resGet = mysqli_fetch_assoc($resGet);

                    $receipt_db_number = $resGet['receipt_number'];
                    $db_year = substr($receipt_db_number, 0, 4);
                    
                    if($db_year == $year){

                        $receipt_number = $receipt_db_number + 1;
                    }
                }

                $resCheck = mysqli_query($conn, "SELECT receipt_number_master_id FROM receipt_number_master 
                    WHERE receipt_number = '$receipt_number'");
                if(mysqli_num_rows($resCheck)>0){

                    $clientInfo = [
                        'name' => $order_name,
                        'email' => $order_email,
                        'phone' => $order_phone,
                        'trans' => $order_trans,
                        'order' => $order_id,
                        'amount' => $order_amount,
                        'currency' => $order_currency
                    ];

                    $source = urlencode(base64_encode(json_encode($clientInfo)));
                            
                    echo "<script>location.href='goodstanding-process.php?pref=Unkown&source=$source';</script>";
                } else{

                    if(mysqli_query($conn, "INSERT INTO receipt_number_master (receipt_number, created_on, created_by) VALUES ('$receipt_number', '$date', 'Self')")){
                    
                        if(mysqli_query($conn, "INSERT INTO receipt_temp (fees_id, total_amount, receipt_number, PractitionerID, CreatedBy, CreatedOn) VALUES 
                            (10, '$good_standing_amount', '$receipt_number','$practitioner_id', 'Self', '$date'), 
                            (19, '$platform_charge', '$receipt_number','$practitioner_id', 'Self','$date')")){

                            if(mysqli_query($conn, "INSERT INTO receipt (account_id, receipt_total, receipt_date, receipt_reference_number, 
                                receipt_for_id, receipt_type, receipt_created_on, receipt_created_by, 
                                receipt_number, receipt_status, receipt_remit_status, practitioner_id) 
                                VALUES (1, '$receipt_total', '$currentDate', '$order_trans', 7, 'Online', '$date', 
                                'Self', '$receipt_number', 'Active', 'No', '$practitioner_id')")){

                                mysqli_query($conn, "INSERT INTO razorpay (total_amount, practitioner_id, 
                                    payment_status, payment_date, order_id, payment_id, receipt_number) VALUES 
                                    ('$receipt_total', '$practitioner_id', 'Paid', '$date', '$order_id', 
                                    '$order_trans', '$receipt_number')");

                                mysqli_query($conn, "UPDATE receipt_preview SET receipt_status = 'Successful' 
                                    WHERE order_id = '$order_id'");

                                sendGoodStandingMailCC($order_name, $order_email, $receipt_number,$practitioner_id);
                                // sendRenewalSMS($resRegi['mobile_number']);

                                $clientInfo = [
                                    'name' => $order_name,
                                    'email' => $order_email,
                                    'phone' => $order_phone,
                                    'trans' => $order_trans,
                                    'order' => $order_id,
                                    'amount' => $order_amount,
                                    'currency' => $order_currency,
                                    'receipt' => $receipt_number
                                ];

                                $source = urlencode(base64_encode(json_encode($clientInfo)));

                                echo "<script>location.href='goodstanding-process.php?pref=Success&source=$source';</script>";
                            } else{
                                
                                $clientInfo = [
                                    'name' => $order_name,
                                    'email' => $order_email,
                                    'phone' => $order_phone,
                                    'trans' => $order_trans,
                                    'order' => $order_id,
                                    'amount' => $order_amount,
                                    'currency' => $order_currency
                                ];

                                $source = urlencode(base64_encode(json_encode($clientInfo)));
                                
                                echo "<script>location.href='goodstanding-process.php?pref=Unkown&source=$source';</script>";
                            }

                        } else{

                            $clientInfo = [
                                'name' => $order_name,
                                'email' => $order_email,
                                'phone' => $order_phone,
                                'trans' => $order_trans,
                                'order' => $order_id,
                                'amount' => $order_amount,
                                'currency' => $order_currency
                            ];

                            $source = urlencode(base64_encode(json_encode($clientInfo)));
                            
                            echo "<script>location.href='goodstanding-process.php?pref=Unkown&source=$source';</script>";
                        }
                    } else{

                        $clientInfo = [
                            'name' => $order_name,
                            'email' => $order_email,
                            'phone' => $order_phone,
                            'trans' => $order_trans,
                            'order' => $order_id,
                            'amount' => $order_amount,
                            'currency' => $order_currency
                        ];

                        $source = urlencode(base64_encode(json_encode($clientInfo)));

                        echo "<script>location.href='goodstanding-process.php?pref=Unkown&source=$source';</script>";
                    }
                }
            } else{

                $clientInfo = [
                    'name' => $order_name,
                    'email' => $order_email,
                    'phone' => $order_phone,
                    'trans' => $order_trans,
                    'order' => $order_id,
                    'amount' => $order_amount,
                    'currency' => $order_currency
                ];

                $source = urlencode(base64_encode(json_encode($clientInfo)));
                
                echo "<script>location.href='goodstanding-process.php?pref=Unkown&source=$source';</script>";
            }     
        }
        else if($order_status==="Aborted") {

            $clientInfo = [
                'name' => $order_name,
                'email' => $order_email,
                'phone' => $order_phone,
                'trans' => $order_trans,
                'order' => $order_id,
                'amount' => $order_amount,
                'currency' => $order_currency
            ];

            $source = urlencode(base64_encode(json_encode($clientInfo)));
            
            echo "<script>location.href='goodstanding-process.php?pref=Aborted&source=$source';</script>";
        }
        else if($order_status==="Failure") {

            $clientInfo = [
                'name' => $order_name,
                'email' => $order_email,
                'phone' => $order_phone,
                'trans' => $order_trans,
                'order' => $order_id,
                'amount' => $order_amount,
                'currency' => $order_currency
            ];

            $source = urlencode(base64_encode(json_encode($clientInfo)));

            echo "<script>location.href='goodstanding-process.php?pref=Failure&source=$source';</script>";
        } else {

            $clientInfo = [
                'name' => $order_name,
                'email' => $order_email,
                'phone' => $order_phone,
                'trans' => $order_trans,
                'order' => $order_id,
                'amount' => $order_amount,
                'currency' => $order_currency
            ];

            $source = urlencode(base64_encode(json_encode($clientInfo)));
            
            echo "<script>location.href='goodstanding-process.php?pref=Other&source=$source';</script>";
        }
    } else{
        
        echo "<script>location.href='goodstanding-process.php';</script>";
    }            
        
}

    ?>