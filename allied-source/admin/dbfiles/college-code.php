<?php 

require_once "../../config/connection.php";
for($id = 1;$id<=1200;$id++){
    $code = 999 + $id;
    mysqli_query($conn, "UPDATE college_master SET college_code = '$code' WHERE college_id = '$id'");
}

?>