<?php 

$conn = mysqli_connect("localhost", "root", "", "live_ksdc");

$res = mysqli_query($conn, "SELECT education_id, ksdc_college_id FROM education_information ORDER BY education_id ASC LIMIT 1000000 OFFSET 0");
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        
        $ledger = mysqli_query($conn, "SELECT college_id FROM college_master WHERE college_id_ksdc = '$row[ksdc_college_id]'");
        if(mysqli_num_rows($ledger)>0){
            $ledger = mysqli_fetch_assoc($ledger);
            $college_id = $ledger['college_id'];

            mysqli_query($conn, "UPDATE education_information SET college_id = '$college_id' WHERE education_id = '$row[education_id]'");
        }
    }
}

?>