<?php
session_start();
require_once '../../config/connection.php';

// Set SQL mode to empty string
$conn->query("SET SESSION sql_mode = ''");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$type = isset($_GET['type']) ? (int)$_GET['type'] : 0;
$response = "";

// echo "ID: $id, Type: $type<br>";

if ($id > 0) {
    // Fetch PractitionerID from receipt_temp
    $practitioner_stmt = $conn->prepare("SELECT PractitionerID FROM receipt_temp WHERE PractitionerID = ?");
    $practitioner_stmt->bind_param("i", $id);
    $practitioner_stmt->execute();
    $practitioner_result = $practitioner_stmt->get_result();

    // if ($practitioner_result->num_rows > 0) {
    //     $practitioner_row = $practitioner_result->fetch_assoc();
    //     $practitioner_id = $practitioner_row['PractitionerID'];
    //     // echo "PractitionerID found: $practitioner_id<br>";

    //     $stmt = $conn->prepare("SELECT r.receipt_id, r.receipt_date, r.receipt_reference_number, 
    //         r.dd_date, r.payment_mode_id, r.receipt_for_id, r.receipt_number, r.bank_id, r.receipt_status, 
    //         rf.receipt_for, b.bank_name, pm.payment_mode, rt.PractitionerID,
    //         SUM(CASE WHEN rt.total_amount IS NOT NULL THEN rt.total_amount END) as total_amount,
    //         GROUP_CONCAT(rt.fees_id) as fees_ids 
    //     FROM receipt r 
    //     LEFT JOIN receipt_for_master rf ON r.receipt_for_id = rf.receipt_for_id 
    //     LEFT JOIN bank_master b ON r.bank_id = b.bank_id 
    //     LEFT JOIN payment_mode pm ON r.payment_mode_id = pm.payment_mode_id 
    //     LEFT JOIN (
    //         SELECT rt.receipt_number, rt.fees_id, rt.total_amount, rt.PractitionerID
    //         FROM receipt_temp rt
    //         LEFT JOIN fees_master fm ON rt.fees_id = fm.fees_id
    //         WHERE rt.PractitionerID = ? OR rt.PractitionerID IS NULL
    //     ) rt ON r.receipt_number = rt.receipt_number 
    //     WHERE r.practitioner_id = ? AND r.receipt_status != 'Deleted' 
    //     GROUP BY r.receipt_id 
    //     ORDER BY r.receipt_id DESC 
    //     LIMIT 15");
    //     $stmt->bind_param("ii", $practitioner_id, $practitioner_id);
    // } else {
    //     // echo "No PractitionerID found<br>";
    //     $stmt = $conn->prepare("SELECT r.receipt_id, r.receipt_date, r.receipt_reference_number, 
    //         r.dd_date, r.payment_mode_id, r.receipt_for_id, r.receipt_number, r.bank_id, r.receipt_status, 
    //         rf.receipt_for, b.bank_name, pm.payment_mode,
    //         SUM(CASE WHEN rt.total_amount IS NOT NULL THEN rt.total_amount END) as total_amount,
    //         GROUP_CONCAT(rt.fees_id) as fees_ids 
    //     FROM receipt r 
    //     LEFT JOIN receipt_for_master rf ON r.receipt_for_id = rf.receipt_for_id 
    //     LEFT JOIN bank_master b ON r.bank_id = b.bank_id 
    //     LEFT JOIN payment_mode pm ON r.payment_mode_id = pm.payment_mode_id 
    //     LEFT JOIN (
    //         SELECT rt.receipt_number, rt.fees_id, rt.total_amount
    //         FROM receipt_temp rt
    //         LEFT JOIN fees_master fm ON rt.fees_id = fm.fees_id
    //     ) rt ON r.receipt_number = rt.receipt_number 
    //     WHERE r.receipt_status != 'Deleted' 
    //     GROUP BY r.receipt_id 
    //     ORDER BY r.receipt_id DESC 
    //     LIMIT 15");
    // }

    if ($practitioner_result->num_rows > 0) {
        $practitioner_row = $practitioner_result->fetch_assoc();
        $practitioner_id_temp = $practitioner_row['PractitionerID'];
    } 
    
    // Use a single query with practitioner_id filter always
    $stmt = $conn->prepare("SELECT r.receipt_id, r.practitioner_id, r.receipt_date, r.receipt_reference_number, 
        r.dd_date, r.payment_mode_id, r.receipt_for_id, r.receipt_number, r.bank_id, r.receipt_status, 
        rf.receipt_for, b.bank_name, pm.payment_mode,
        SUM(CASE WHEN rt.total_amount IS NOT NULL THEN rt.total_amount END) as total_amount,
        GROUP_CONCAT(rt.fees_id) as fees_ids 
    FROM receipt r 
    LEFT JOIN receipt_for_master rf ON r.receipt_for_id = rf.receipt_for_id 
    LEFT JOIN bank_master b ON r.bank_id = b.bank_id 
    LEFT JOIN payment_mode pm ON r.payment_mode_id = pm.payment_mode_id 
    LEFT JOIN (
        SELECT rt.receipt_number, rt.fees_id, rt.total_amount
        FROM receipt_temp rt
        LEFT JOIN fees_master fm ON rt.fees_id = fm.fees_id
    ) rt ON r.receipt_number = rt.receipt_number 
    WHERE r.practitioner_id = ? AND r.receipt_status != 'Deleted' 
    GROUP BY r.receipt_id 
    ORDER BY r.receipt_id DESC 
    LIMIT 15");
    
    $stmt->bind_param("i", $id);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // echo "Receipts found: " . $result->num_rows . "<br>";
        while ($row = $result->fetch_assoc()) {
            // echo "Processing receipt: " . $row['receipt_number'] . "<br>";
            $param = urlencode(base64_encode($row['receipt_number']));
            $practitioner_id = urlencode(base64_encode($row['practitioner_id']));
            $source_id = urlencode(base64_encode($practitioner_id));
            $total_amount = number_format($row['total_amount'], 2);
            $fees_ids = explode(',', $row['fees_ids']);
            $certificates = [];

            $response .= "<form class='row mb-5 needs-validation p-4' style='background-color: #f5f5f5;' method='POST' enctype='multipart/form-data' novalidate>
                <div class='col-xl-4 mb-3'>
                    <label class='form-label'>Receipt Number</label>
                    <input class='form-control' readonly value='{$row['receipt_number']}' />
                </div>
                <div class='col-xl-4 mb-3'>
                    <label class='form-label'>Payment For</label>
                    <input class='form-control' readonly value='{$row['receipt_for']}' />
                </div>
                <div class='col-xl-4 mb-3'>
                    <label class='form-label'>Bank</label>
                    <select class='form-control' name='payment_bank'>
                        <option value=''>Choose</option>";
            $bank_stmt = $conn->prepare("SELECT bank_name, bank_id FROM bank_master WHERE bank_status = 'Active' ORDER BY FIELD(bank_name, 'UPI', 'Card Payment') DESC, bank_name ASC");
            $bank_stmt->execute();
            $bank_result = $bank_stmt->get_result();
            while ($bank_row = $bank_result->fetch_assoc()) {
                $selected = $bank_row['bank_id'] == $row['bank_id'] ? 'selected' : '';
                $response .= "<option value='{$bank_row['bank_id']}' $selected>{$bank_row['bank_name']}</option>";
            }
            $response .= "</select>
                </div>
                <div class='col-xl-4 mb-3'>
                    <label class='form-label'>Mode</label>
                    <select class='form-control' name='payment_through'>
                        <option value=''>Choose</option>";
            $mode_stmt = $conn->prepare("SELECT payment_mode, payment_mode_id FROM payment_mode WHERE payment_mode_status = 'Active'");
            $mode_stmt->execute();
            $mode_result = $mode_stmt->get_result();
            while ($mode_row = $mode_result->fetch_assoc()) {
                $selected = $mode_row['payment_mode_id'] == $row['payment_mode_id'] ? 'selected' : '';
                $response .= "<option value='{$mode_row['payment_mode_id']}' $selected>{$mode_row['payment_mode']}</option>";
            }
            $response .= "</select>
                </div>
                <div class='col-xl-4 mb-3'>
                    <label class='form-label'>DD Number/Reference No</label>
                    <input class='form-control' value='{$row['receipt_reference_number']}' name='reference_number' />
                </div>
                <div class='col-xl-4 mb-3'>
                    <label class='form-label'>DD Date/Payment Date</label>
                    <input class='form-control' name='dd_date' type='date' max='" . date('Y-m-d') . "' value='{$row['dd_date']}' />
                </div>
                <div class='col-xl-4 mb-3'>
                    <label class='form-label'>Receipt Date</label>
                    <input class='form-control' name='receipt_date' type='date' max='" . date('Y-m-d') . "' value='{$row['receipt_date']}' />
                </div>
                <div class='col-xl-4 mb-3'>
                    <label class='form-label'>Amount</label>
                    <input class='form-control' readonly value='";

            $total_amount = 0;
            $stmt_total_amount = $conn->prepare("SELECT SUM(rt.total_amount) AS total_amount 
                FROM receipt_temp rt 
                LEFT JOIN fees_master fm ON rt.fees_id = fm.fees_id
                WHERE rt.receipt_number = ? AND rt.fees_id != 19
                AND ( 
                (LENGTH(rt.PractitionerID) > 10 OR rt.PractitionerID IS NULL)  -- Adjust threshold as needed
                OR rt.PractitionerID = ?
                )");
            $stmt_total_amount->bind_param("si", $row['receipt_number'], $practitioner_id_temp);
            $stmt_total_amount->execute();
            $stmt_total_amount->bind_result($total_amount);
            $stmt_total_amount->fetch();
            $stmt_total_amount->close();

            $response .= number_format($total_amount, 2);
            $response .= "'/>
                </div>
                <div class='col-xl-4 mb-3'>
                    <label class='form-label'>Status<span class='text-danger'>*</span></label>
                    <select class='default-select form-control' required name='payment_status'>
                        <option value=''>Choose</option>
                        <option value='Active'" . ($row['receipt_status'] == 'Active' ? 'selected' : '') . ">Active</option>
                        <option value='In-Active'" . ($row['receipt_status'] == 'In-Active' ? 'selected' : '') . ">In-Active</option>
                    </select>
                </div>
                <input autocomplete='off' type='hidden' name='payment_id' value='{$row['receipt_id']}' />
                <div class='col-xl-9'>
                    <a class='text-primary' href='view-receipt.php?source=$param&practitioner=$practitioner_id' target='_blank'></a>";
            $certificates[] = "<a class='text-primary' href='view-receipt.php?source=$param";
            if (!empty($practitioner_id)) { 
                $certificates[] .= "&practitioner=$practitioner_id"; 
            }
            $certificates[] .= "' target='_blank'>View Receipt</a>";

            if (in_array(10, $fees_ids)) {
                $certificates[] = "<a class='text-primary' href='goodstanding-certificate.php?source=$param";
                if (!empty($practitioner_id)) { 
                    $certificates[] .= "&practitioner=$practitioner_id"; 
                }
                $certificates[] .= "' target='_blank'>Good Standing Certificate</a>";
            }
            if (in_array(16, $fees_ids)) {
                $certificates[] = "<a class='text-primary' href='smart-card-certificate.php?source=$param";
                if (!empty($practitioner_id)) { 
                    $certificates[] .= "&practitioner=$practitioner_id"; 
                }
                $certificates[] .= "' target='_blank'>Smart Card Certificate</a>";
            }
            if (array_intersect([1, 2, 3, 20, 4], $fees_ids)) {
                $cert_type = '';
                switch ($type) {
                    case 1:
                        $cert_type = 'A';
                        break;
                    case 3:
                        $cert_type = 'DH';
                        break;
                    case 4:
                        $cert_type = 'DM';
                        break;
                    case 6:
                        $cert_type = 'P';
                        break;
                    case 5:
                        $cert_type = 'DORA';
                        break;
                }
                $certificates[] = "<a class='text-primary' href='registration-certificate-$cert_type.php?source=$param";
                if (!empty($practitioner_id)) { 
                    $certificates[] .= "&practitioner=$practitioner_id"; 
                }
                $certificates[] .= "' target='_blank'>Registration Certificate</a>";
            }

            if (array_intersect([8, 7], $fees_ids)) {
                $certificates[] = "<a class='text-primary' href='renewal-certificate.php?source=$param";
                if (!empty($practitioner_id)) { 
                    $certificates[] .= "&practitioner=$practitioner_id"; 
                }
                $certificates[] .= "' target='_blank'>Renewal Certificate</a>";
            }

            if (in_array(9, $fees_ids)) {
                $certificates[] = "<a class='text-primary' href='additional-certificate-A.php?source=$param";
                if (!empty($practitioner_id)) { 
                    $certificates[] .= "&practitioner=$practitioner_id"; 
                }
                $certificates[] .= "' target='_blank'>Additional Qualification Certificate</a>";
            }
            if (in_array(11, $fees_ids)) {
                $cert_type = '';
                switch ($type) {
                    case 1:
                        $cert_type = 'A';
                        break;
                    case 3:
                        $cert_type = 'DH';
                        break;
                    case 4:
                        $cert_type = 'DM';
                        break;
                    case 5:
                        $cert_type = 'DORA';
                        break;
                }
                $certificates[] = "<a class='text-primary' href='change-of-name-$cert_type.php?source=$param";
                if (!empty($practitioner_id)) { 
                    $certificates[] .= "&practitioner=$practitioner_id"; 
                }
                $certificates[] .= "' target='_blank'>Change of Name Certificate</a>";
            }
            if (in_array(12, $fees_ids)) {
                $cert_type = '';
                switch ($type) {
                    case 1:
                        $cert_type = 'A';
                        break;
                    case 3:
                        $cert_type = 'DH';
                        break;
                    case 4:
                        $cert_type = 'DM';
                        break;
                    case 5:
                        $cert_type = 'DORA';
                        break;
                }
                $certificates[] = "<a class='text-primary' href='duplicate-certificate-$cert_type.php?source=$param";
                if (!empty($practitioner_id)) { 
                    $certificates[] .= "&practitioner=$practitioner_id"; 
                }
                $certificates[] .= "' target='_blank'>Duplicate Certificate</a>";
            }
            if (in_array(17, $fees_ids)) {
                $cert_type = '';
                if ($row['receipt_for_id'] == 14) {
                    switch ($type) {
                        case 1:
                            $cert_type = 'A';
                            break;
                        case 3:
                            $cert_type = 'DH';
                            break;
                        case 4:
                            $cert_type = 'DM';
                            break;
                        case 5:
                            $cert_type = 'DORA';
                            break;
                    }
                    $certificates[] = "<a class='text-primary' href='noc-certificate-$cert_type.php?source=$param";
                    if (!empty($practitioner_id)) { 
                        $certificates[] .= "&practitioner=$practitioner_id"; 
                    }
                    $certificates[] .= "' target='_blank'>NOC Certificate</a>";
                } elseif ($row['receipt_for_id'] == 15) {
                    $certificates[] = "<a class='text-primary' href='noc-certificate-fresh.php?source=$param";
                    if (!empty($practitioner_id)) { 
                        $certificates[] .= "&practitioner=$practitioner_id"; 
                    }
                    $certificates[] .= "' target='_blank'>NOC Certificate</a>";
                }
            }

            $response .= implode(" | ", $certificates);

            $response .= "</div>
                <div class='col-xl-3 text-end'>
                    <button type='submit' name='edit_payment' class='btn btn-primary btn-sm me-2'>Update</button>";
            if ($_SESSION['user_role'] == 'Admin') {
                $response .= "<a href='edit-receipt.php?source=$practitioner_id&param=$param' class='btn btn-outline-danger btn-sm'>Edit For</a>";
            }
            $response .= "</div>
            </form>";
        }
    } else {
        echo "No receipts found<br>";
    }
    $stmt->close();
} else {
    echo "Invalid ID<br>";
}

echo $response;

mysqli_close($conn);
?>
