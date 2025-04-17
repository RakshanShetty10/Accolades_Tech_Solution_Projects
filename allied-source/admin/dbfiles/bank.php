<?php 

require_once "../../config/connection.php";
for($id = 1;$id<=1200;$id++){
    $code = 999 + $id;
    mysqli_query($conn, "UPDATE bank_master SET bank_code = '$code' WHERE bank_id = '$id'");
}

?>