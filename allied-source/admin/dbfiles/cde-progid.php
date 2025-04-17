<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT cde_points_id, ProgramID FROM cde_points 
    ORDER BY cde_points_id ASC");

if(mysqli_num_rows($res)>0){
    
    while($row = mysqli_fetch_assoc($res)){

        $ledger = mysqli_query($conn, "SELECT cde_program_id FROM cde_program WHERE 
            cde_program_id_ksdc = '$row[ProgramID]'");

        if(mysqli_num_rows($ledger)>0){

            $ledger = mysqli_fetch_assoc($ledger);
            $cde_program_id = $ledger['cde_program_id'];

            mysqli_query($conn, "UPDATE cde_points SET cde_program_id = '$cde_program_id' 
                WHERE cde_points_id = '$row[cde_points_id]'");
        }

        // echo "<br>";
        // echo "<br>";
    }
}
?>