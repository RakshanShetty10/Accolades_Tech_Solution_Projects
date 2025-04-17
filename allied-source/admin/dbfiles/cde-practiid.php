<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT cde_points_id, PractitionerID FROM cde_points 
    WHERE practitioner_id IS NULL
    ORDER BY cde_points_id ASC");

if(mysqli_num_rows($res)>0){
    
    while($row = mysqli_fetch_assoc($res)){

        $ledger = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE 
            PractitionerID = '$row[PractitionerID]'");

        if(mysqli_num_rows($ledger)>0){

            $ledger = mysqli_fetch_assoc($ledger);
            $practitioner_id = $ledger['practitioner_id'];

            mysqli_query($conn, "UPDATE cde_points SET practitioner_id = '$practitioner_id' 
                WHERE cde_points_id = '$row[cde_points_id]'");
        }

        // echo "<br>";
        // echo "<br>";
    }
}
?>