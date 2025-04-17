<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT practitioner_address_id, practitioner_code_ksdc 
    FROM practitioner_address WHERE practitioner_id IS NULL ORDER BY practitioner_address_id DESC LIMIT 25");
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        
        $ledger = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE PractitionerID = '$row[practitioner_code_ksdc]'");
        if(mysqli_num_rows($ledger)>0){
            $ledger = mysqli_fetch_assoc($ledger);
            $practitioner_id = $ledger['practitioner_id'];

            mysqli_query($conn, "UPDATE practitioner_address SET practitioner_id = '$practitioner_id' WHERE practitioner_address_id = '$row[practitioner_address_id]'");
        }
    }
}
mysqli_close($conn);
?>