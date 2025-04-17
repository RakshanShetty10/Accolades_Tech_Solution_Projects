<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT vote_status, practitioner_id FROM tbl_practitioner");
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        $type = "";

        if($row['vote_status']==0){
            $type = "Eligible";
        } else if($row['vote_status']==1){
            $type = "Non Indian";
        } else if($row['vote_status']==2){
            $type = "Suspended [Transfer]";
        } else if($row['vote_status']==3){
            $type = "Suspended [Enquiry]";
        } else if($row['vote_status']==4){
            $type = "Suspended [Transfer]";
        } else if($row['vote_status']==5){
            $type = "Death";
        }

        if(mysqli_query($conn, "UPDATE tbl_practitioner SET vote_status = '$type' WHERE practitioner_id = '$row[practitioner_id]'")){

        } else{
            echo $row['practitioner_id'];
            echo "<br>";
        }
    }
}

?>