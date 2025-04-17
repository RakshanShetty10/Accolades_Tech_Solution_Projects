<?php 
$conn = mysqli_connect("localhost", "root", "", "live_ksdc");

$res = mysqli_query($conn, "SELECT practitioner_remarks_id, practitioner_id_ksdc FROM practitioner_remarks");
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        
        $ledger = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE PractitionerID = '$row[practitioner_id_ksdc]'");
        if(mysqli_num_rows($ledger)>0){
            $ledger = mysqli_fetch_assoc($ledger);
            $practitioner_id = $ledger['practitioner_id'];

            mysqli_query($conn, "UPDATE practitioner_remarks SET practitioner_id = '$practitioner_id' WHERE practitioner_remarks_id = '$row[practitioner_remarks_id]'");
        }
    }
}

?>