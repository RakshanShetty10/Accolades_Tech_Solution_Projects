<?php 

$conn = mysqli_connect("localhost", "root", "", "live_ksdc");

$res = mysqli_query($conn, "SELECT education_id, practitioner_code_ksdc FROM education_information 
    ORDER BY education_id ASC LIMIT 1000 OFFSET 10001");

if(mysqli_num_rows($res)>0){
    
    while($row = mysqli_fetch_assoc($res)){
        
        $ledger = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE 
            PractitionerID = '$row[practitioner_code_ksdc]'");
        if(mysqli_num_rows($ledger)>0){
            $ledger = mysqli_fetch_assoc($ledger);
            $practitioner_id = $ledger['practitioner_id'];

            mysqli_query($conn, "UPDATE education_information SET practitioner_id = '$practitioner_id' 
                WHERE education_id = '$row[education_id]'");
        }

        echo $row['education_id'];
        echo "<br>";
    }

    
}
?>