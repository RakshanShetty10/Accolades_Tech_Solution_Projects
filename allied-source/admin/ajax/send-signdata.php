<?php

require_once '../../config/connection.php';

date_default_timezone_set('Asia/Kolkata');
if (isset($_POST['signatureDataSend']) && isset($_POST['id'])) {
    $signatureData = $_POST['signatureDataSend'];
    $id = $_POST['id']; 
    if (!is_dir("../images/dentist/" . $id . "/")) {
        mkdir("../images/dentist/" . $id . "/");
    }

    $signatureData = str_replace(['data:image/png;base64,', ' '], ['', '+'], $signatureData);
    $decodedSignature = base64_decode($signatureData);

    $fileName = time() . '.png';
    $filePath = "../images/dentist/" . $id . "/" . $fileName;

    $fileName = $id . "/" . $fileName;

    if (file_put_contents($filePath, $decodedSignature)) {
        if(mysqli_query($conn, "UPDATE practitioner SET practitioner_signature = '$fileName' WHERE practitioner_id = '$id'")){
            echo "done";
        } else {
            echo "failed";
        }
    } else {
        echo "failed";
    }
}

mysqli_close($conn);
?>