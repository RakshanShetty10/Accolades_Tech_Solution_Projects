<?php
session_start();

require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

if (!empty($_GET['source']) && !empty($_GET['practitioner'])) {
    $receipt_number = urldecode(base64_decode($_GET['source']));
    $practitioner_id = urldecode(base64_decode($_GET['practitioner']));
    
    $resReceipt = mysqli_query($conn, "SELECT * FROM receipt WHERE receipt_number = '$receipt_number'
        AND receipt_status = 'Active'");

    if(mysqli_num_rows($resReceipt)>0){

        $resReceipt = mysqli_fetch_assoc($resReceipt);

        $name = "";
        $register_number = "";
        $practitioner_sign = "";
        
        $resPractitioner = mysqli_query($conn, "SELECT practitioner_title, practitioner_name, practitioner_username, 
            practitioner_change_of_name, registration_type_id, practitioner_signature FROM practitioner WHERE 
            practitioner_id = '$practitioner_id'");

        if(mysqli_num_rows($resPractitioner)>0){

            $resPractitioner = mysqli_fetch_assoc($resPractitioner);

            if(empty($resPractitioner['practitioner_change_of_name'])){

                $name = $resPractitioner['practitioner_title'] . ' ' . $resPractitioner['practitioner_name'];
            } else{
                $name = $resPractitioner['practitioner_title'] . ' ' . $resPractitioner['practitioner_change_of_name'];
            }

            $register_number = $resPractitioner['practitioner_username'];

            $practitioner_sign = $resPractitioner['practitioner_signature'];
        }

        $filename = $receipt_number.".pdf";

        include_once('admin/libs/fpdf.php');

        class PDF extends FPDF {
            function Header() {
                $this->SetTitle('Receipt');
            }
        }

        $pdf = new PDF();
        $pdf->AddPage();
        $pdf->SetDrawColor(169, 169, 169); 
        $pdf->Rect(10, 10, 190, 275); 

        $margin = 5;
        $pdf->SetMargins($margin, $margin, $margin);

        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Image('assets/images/logo/tp_logo.png', 15, 15, 30, 30); 
        $pdf->SetXY(60, 15);
        $pdf->Cell(0, 10, $site_full, 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(60, 21);
        $pdf->Cell(0, 10, $site_address1, 0, 1, 'L');
        $pdf->SetXY(60, 27);
        $pdf->Cell(0, 10, $site_address2.' Telephone:'.$site_phone, 0, 1, 'L');
        $pdf->SetXY(60, 33);
        $pdf->Cell(0, 10, 'Email:'.$registrar_email, 0, 1, 'L');
        $pdf->SetXY(60, 39);
        $pdf->Cell(0, 10, 'Website: https://www.ksdc.in/', 0, 1, 'L');

        $pdf->SetFont('Arial', 'BU', 15);
        $pdf->Cell(0, 15, 'Receipt', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(10); 
        $pdf->Cell(24, 5, 'Receipt No: ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 5, $receipt_number, 0, 0, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(75); 
        $pdf->Cell(12, 5, 'Date: ', 0, 0, 'L'); 
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(40, 5, date_format(date_create($resReceipt['receipt_date']), 'd/m/Y') , 0, 1, 'L');

        $pdf->Ln(1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(10);
        $pdf->Cell(53, 10, 'Received with thanks from ', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, $name, 0, 1, 'L');

        $total_amount = 0;
        $rupees_in_words = ""; 

        // if (is_null($practitioner_id)) {
        //     $receiptTempQuery = "SELECT * FROM receipt_temp WHERE receipt_number = '$receipt_number' AND fees_id != 19";
        // } else {
        //     $receiptTempQuery = "SELECT * FROM receipt_temp WHERE receipt_number = '$receipt_number' AND PractitionerID = '$practitioner_id' AND fees_id != 19";
        // }
       
            $receiptTempQuery = "SELECT * FROM receipt_temp WHERE receipt_number = '$receipt_number' AND (PractitionerID = '$practitioner_id' OR PractitionerID IS NULL OR LENGTH(PractitionerID) > 10) AND fees_id != 19";
        

        $resAmount = mysqli_query($conn, $receiptTempQuery);
        if(mysqli_num_rows($resAmount)>0){
            while($rowAmount = mysqli_fetch_assoc($resAmount)){
                $total_amount += $rowAmount['total_amount'];
            }
        }

        $rupees_in_words = convertToString($total_amount);

        $pdf->Ln(1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(10);
        $pdf->Cell(8, 10, 'Rs:', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(20, 10, $total_amount, 0, 0, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(20, 10, '(Rupees:   ' . $rupees_in_words . '   only.)', 0, 1, 'L');

        $dd_number = "";
        $dd_date = "";
        // if($resReceipt['payment_mode_id']==1){
             $dd_number = $resReceipt['receipt_reference_number'];
             if(!empty($resReceipt['dd_date'])){
                $dd_date = date_format(date_create($resReceipt['dd_date']), 'd/m/Y');
             }
             
        // }
        
        $pdf->Ln(1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(10);
        if($resReceipt['receipt_type']=='Offline'){
            $pdf->Cell(30, 10, 'D.D.No.', 0, 0, 'L');
            $pdf->Cell(80, 10, $dd_number, 0, 0, 'L');
            $pdf->Cell(30, 10, 'D.D.Date', 0, 0, 'L');
            $pdf->Cell(60, 10, $dd_date, 0, 1, 'L');
        } else{
            $pdf->Cell(30, 10, 'Reference No.', 0, 0, 'L');
            $pdf->Cell(80, 10, $dd_number, 0, 0, 'L');
            $pdf->Cell(30, 10, 'Receipt Date', 0, 0, 'L');
            $pdf->Cell(60, 10, date_format(date_create($resReceipt['receipt_date']), 'd/m/Y'), 0, 1, 'L');
        }

        $account_number = "";
        if ($resReceipt['receipt_type'] == 'Online') {
            $account_number = 'Online';
        } else {
            $res_account_number = mysqli_query($conn, "SELECT bank_name FROM bank_master WHERE bank_id = '$resReceipt[bank_id]'");
            
            if (mysqli_num_rows($res_account_number) > 0) {
                $res_account_number = mysqli_fetch_assoc($res_account_number);
                $account_number = $res_account_number['bank_name'];
            } else {
                if (!empty($resReceipt['payment_mode_id'])) {
                    $res_account_number = mysqli_query($conn, "SELECT payment_mode FROM payment_mode WHERE payment_mode_id = '$resReceipt[payment_mode_id]'");
                    if (mysqli_num_rows($res_account_number) > 0) {
                        $res_account_number = mysqli_fetch_assoc($res_account_number);
                        $payment_mode = $res_account_number['payment_mode'];
                    }
                }
            }
        }
        

        $pdf->Ln(1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(10);
        $pdf->Cell(0, 10, 'Drawn on  '.$account_number.'.', 0, 1, 'L');
        $pdf->Ln(1);
        $pdf->Cell(10);
        $pdf->Cell(0, 10, 'Registration No. ' . $register_number, 0, 1, 'L');

        $pdf->Ln(2);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(10);
        $pdf->Cell(115, 10, 'Particulars', 1, 0, 'C');
        $pdf->Cell(60, 10, 'Rs.', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 12);

        $resFor = mysqli_query($conn, "SELECT fees_name, fees_id FROM fees_master
            WHERE fees_status = 'Active' AND NOT fees_id = 19 AND NOT fees_id = 2
            AND NOT fees_id = 3 AND NOT fees_id = 4 AND NOT fees_id = 20");
        if(mysqli_num_rows($resFor)>0){

            $i = 1;
            while($rowFor = mysqli_fetch_assoc($resFor)){

                $temp_amount = "";
                // if (is_null($practitioner_id)) {
                //     $resTemp = mysqli_query($conn, "SELECT total_amount FROM receipt_temp
                //         WHERE fees_id = '$rowFor[fees_id]' AND receipt_number = '$receipt_number' AND NOT fees_id = 19");
                // } else {
                //     $resTemp = mysqli_query($conn, "SELECT total_amount FROM receipt_temp
                //         WHERE fees_id = '$rowFor[fees_id]' AND receipt_number = '$receipt_number' AND PractitionerID = '$practitioner_id' AND NOT fees_id = 19");
                // }

            
                    $resTemp = mysqli_query($conn, "SELECT total_amount FROM receipt_temp
                        WHERE fees_id = '$rowFor[fees_id]' AND receipt_number = '$receipt_number' AND (PractitionerID = '$practitioner_id' OR PractitionerID IS NULL OR LENGTH(PractitionerID) > 10) AND NOT fees_id = 19");
                
                if(mysqli_num_rows($resTemp)>0){

                    $resTemp = mysqli_fetch_assoc($resTemp);
                    
                    $temp_amount = $resTemp['total_amount'];
                }                
                
                $pdf->Cell(10);
                $pdf->Cell(115, 7, $i . '. ' . $rowFor['fees_name'], 'LR'); 
                $pdf->Cell(60, 7, $temp_amount, 'LR', 1, 'C'); 
                $i++;
            }
        }

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(10);
    $pdf->Cell(115, 10, 'Total', 1, 0, 'R');
    $pdf->Cell(60, 10, $total_amount, 1, 1, 'C');

    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(10);
    
    if(!empty($practitioner_sign)){
        $p_sign = 'admin/images/dentist/' . $practitioner_sign;
        if (file_exists($p_sign)) {
            $pdf->Image($p_sign, 25, 252, 24, 24); 
        }
    }
    
    $resRegSign = mysqli_query($conn, "SELECT signature_file FROM signature_master WHERE
        signature_designation = 'Registrar' AND '$resReceipt[receipt_date]' BETWEEN 
        valid_from AND valid_to");
    if(mysqli_num_rows($resRegSign)>0){

        $resRegSign = mysqli_fetch_assoc($resRegSign);

        $p_sign = 'admin/images/signature/' . $resRegSign['signature_file'];
        if (file_exists($p_sign)) {
            $pdf->Image($p_sign, 136, 255, 50, 10);
        }
    }
    $pdf->Cell(90, 15, 'Signature of the applicant', 0, 0, 'L');
    $pdf->Cell(80, 15, 'Signature of the Registrar', 0, 1, 'R');

    $pdf->Output($filename, 'D');
    }
}
?>