<?php
session_start();

require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

if (!empty($_GET['source']) && (!empty($_GET['practitioner']))){

    $receipt_number = urldecode(base64_decode($_GET['source']));
    $practitioner_id = urldecode(base64_decode($_GET['practitioner']));
    
    $resReceipt = mysqli_query($conn, "SELECT receipt_number, practitioner_id, receipt_date FROM receipt WHERE receipt_number = '$receipt_number'
        AND receipt_status = 'Active' AND practitioner_id = '$practitioner_id'");

    if(mysqli_num_rows($resReceipt)>0){

        $resReceipt = mysqli_fetch_assoc($resReceipt);

        $name = "";
        $register_number = "";
        
        $resPractitioner = mysqli_query($conn, "SELECT practitioner_title, practitioner_name, practitioner_username, 
            practitioner_change_of_name, practitioner_spouse_name, registration_date 
            FROM practitioner WHERE practitioner_id = '$resReceipt[practitioner_id]'");

        if(mysqli_num_rows($resPractitioner)>0){

            $resPractitioner = mysqli_fetch_assoc($resPractitioner);

            if(empty($resPractitioner['practitioner_change_of_name'])){

                $name = $resPractitioner['practitioner_title'] . ' ' . $resPractitioner['practitioner_name'];
            } else{
                $name = $resPractitioner['practitioner_title'] . ' ' . $resPractitioner['practitioner_change_of_name'];
            }

            $register_number = $resPractitioner['practitioner_username'];
        }

        $filename = $receipt_number.".pdf";

        include_once('admin/libs/fpdf.php');

        class PDF extends FPDF {
            function Header() {
                $this->SetTitle('Goodstanding Certificate');
            }
        }

        $pdf = new PDF();
        $pdf->AddPage('L');
        $pdf->Image('assets/images/certificate/good-stand.png', 0, 0, 297, 210);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(45, 50); 
        $pdf->Cell(0, 10, $receipt_number, 0, 1, 'L');

        $date = "";
        if(!empty($resReceipt['receipt_date'])){
            $date = date_format(date_create($resReceipt['receipt_date']), 'd/m/Y');
        }

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(240, 54); 
        $pdf->Cell(0, 10, $date, 0, 1, 'L');

        $edu_data = "";

        $resEducation = mysqli_query($conn, "SELECT * FROM education_information  
            WHERE practitioner_id = '$resReceipt[practitioner_id]' AND education_status = 'Active'
            AND (education_name = 'BDS' OR education_name = 'MDS' OR education_name='Other' OR education_name='Diploma' OR education_name='PG Diploma')");

        if (mysqli_num_rows($resEducation) > 0) {

            while ($rowEducation = mysqli_fetch_assoc($resEducation)) {
                
                $university = "";
                $resUniversity = mysqli_query($conn, "SELECT university_name FROM university_master WHERE university_status = 'Active' AND university_id = '$rowEducation[university_id]'");
                if (mysqli_num_rows($resUniversity) > 0) { 
                    $resUniversity = mysqli_fetch_assoc($resUniversity); 
                    $university = $resUniversity['university_name']; 
                }

                $subject_name = "";
                $resSub = mysqli_query($conn, "SELECT subject_name FROM subject_master WHERE subject_id = '$rowEducation[subject_id]'");
                if (mysqli_num_rows($resSub) > 0) { 
                    $resSub = mysqli_fetch_assoc($resSub); 
                    $subject_name = $resSub['subject_name']; 
                }

                $edu_data .= $rowEducation['education_name'] . ' '. $subject_name;
                if($rowEducation['education_name']=='MDS'){
                    $edu_data.="\n";
                }
                $edu_data .= "(" . $university . ') ' . $rowEducation['education_month_of_passing'] . ' ' . $rowEducation['education_year_of_passing']."\n";
                
            }
        }

        $pdf->SetFont('Arial', 'B', 10); 
        $pdf->SetXY(110, 94);
        $pdf->MultiCell(90, 5, $edu_data, 0, 'C');
        
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetXY(35, 93); 
        $pdf->Cell(0, 10, $name, 0, 1, 'L');

        $resAddress = mysqli_query($conn, "SELECT * FROM practitioner_address WHERE practitioner_id = '$resReceipt[practitioner_id]' AND practitioner_address_type = 'Residential'");
        if(mysqli_num_rows($resAddress)>0){
            $resAddress = mysqli_fetch_assoc($resAddress);

            $pdf->SetFont('Arial', 'B', 10); 
            $pdf->SetXY(35, 100);
            $address_text = $resAddress['practitioner_address_line1'];
            if(!empty($resAddress['practitioner_address_line2'])){
                $address_text .= "\n" . $resAddress['practitioner_address_line2'];
            }
            $address_text .= "\n" . $resAddress['practitioner_address_city'] . " " . $resAddress['practitioner_address_pincode'] . "\n" . $resAddress['state_name'];
            $pdf->MultiCell(70, 5, $address_text, 0, 'L');
        }

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(250, 93); 
        $pdf->Cell(0, 10, $resPractitioner['practitioner_username'], 0, 1, 'L');


        $reg_date = "";
        if(!empty($resPractitioner['registration_date'])){
            $reg_date = date_format(date_create($resPractitioner['registration_date']), 'd/m/Y');
        }

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetXY(248, 98); 
        $pdf->Cell(0, 10, $reg_date, 0, 1, 'L');

        $resRegSign = mysqli_query($conn, "SELECT signature_file FROM signature_master WHERE
            signature_designation = 'Registrar' AND '$resReceipt[receipt_date]' BETWEEN 
            valid_from AND valid_to");
        if(mysqli_num_rows($resRegSign)>0){

            $resRegSign = mysqli_fetch_assoc($resRegSign);

            $p_sign = 'admin/images/signature/' . $resRegSign['signature_file'];
            if (file_exists($p_sign)) {
                $pdf->Image($p_sign, 200, 145, 50, 15);
            }
            
        }

        $pdf->Output($filename, 'D');
    }
}

mysqli_close($conn);
?>