<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT college_id, university_id_ksdc FROM college_master");
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){

        $university = $row['university_id_ksdc'];
        
        $ledger = mysqli_query($conn, "SELECT university_id FROM university_master WHERE university_name = '$university'");
        if(mysqli_num_rows($ledger)>0){
            $ledger = mysqli_fetch_assoc($ledger);
            $university_id = $ledger['university_id'];

            mysqli_query($conn, "UPDATE college_master SET university_id = '$university_id' WHERE college_id = '$row[college_id]'");
        }
    }
}

?>