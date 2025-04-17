<?php 

require_once "../../config/connection.php";
for($id = 1;$id<=200;$id++){
    $code = 999 + $id;
    mysqli_query($conn, "UPDATE ledger_master SET ledger_status = 'Active', ledger_code = '$code' WHERE ledger_id = '$id'");
}

?>