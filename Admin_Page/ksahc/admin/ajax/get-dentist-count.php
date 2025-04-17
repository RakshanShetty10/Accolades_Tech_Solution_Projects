<?php
require_once '../../config/connection.php';

$regTypeA1 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 1 AND YEAR(registration_date) = YEAR(CURDATE()) - 6");
$regTypeA1 = mysqli_num_rows($regTypeA1);

$regTypeA2 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 1 AND YEAR(registration_date) = YEAR(CURDATE()) - 5");
$regTypeA2 = mysqli_num_rows($regTypeA2);

$regTypeA3 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 1 AND YEAR(registration_date) = YEAR(CURDATE()) - 4");
$regTypeA3 = mysqli_num_rows($regTypeA3);

$regTypeA4 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 1 AND YEAR(registration_date) = YEAR(CURDATE()) - 3");
$regTypeA4 = mysqli_num_rows($regTypeA4);

$regTypeA5 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 1 AND YEAR(registration_date) = YEAR(CURDATE()) - 2");
$regTypeA5 = mysqli_num_rows($regTypeA5);

$regTypeA6 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 1 AND YEAR(registration_date) = YEAR(CURDATE()) - 1");
$regTypeA6 = mysqli_num_rows($regTypeA6);

$regTypeA7 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 1 AND YEAR(registration_date) = YEAR(CURDATE())");
$regTypeA7 = mysqli_num_rows($regTypeA7);

$regTypeA = [
    [0, $regTypeA1],
    [1, $regTypeA2],
    [2, $regTypeA3],
    [3, $regTypeA4],
    [4, $regTypeA5],
    [5, $regTypeA6],
    [6, $regTypeA7]
];

$regTypeDH1 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 3 AND YEAR(registration_date) = YEAR(CURDATE()) - 6");
$regTypeDH1 = mysqli_num_rows($regTypeDH1);

$regTypeDH2 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 3 AND YEAR(registration_date) = YEAR(CURDATE()) - 5");
$regTypeDH2 = mysqli_num_rows($regTypeDH2);

$regTypeDH3 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 3 AND YEAR(registration_date) = YEAR(CURDATE()) - 4");
$regTypeDH3 = mysqli_num_rows($regTypeDH3);

$regTypeDH4 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 3 AND YEAR(registration_date) = YEAR(CURDATE()) - 3");
$regTypeDH4 = mysqli_num_rows($regTypeDH4);

$regTypeDH5 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 3 AND YEAR(registration_date) = YEAR(CURDATE()) - 2");
$regTypeDH5 = mysqli_num_rows($regTypeDH5);

$regTypeDH6 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 3 AND YEAR(registration_date) = YEAR(CURDATE()) - 1");
$regTypeDH6 = mysqli_num_rows($regTypeDH6);

$regTypeDH7 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 3 AND YEAR(registration_date) = YEAR(CURDATE())");
$regTypeDH7 = mysqli_num_rows($regTypeDH7);

$regTypeDH = [
    [0, $regTypeDH1],
    [1, $regTypeDH2],
    [2, $regTypeDH3],
    [3, $regTypeDH4],
    [4, $regTypeDH5],
    [5, $regTypeDH6],
    [6, $regTypeDH7]
];

$regTypeDM1 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 4 AND YEAR(registration_date) = YEAR(CURDATE()) - 6");
$regTypeDM1 = mysqli_num_rows($regTypeDM1);

$regTypeDM2 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 4 AND YEAR(registration_date) = YEAR(CURDATE()) - 5");
$regTypeDM2 = mysqli_num_rows($regTypeDM2);

$regTypeDM3 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 4 AND YEAR(registration_date) = YEAR(CURDATE()) - 4");
$regTypeDM3 = mysqli_num_rows($regTypeDM3);

$regTypeDM4 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 4 AND YEAR(registration_date) = YEAR(CURDATE()) - 3");
$regTypeDM4 = mysqli_num_rows($regTypeDM4);

$regTypeDM5 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 4 AND YEAR(registration_date) = YEAR(CURDATE()) - 2");
$regTypeDM5 = mysqli_num_rows($regTypeDM5);

