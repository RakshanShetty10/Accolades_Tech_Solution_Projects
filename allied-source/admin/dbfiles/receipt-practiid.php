<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT receipt_id, PractitionerID FROM receipt 
    WHERE practitioner_id IS NULL
    ORDER BY receipt_id DESC LIMIT 110");

if(mysqli_num_rows($res)>0){
    
    while($row = mysqli_fetch_assoc($res)){

        // echo $row['PractitionerID'];
        // echo "<br>";
        // echo $row['receipt_id'];
        // echo "<br>";

        $ledger = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE 
            PractitionerID = '$row[PractitionerID]'");

        if(mysqli_num_rows($ledger)>0){

            $ledger = mysqli_fetch_assoc($ledger);
            $practitioner_id = $ledger['practitioner_id'];

            mysqli_query($conn, "UPDATE receipt SET practitioner_id = '$practitioner_id' 
                WHERE receipt_id = '$row[receipt_id]'");
        }

        // echo "<br>";
        // echo "<br>";
    }
}
mysqli_close($conn);
?>