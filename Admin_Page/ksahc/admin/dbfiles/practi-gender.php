<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT practitioner_gender, practitioner_id FROM tbl_practitioner");
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        $type = "";

        if($row['practitioner_gender']==0){
            $type = "Male";
        } else if($row['practitioner_gender']==1){
            $type = "Female";
        }

        if(mysqli_query($conn, "UPDATE tbl_practitioner SET practitioner_gender = '$type' WHERE practitioner_id = '$row[practitioner_id]'")){

        } else{
            echo $row['practitioner_id'];
            echo "<br>";
        }
    }
}

?>