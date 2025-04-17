<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT education_id, ksdc_subject FROM education_information
    WHERE education_name = 'Other' AND ksdc_subject != '' AND NOT ksdc_subject IS NULL");
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        
        mysqli_query($conn, "UPDATE education_information SET course_name = '$row[ksdc_subject]' 
            WHERE education_id = '$row[education_id]'");
    }
}

?>