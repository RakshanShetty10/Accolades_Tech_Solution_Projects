<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT receipt_id, bank_id_ksdc FROM receipt 
    ORDER BY receipt_id DESC LIMIT 110");

if(mysqli_num_rows($res)>0){
    
    while($row = mysqli_fetch_assoc($res)){

        $gbank = addslashes($row['bank_id_ksdc']);
        
        $ledger = mysqli_query($conn, "SELECT bank_id FROM bank_master WHERE 
            bank_code_ksdc = '$gbank'");

        if(mysqli_num_rows($ledger)>0){

            $ledger = mysqli_fetch_assoc($ledger);
            $practitioner_id = $ledger['bank_id'];

            mysqli_query($conn, "UPDATE receipt SET bank_id = '$practitioner_id' 
                WHERE receipt_id = '$row[receipt_id]'");
        }
    }
}
mysqli_close($conn);
?>