<?php
// Start session
session_start();

// Database connection
require_once '../../config/config.php';

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../phpmailer/Exception.php';
require '../../phpmailer/PHPMailer.php';
require '../../phpmailer/SMTP.php';

// Process remark submission
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Basic validation
    if(empty($email) || empty($message)) {
        echo 'Email and message are required';
        exit;
    }
    
    try {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);
        
        // Server settings
        $mail->SMTPDebug = 0; // Set to 0 for production, 2 for debugging
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Specify SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'anonymous10unknown10@gmail.com'; // Your SMTP username
        $mail->Password   = 'kdxx fkix stuj nrue'; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        // Recipients
        $mail->setFrom('anonymous10unknown10@gmail.com', 'KSAHC Admin');
        $mail->addAddress($email, $name);
        $mail->addReplyTo('anonymous10unknown10@gmail.com', 'KSAHC Admin');
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Important Notification from KSAHC';
        
        // Email template with enhanced styling
        $htmlBody = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background-color: #f9f9f9;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 0;
                    background-color: #ffffff;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                .header {
                    background-color: #4e73df;
                    color: white;
                    padding: 25px;
                    text-align: center;
                    border-radius: 5px 5px 0 0;
                }
                .header h2 {
                    margin: 0;
                    font-size: 24px;
                }
                .header img {
                    max-width: 150px;
                    margin-bottom: 10px;
                }
                .content {
                    padding: 30px;
                    background-color: #ffffff;
                    border-radius: 0 0 5px 5px;
                }
                .greeting {
                    font-size: 18px;
                    font-weight: bold;
                    margin-bottom: 15px;
                }
                .message-box {
                    background-color: #f8f9fc;
                    border-left: 5px solid #4e73df;
                    padding: 20px;
                    margin: 20px 0;
                    border-radius: 0 5px 5px 0;
                }
                .message-title {
                    font-weight: bold;
                    color: #4e73df;
                    margin-bottom: 10px;
                    font-size: 16px;
                }
                .footer {
                    background-color: #f1f3f9;
                    padding: 15px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                    border-top: 1px solid #e3e6f0;
                }
                .button {
                    display: inline-block;
                    background-color: #4e73df;
                    color: white;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 5px;
                    margin-top: 15px;
                    font-weight: bold;
                }
                .button:hover {
                    background-color: #375bd1;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Karnataka State Allied & Healthcare Council</h2>
                </div>
                <div class="content">
                    <div class="greeting">Dear ' . htmlspecialchars($name) . ',</div>
                    <p>We are writing to inform you regarding your application to the Karnataka State Allied & Healthcare Council.</p>
                    
                    <div class="message-box">
                        <div class="message-title">Remark from Administrator:</div>
                        <p>' . nl2br(htmlspecialchars($message)) . '</p>
                    </div>
                    
                    <p>Please address the above points as soon as possible. If you have any questions, please contact us.</p>
                    <p>Thank you for your cooperation.</p>
                    <p>Best regards,<br><strong>KSAHC Administration</strong></p>
                    
                    <a href="https://ksahc.karnataka.gov.in" class="button">Visit KSAHC Website</a>
                </div>
                <div class="footer">
                    <p>This is an automated message. Please do not reply to this email.</p>
                    <p>&copy; ' . date('Y') . ' Karnataka State Allied & Healthcare Council. All Rights Reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ';
        
        $mail->Body = $htmlBody;
        $mail->AltBody = strip_tags(str_replace('<br>', "\n", $message));
        
        // Send email without any database operations
        if($mail->send()) {
            echo 'Email sent successfully';
        } else {
            echo 'Error: ' . $mail->ErrorInfo;
        }
        
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
    
    exit;
}

// If accessed directly without POST data
echo 'Invalid request';
?> 