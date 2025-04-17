<?php

    require_once '../../config/connection.php';

	$registrationType = $_POST['registrationType'];

    $response = "1";

    $resReg = mysqli_query($conn, "SELECT MAX(registration_number) AS registration_number FROM practitioner 
        WHERE registration_type_id = '$registrationType' AND NOT registration_status = 'Deleted'");

    if(mysqli_num_rows($resReg)>0){

        $resReg = mysqli_fetch_assoc($resReg);

        $registration_number = $resReg['registration_number'];

        $response = $registration_number + 1;
    }

    echo $response;

	mysqli_close($conn);
    ?>