<?php
require_once "../../config/connection.php";

// Step 1: Fetch all required data in a single query using JOIN
$res = mysqli_query($conn, "
    SELECT e.education_id, p.practitioner_id 
    FROM education_information e
    LEFT JOIN practitioner p ON e.practitioner_code_ksdc = p.PractitionerID
    ORDER BY e.education_id ASC
    LIMIT 10000 OFFSET 22254
");

$updates = [];
if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        if (!is_null($row['practitioner_id'])) {
            // Prepare an array of update statements
            $updates[] = "UPDATE education_information SET practitioner_id = '" . $row['practitioner_id'] . "' WHERE education_id = '" . $row['education_id'] . "'";
        }
    }
}

// Step 2: Execute the updates in batches to reduce the number of queries
if (!empty($updates)) {
    $batch_size = 100; // Adjust batch size according to your server capability
    for ($i = 0; $i < count($updates); $i += $batch_size) {
        $batch_queries = array_slice($updates, $i, $batch_size);
        $batch_query = implode(";", $batch_queries);
        mysqli_multi_query($conn, $batch_query);
        // Ensure all queries in the batch are executed before moving to the next batch
        while (mysqli_more_results($conn) && mysqli_next_result($conn)) {;}
    }
}

mysqli_close($conn);
?>