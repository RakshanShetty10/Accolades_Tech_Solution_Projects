<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT budget_id, ledger_code FROM budget_master");
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        
        $ledger = mysqli_query($conn, "SELECT ledger_id FROM ledger_master WHERE ledger_code_ksdc = '$row[ledger_code]'");
        if(mysqli_num_rows($ledger)>0){
            $ledger = mysqli_fetch_assoc($ledger);
            $ledger_id = $ledger['ledger_id'];

            mysqli_query($conn, "UPDATE budget_master SET ledger_id = '$ledger_id' WHERE budget_id = '$row[budget_id]'");
        }
    }
}

?>