<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT receipt_number FROM receipt 
    ORDER BY receipt_number desc LIMIT 1000");

if(mysqli_num_rows($res)>0){
    
    while($row = mysqli_fetch_assoc($res)){

        $resc = mysqli_query($conn, "SELECT receipt_number FROM receipt_number_master
            WHERE receipt_number = '$row[receipt_number]'");
        if(mysqli_num_rows($resc)==0){
            mysqli_query($conn, "INSERT INTO receipt_number_master (receipt_number) VALUES ('$row[receipt_number]')");
        }

        
    }
}
mysqli_close($conn);
?>