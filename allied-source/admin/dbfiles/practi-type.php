<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT registration_type_id, practitioner_id FROM tbl_practitioner");
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        $type = 0;

        if($row['registration_type_id']=='A'){
            $type = 1;
        } else if($row['registration_type_id']=='DH'){
            $type = 3;
        } else if($row['registration_type_id']=='DM'){
            $type = 4;
        } else if($row['registration_type_id']=='DORA'){
            $type = 5;
        } else if($row['registration_type_id']=='P'){
            $type = 6;
        }

        if(mysqli_query($conn, "UPDATE tbl_practitioner SET registration_type_id = '$type' WHERE practitioner_id = '$row[practitioner_id]'")){

        } else{
            echo $row['practitioner_id'];
            echo "<br>";
        }
    }
}

?>