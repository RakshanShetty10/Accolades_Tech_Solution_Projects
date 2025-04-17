<?php 


require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT cashbook_id, ledger_id FROM cashbook ORDER BY cashbook_id DESC LIMIT 125");
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        
        $ledger = mysqli_query($conn, "SELECT ledger_id FROM ledger_master WHERE ledger_code_ksdc = '$row[ledger_id]'");
        if(mysqli_num_rows($ledger)>0){
            $ledger = mysqli_fetch_assoc($ledger);
            $practitioner_id = $ledger['ledger_id'];

            mysqli_query($conn, "UPDATE cashbook SET ledger_id = '$practitioner_id' WHERE cashbook_id = '$row[cashbook_id]'");
        }
    }
}

?>