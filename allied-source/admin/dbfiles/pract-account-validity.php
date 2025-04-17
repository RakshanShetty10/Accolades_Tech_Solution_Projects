<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT PractitionerID FROM practitioner 
    WHERE practitioner_account_validity IS NULL
    ORDER BY practitioner_id DESC LIMIT 25");

if(mysqli_num_rows($res)>0){

    while($row = mysqli_fetch_assoc($res)){

        $resV = mysqli_query($conn, "SELECT ValidUpto FROM receipt 
            WHERE PractitionerID = '$row[PractitionerID]' AND 
            (receipt_for_id = 1 OR receipt_for_id = 2 OR receipt_for_id = 4) 
            ORDER BY ValidUpto DESC LIMIT 1"); 
        if(mysqli_num_rows($resV)>0){

            $resV = mysqli_fetch_assoc($resV);

            $validity = $resV['ValidUpto'];

            mysqli_query($conn, "UPDATE practitioner SET 
                practitioner_account_validity = '$validity' WHERE PractitionerID = '$row[PractitionerID]'");
        }
    }
}
mysqli_close($conn);
?>