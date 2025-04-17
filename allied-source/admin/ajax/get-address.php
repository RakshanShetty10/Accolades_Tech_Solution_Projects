<?php
require_once '../../config/connection.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$address_line1_res = "";
$address_line2_res = "";
$residential_city_res = "";
$address_type_res = "";
$postal_code_res = "";
$stateInputResidential = "";
$countryInputResidential = "India";
$mobile_number_res = "";
$secondary_number_res = "";

$address_line1 = "";
$address_line2 = "";
$residential_city = "";
$address_type = "";
$postal_code = "";
$stateInputProfessional = "";
$countryInputProfessional = "India";
$mobile_number = "";
$secondary_number = "";

if ($id > 0) {

    $resProfes = mysqli_query($conn, "SELECT practitioner_address_category, practitioner_address_line1, 
        practitioner_address_line2, practitioner_address_city, state_name, practitioner_address_pincode, 
        country_name, practitioner_address_secondary_phoneno, practitioner_address_phoneno FROM 
        practitioner_address WHERE practitioner_id = '$id' AND practitioner_address_type = 'Professional'");
    if(mysqli_num_rows($resProfes)>0){
        
        $resProfes = mysqli_fetch_assoc($resProfes);

        $address_type = $resProfes['practitioner_address_category'];
        $address_line1 = $resProfes['practitioner_address_line1'];
        $address_line2 = $resProfes['practitioner_address_line2'];
        $residential_city = $resProfes['practitioner_address_city'];
        $stateInputProfessional = $resProfes['state_name'];
        $postal_code = $resProfes['practitioner_address_pincode'];
        $countryInputProfessional = $resProfes['country_name'];
        $secondary_number = $resProfes['practitioner_address_secondary_phoneno'];
        $mobile_number = $resProfes['practitioner_address_phoneno'];
    }

    $resResiden = mysqli_query($conn, "SELECT practitioner_address_category, practitioner_address_line1, 
        practitioner_address_line2, practitioner_address_city, state_name, practitioner_address_pincode, 
        country_name, practitioner_address_secondary_phoneno, practitioner_address_phoneno FROM 
        practitioner_address WHERE practitioner_id = '$id' AND practitioner_address_type = 'Residential'");
    if(mysqli_num_rows($resResiden)>0){
        
        $resResiden = mysqli_fetch_assoc($resResiden);

        $address_type_res = $resResiden['practitioner_address_category'];
        $address_line1_res = $resResiden['practitioner_address_line1'];
        $address_line2_res = $resResiden['practitioner_address_line2'];
        $residential_city_res = $resResiden['practitioner_address_city'];
        $stateInputResidential = $resResiden['state_name'];
        $postal_code_res = $resResiden['practitioner_address_pincode'];
        $countryInputResidential = $resResiden['country_name'];
        $secondary_number_res = $resResiden['practitioner_address_secondary_phoneno'];
        $mobile_number_res = $resResiden['practitioner_address_phoneno'];
    }
}

echo json_encode(['address_type' => $address_type, 'address_line1' => $address_line1, 
    'address_line2' => $address_line2, 'residential_city' => $residential_city, 
    'stateInputProfessional' => $stateInputProfessional, 'postal_code' => $postal_code, 
    'countryInputProfessional' => $countryInputProfessional, 'secondary_number' => $secondary_number,
    'mobile_number' => $mobile_number, 'address_type_res' => $address_type_res, 
    'address_line1_res' => $address_line1_res, 'address_line2_res' => $address_line2_res, 
    'residential_city_res' => $residential_city_res, 'stateInputResidential' => $stateInputResidential, 
    'secondary_number_res' => $secondary_number_res, 'mobile_number_res' => $mobile_number_res, 
    'postal_code_res' => $postal_code_res, 'countryInputResidential' => $countryInputResidential]);

mysqli_close($conn);
?>