<?php 

require_once "../../config/connection.php";

$res = mysqli_query($conn, "SELECT practitioner_id, practitioner_birth_date 
    FROM practitioner WHERE practitioner_password IS NULL ORDER BY practitioner_id ASC LIMIT 25");
if(mysqli_num_rows($res)>0){
    while($row = mysqli_fetch_assoc($res)){

        // if(!empty($row['practitioner_birth_date'])){

            $a_pass = date_format(date_create($row['practitioner_birth_date']), 'd/m/Y');
        
            $password = password_hash($a_pass, PASSWORD_BCRYPT);

            mysqli_query($conn, "UPDATE practitioner SET practitioner_password = '$password' 
                WHERE practitioner_id = '$row[practitioner_id]'");
        // }
        
    }
}
mysqli_close($conn);
?>