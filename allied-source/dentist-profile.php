<?php
session_start();

require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

if (empty($_GET['source'])) {
            
    echo "<script>alert('Unable to process your request!');location.href='index.php';</script>";
} else{
    
    $id = urldecode(base64_decode($_GET['source']));

    $practitioner_sign = "";
    
    $resPractitioner = mysqli_query($conn, "SELECT practitioner_title, practitioner_name, 
        practitioner_username, practitioner_change_of_name, practitioner_profile_image, 
        practitioner_birth_date, practitioner_gender, practitioner_spouse_name, 
        practitioner_nationality, registration_date FROM 
        practitioner WHERE practitioner_id = '$id'");

    if(mysqli_num_rows($resPractitioner)>0){

        $resPractitioner = mysqli_fetch_assoc($resPractitioner);

        if(empty($resPractitioner['practitioner_change_of_name'])){

            $name = $resPractitioner['practitioner_title'] . ' ' . $resPractitioner['practitioner_name'];
        } else{
            $name = $resPractitioner['practitioner_title'] . ' ' . $resPractitioner['practitioner_change_of_name'];
        }

        $register_number = $resPractitioner['practitioner_username'];

        $date = date('d/m/Y');

        $practitioner_sign = $resPractitioner['practitioner_profile_image'];

        $filename = date('Y-m-d') . ".pdf";

        include_once('admin/libs/fpdf.php');

        class PDF extends FPDF {
            function Header() {
                $this->SetTitle('Dentist Register');
            }
        }

        $pdf = new PDF();
        $pdf->AddPage();

        $pdf->Image('assets/images/logo/tp_logo.png', 40, 14, 27);
        $pdf->SetFont('Arial', '', 18);
        $pdf->Cell(65); 
        $pdf->Cell(0, 10, $site_full, 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(65); 
        $pdf->Cell(0, 6, $site_address1, 0, 1, 'L');
        $pdf->Cell(65); 
        $pdf->Cell(0, 6, $site_address2 . ' Tele Fax:' . $site_phone, 0, 1, 'L');
        $pdf->Cell(65); 
        $pdf->Cell(0, 6, 'Email: ' . $registrar_email, 0, 1, 'L');
        $pdf->Cell(65); 
        $pdf->Cell(0, 6, 'Website: ' . $site_url, 0, 1, 'L');
        $pdf->Ln(6);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(61, 61, 158);
        $pdf->Cell(30); 
        // $pdf->Cell(0, 10, 'KARNATAKA STATE DENTISTS REGISTER', 0, 1, 'C');
        $pdf->Line(0, $pdf->GetY(), 212, $pdf->GetY());
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 10);
        $pdf->cell(15);
        $pdf->SetTextColor(61, 61, 158);
        $pdf->Cell(50, 10, 'Register No:', 0, 0, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, $register_number, 0, 1, 'L');
        $pdf->cell(15);
        $pdf->SetTextColor(61, 61, 158);
        $pdf->Cell(50, 10, 'Name in Full:', 0, 0, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, $name, 0, 1, 'L');
        $pdf->cell(15);
        $pdf->SetTextColor(61, 61, 158);
        $pdf->Cell(50, 10, 'Sex:', 0, 0, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, $resPractitioner['practitioner_gender'], 0, 1, 'L');
        $pdf->cell(15);
        $pdf->SetTextColor(61, 61, 158);
        $pdf->Cell(50, 10, 'Father\'s/Mother\'s Name:', 0, 0, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, $resPractitioner['practitioner_spouse_name'], 0, 1, 'L');
        $pdf->cell(15);
        $pdf->SetTextColor(61, 61, 158);
        $pdf->Cell(50, 10, 'Nationality:', 0, 0, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, $resPractitioner['practitioner_nationality'], 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->cell(15);
        $pdf->SetTextColor(61, 61, 158);
        $pdf->Cell(50, 10, 'Date of Birth:', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, date_format(date_create($resPractitioner['practitioner_birth_date']), 'd/m/Y'), 0, 1, 'L');
        $pdf->cell(15);
        $pdf->SetTextColor(61, 61, 158);
        $pdf->Cell(50, 10, 'Qualification and date of', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0, 0, 0);

        $degree = "";
        $college = "";
        $university = "";
        $month = "";
        $year = "";
        $edu_full = "";

        $resQual = mysqli_query($conn, "SELECT e.subject_id, e.education_name, e.education_year_of_passing, 
            e.education_month_of_passing, c.college_name, u.university_name FROM 
            education_information e, college_master c, university_master u WHERE 
            e.practitioner_id = '$id' AND e.college_id = c.college_id AND
            e.university_id = u.university_id AND (e.education_name = 'BDS'
            OR e.education_name = 'MDS')
            ORDER BY CASE WHEN e.education_name = 'MDS' THEN 1 
            WHEN e.education_name = 'BDS' THEN 2 ELSE 3 END");
            
        if(mysqli_num_rows($resQual)>0){

            $resQual = mysqli_fetch_assoc($resQual);

            $degree = $resQual['education_name'];
            $college = $resQual['college_name'];
            $university = $resQual['university_name'];
            $year = $resQual['education_year_of_passing'];
            $month = $resQual['education_month_of_passing'];

            $subject_name = "";

            if(!empty($resQual['subject_id'])){
                $resSub = mysqli_query($conn, "SELECT subject_name FROM subject_master WHERE subject_id = '$resQual[subject_id]'");
                if (mysqli_num_rows($resSub) > 0) { 
                    $resSub = mysqli_fetch_assoc($resSub); 
                    $subject_name = '- '.$resSub['subject_name']; 
                }
            }

            $edu_full = $degree.' '.$subject_name.': '.$college.'('.$university.') Month: '.$month.' Year: '.$year;
        }

        $pdf->Multicell(0, 6, $edu_full, 0, 'L');
        $pdf->SetTextColor(61, 61, 158);
        $pdf->cell(15);
        $pdf->Cell(0, 5, 'obtaining the certificate:', 40, 50, 'L');
        $pdf->Ln(6);
        $pdf->cell(15);
        $pdf->SetTextColor(61, 61, 158);
        $pdf->Cell(50, 10, 'Additional Qualification:', 0, 0, 'L');
        $pdf->SetTextColor(0, 0, 0);

        $course = "";
        $college = "";
        $university = "";
        $month = "";
        $year = "";
        $edu_full = "";

        $resQualOther = mysqli_query($conn, "SELECT e.course_name, e.education_year_of_passing, 
            e.education_month_of_passing, c.college_name, e.university_id FROM 
            education_information e, college_master c WHERE 
            e.practitioner_id = '$id' AND e.college_id = c.college_id 
            AND e.education_name = 'Other'");
        if(mysqli_num_rows($resQualOther)>0){

            $resQualOther = mysqli_fetch_assoc($resQualOther);

            $course = $resQualOther['course_name'];
            $college = $resQualOther['college_name'];
            
            $month = $resQualOther['education_month_of_passing'];
            $year = $resQualOther['education_year_of_passing'];

            $university = '';
            if(!empty($resQualOther['university_id'])){
                $resinstu = mysqli_query($conn, "SELECT university_name FROM university_master WHERE 
                university_id = '$resQualOther[university_id]'");
        
                if(mysqli_num_rows($resinstu)>0){
        
                    $rowInstu = mysqli_fetch_assoc($resinstu);
                    $university = '('.$rowInstu['university_name'].')';
                
                }
            }
            $edu_full = $course.': '.$college.$university.' Month: '.$month.' Year: '.$year;
        }
        
        $pdf->Multicell(0, 6, $edu_full, 0, 'L');
        $pdf->cell(15);
        $pdf->SetTextColor(61, 61, 158);
        $pdf->Cell(50, 10, 'Good Standing Certificate:', 0, 0, 'L');
        $pdf->SetTextColor(0, 0, 0);

        $goodstanding = "";
        $resGood = mysqli_query($conn, "SELECT receipt_date FROM receipt WHERE 
            practitioner_id = '$id' AND receipt_status = 'Active' AND receipt_for_id = 7");
        if(mysqli_num_rows($resGood)>0){
            $resGood = mysqli_fetch_assoc($resGood);
            $goodstanding = $resGood['receipt_date'];
        }
        $pdf->Cell(0, 10, $goodstanding, 0, 1, 'L');
        $pdf->SetTextColor(61, 61, 158);
        $pdf->cell(15);
        $pdf->Cell(50, 10, 'Date of Registration:', 0, 0, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, date_format(date_create($resPractitioner['registration_date']), 'd/m/Y'), 0, 1, 'L');
        $pdf->SetTextColor(61, 61, 158);
        $pdf->cell(15);
        $pdf->Cell(50, 10, 'Permanent Address:', 0, 0, 'L');
        $pdf->SetTextColor(0, 0, 0);

        $practitioner_address_line1 = "";
        $practitioner_address_line2 = "";
        $practitioner_address_city = "";
        $state_name = "";
        $add_full = "";
        
        $resAddress = mysqli_query($conn, "SELECT practitioner_address_line1, 
            practitioner_address_line2, practitioner_address_city, state_name,
            practitioner_address_pincode
            FROM practitioner_address WHERE practitioner_id = '$id' AND 
            practitioner_address_status = 'Active' AND 
            practitioner_address_type = 'Residential'");
        if(mysqli_num_rows($resAddress)>0){

            $resAddress = mysqli_fetch_assoc($resAddress);

            $practitioner_address_line1 = $resAddress['practitioner_address_line1'];
            $practitioner_address_line2 = $resAddress['practitioner_address_line2'];
            $practitioner_address_city = $resAddress['practitioner_address_city'];
            $state_name = $resAddress['state_name'];
            $practitioner_address_pincode = $resAddress['practitioner_address_pincode'];

            $add_full = $practitioner_address_line1.' '.$practitioner_address_line2.' '.$practitioner_address_city.' '.$state_name . ' ' . $practitioner_address_pincode;
        }
        
        $pdf->MultiCell(0, 6, $add_full, 0, 'L');
        $pdf->Ln(4);
        $pdf->SetTextColor(61, 61, 158);
        $pdf->cell(15);
        $pdf->Cell(50, 10, 'Professional Address:', 0, 0, 'L');
        $pdf->SetTextColor(0, 0, 0);

        $practitioner_address_line1 = "";
        $practitioner_address_line2 = "";
        $practitioner_address_city = "";
        $state_name = "";
        $add_full = "";
        
        $resAddress = mysqli_query($conn, "SELECT practitioner_address_line1, 
            practitioner_address_line2, practitioner_address_city, state_name,
            practitioner_address_pincode
            FROM practitioner_address WHERE practitioner_id = '$id' AND 
            practitioner_address_status = 'Active' AND 
            practitioner_address_type = 'Professional'");
        if(mysqli_num_rows($resAddress)>0){

            $resAddress = mysqli_fetch_assoc($resAddress);

            $practitioner_address_line1 = $resAddress['practitioner_address_line1'];
            $practitioner_address_line2 = $resAddress['practitioner_address_line2'];
            $practitioner_address_city = $resAddress['practitioner_address_city'];
            $state_name = $resAddress['state_name'];
            $practitioner_address_pincode = $resAddress['practitioner_address_pincode'];

            $add_full = $practitioner_address_line1.' '.$practitioner_address_line2.' '.$practitioner_address_city.' '.$state_name . ' ' . $practitioner_address_pincode;
        }
        
        $pdf->Multicell(0, 6, $add_full, 0, 'L');
        $pdf->Ln(10);     
        
    //     if(!empty($practitioner_sign)){
    //     $p_sign = 'admin/images/dentist/' . $practitioner_sign;
    //     if (file_exists($p_sign)) {
    //         $pdf->Image($p_sign, 25, 252, 24, 24); 
    //     }
    // }

        $pdf->Output($filename, 'I');
    } else{

        echo "<script>alert('Unable to process your request!');location.href='index.php';</script>";
    }
}
?>