<?php
// Start session to access session variables
session_start();

// Include database connection and utilities
require_once '../../config/connection.php';
require_once '../../config/utils.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the POST request
    $practitioner_id = isset($_POST['practitioner_id']) ? intval($_POST['practitioner_id']) : 0;
    $remark = isset($_POST['remark']) ? trim($_POST['remark']) : '';
    $admin_username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Admin';
    
    // Validate data
    if ($practitioner_id <= 0 || empty($remark)) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Invalid data provided']);
        exit;
    }
    
    // Get practitioner information
    $stmt = $conn->prepare("SELECT practitioner_name, practitioner_email_id FROM practitioner WHERE practitioner_id = ?");
    $stmt->bind_param("i", $practitioner_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404); // Not Found
        echo json_encode(['success' => false, 'message' => 'Practitioner not found']);
        exit;
    }
    
    $practitioner = $result->fetch_assoc();
    $practitioner_name = $practitioner['practitioner_name'];
    $practitioner_email = $practitioner['practitioner_email_id'];
    
    // Insert remark into database (you may need to create this table)
    $current_time = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO practitioner_remarks (practitioner_id, remark, created_by, created_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $practitioner_id, $remark, $admin_username, $current_time);
    $success = $stmt->execute();
    
    if (!$success) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'Failed to save remark: ' . $conn->error]);
        exit;
    }
    
    // Send email with the remark
    $subject = "Important Remark About Your Application";
    $message = "
    <html>
    <head>
        <title>Remark from Administrator</title>
    </head>
    <body>
        <p>Dear $practitioner_name,</p>
        <p>An administrator has provided the following remark regarding your application:</p>
        <div style='padding: 15px; border-left: 4px solid #ccc; margin: 20px 0;'>
            $remark
        </div>
        <p>Please review this information and take necessary action.</p>
        <p>If you have any questions, please contact our support team.</p>
        <p>Regards,<br>Administration Team</p>
    </body>
    </html>
    ";
    
    // Set headers for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: admin@example.com" . "\r\n";
    
    // Send the email
    $mail_sent = mail($practitioner_email, $subject, $message, $headers);
    
    // Return response
    if ($mail_sent) {
        echo json_encode(['success' => true, 'message' => 'Remark sent successfully']);
    } else {
        // The remark was saved in the database, but the email wasn't sent
        echo json_encode(['success' => true, 'message' => 'Remark saved but email could not be sent']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 