<?php
    require_once '../../config/connection.php';

    date_default_timezone_set('Asia/Kolkata');

    $resBK = mysqli_query($conn, "SELECT p.practitioner_id
FROM practitioner p
LEFT JOIN (
    SELECT 
        practitioner_id, 
        receipt_validity 
    FROM receipt 
    WHERE receipt_for_id IN (1, 2, 4) 
    ORDER BY receipt_id DESC
) r ON p.practitioner_id = r.practitioner_id
WHERE p.registration_status = 'Active' 
    AND p.registration_type_id = 1 
    AND p.vote_status = 'Eligible'
    AND r.receipt_validity IS NOT NULL
    AND STR_TO_DATE(r.receipt_validity, '%Y-%m-%d') >= CURDATE()");
    $resBK = mysqli_num_rows($resBK);

    $resBK5 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_status = 'Active' AND registration_type_id = 1 
    AND vote_status = 'Eligible'");
    $resBK5 = mysqli_num_rows($resBK5);
    $resBK5 = $resBK5 - $resBK;

    $resBK2 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_status = 'Active' AND registration_type_id = 1 
    AND vote_status = 'Non Indian'");
    $resBK2 = mysqli_num_rows($resBK2);

    $resBK3 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_status = 'Active' AND registration_type_id = 1 
    AND vote_status = 'Suspended [Enquiry]'");
    $resBK3 = mysqli_num_rows($resBK3);

    $resBK6 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_status = 'Active' AND registration_type_id = 1 
    AND vote_status = 'Suspended [Transfer]'");
    $resBK6 = mysqli_num_rows($resBK6);

    $resBK4 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_status = 'Active' AND registration_type_id = 1 
    AND vote_status = 'Death'");
    $resBK4 = mysqli_num_rows($resBK4);

    $data = array($resBK, $resBK5, $resBK2, $resBK3, $resBK6, $resBK4);
    $json_data = json_encode($data);
    echo $json_data;

    mysqli_close($conn);
?>