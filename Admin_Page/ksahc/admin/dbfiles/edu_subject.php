<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT education_id, course_name FROM education_information
    WHERE education_name = 'MDS' AND course_name != '' AND NOT course_name IS NULL");
    
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){
        
        $ledger = mysqli_query($conn, "SELECT subject_id FROM subject_master WHERE subject_name = '$row[course_name]'");
        if(mysqli_num_rows($ledger)>0){
            $ledger = mysqli_fetch_assoc($ledger);
            $subject_id = $ledger['subject_id'];

            mysqli_query($conn, "UPDATE education_information SET subject_id = '$subject_id', course_name = '' WHERE education_id = '$row[education_id]'");
        }
    }
}

?>