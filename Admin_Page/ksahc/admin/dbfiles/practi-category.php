<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT category_id, practitioner_id FROM practitioner");
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        $type = '';

        if($row['category_id']==0){
            $type = 2;
        } else if($row['category_id']==1){
            $type = 3;
        } else if($row['category_id']==2){
            $type = 4;
        } else if($row['category_id']==3){
            $type = 5;
        } else if($row['category_id']=="General"){
            $type = 2;
        }

        if(mysqli_query($conn, "UPDATE practitioner SET category_id = '$type' WHERE practitioner_id = '$row[practitioner_id]'")){

        } else{
            echo $row['practitioner_id'];
            echo "<br>";
        }
    }
}

?>