$regTypeDM6 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 4 AND YEAR(registration_date) = YEAR(CURDATE()) - 1");
$regTypeDM6 = mysqli_num_rows($regTypeDM6);

$regTypeDM7 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 4 AND YEAR(registration_date) = YEAR(CURDATE())");
$regTypeDM7 = mysqli_num_rows($regTypeDM7);

$regTypeDM = [
    [0, $regTypeDM1],
    [1, $regTypeDM2],
    [2, $regTypeDM3],
    [3, $regTypeDM4],
    [4, $regTypeDM5],
    [5, $regTypeDM6],
    [6, $regTypeDM7]
];

$regTypeP1 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 6 AND YEAR(registration_date) = YEAR(CURDATE()) - 6");
$regTypeP1 = mysqli_num_rows($regTypeP1);

$regTypeP2 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 6 AND YEAR(registration_date) = YEAR(CURDATE()) - 5");
$regTypeP2 = mysqli_num_rows($regTypeP2);

$regTypeP3 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 6 AND YEAR(registration_date) = YEAR(CURDATE()) - 4");
$regTypeP3 = mysqli_num_rows($regTypeP3);

$regTypeP4 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 6 AND YEAR(registration_date) = YEAR(CURDATE()) - 3");
$regTypeP4 = mysqli_num_rows($regTypeP4);

$regTypeP5 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 6 AND YEAR(registration_date) = YEAR(CURDATE()) - 2");
$regTypeP5 = mysqli_num_rows($regTypeP5);

$regTypeP6 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 6 AND YEAR(registration_date) = YEAR(CURDATE()) - 1");
$regTypeP6 = mysqli_num_rows($regTypeP6);

$regTypeP7 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 6 AND YEAR(registration_date) = YEAR(CURDATE())");
$regTypeP7 = mysqli_num_rows($regTypeP7);

$regTypeP = [
    [0, $regTypeP1],
    [1, $regTypeP2],
    [2, $regTypeP3],
    [3, $regTypeP4],
    [4, $regTypeP5],
    [5, $regTypeP6],
    [6, $regTypeP7]
];

$regTypeDORA1 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 5 AND YEAR(registration_date) = YEAR(CURDATE()) - 6");
$regTypeDORA1 = mysqli_num_rows($regTypeDORA1);

$regTypeDORA2 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 5 AND YEAR(registration_date) = YEAR(CURDATE()) - 5");
$regTypeDORA2 = mysqli_num_rows($regTypeDORA2);

$regTypeDORA3 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 5 AND YEAR(registration_date) = YEAR(CURDATE()) - 4");
$regTypeDORA3 = mysqli_num_rows($regTypeDORA3);

$regTypeDORA4 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 5 AND YEAR(registration_date) = YEAR(CURDATE()) - 3");
$regTypeDORA4 = mysqli_num_rows($regTypeDORA4);

$regTypeDORA5 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 5 AND YEAR(registration_date) = YEAR(CURDATE()) - 2");
$regTypeDORA5 = mysqli_num_rows($regTypeDORA5);

$regTypeDORA6 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 5 AND YEAR(registration_date) = YEAR(CURDATE()) - 1");
$regTypeDORA6 = mysqli_num_rows($regTypeDORA6);

$regTypeDORA7 = mysqli_query($conn, "SELECT practitioner_id FROM practitioner WHERE registration_type_id = 5 AND YEAR(registration_date) = YEAR(CURDATE())");
$regTypeDORA7 = mysqli_num_rows($regTypeDORA7);

$regTypeDORA = [
    [0, $regTypeDORA1],
    [1, $regTypeDORA2],
    [2, $regTypeDORA3],
    [3, $regTypeDORA4],
    [4, $regTypeDORA5],
    [5, $regTypeDORA6],
    [6, $regTypeDORA7]
];

$data = [
    'regTypeA' => $regTypeA,
    'regTypeDH' => $regTypeDH,
    'regTypeDM' => $regTypeDM,
    'regTypeP' => $regTypeP,
    'regTypeDORA' => $regTypeDORA,
];

header('Content-Type: application/json');
echo json_encode($data);

mysqli_close($conn);
?>