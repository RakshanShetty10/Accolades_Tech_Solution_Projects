<?php
/**
 * Email Templates and Functions for KSAHC
 * This file contains email template functionality used across the application
 */

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require_once __DIR__ . '/../phpmailer/Exception.php';
require_once __DIR__ . '/../phpmailer/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/SMTP.php';

/**
 * Sends an approval notification email to a practitioner
 * 
 * @param string $recipient_email The practitioner's email address
 * @param string $recipient_name The practitioner's name
 * @param int $practitioner_id The practitioner's ID for logging purposes
 * @return boolean True if the email was sent successfully, false otherwise
 */
function sendApprovalEmail($recipient_email, $recipient_name, $practitioner_id) {
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
        $mail->addAddress($recipient_email, $recipient_name);
        $mail->addReplyTo('anonymous10unknown10@gmail.com', 'KSAHC Admin');
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your KSAHC Registration Status Update';
        
        // Email template with enhanced styling
        $htmlBody = getApprovalEmailTemplate($recipient_name);
        
        $mail->Body = $htmlBody;
        $mail->AltBody = 'Dear ' . $recipient_name . ', Your application to the Karnataka State Allied & Healthcare Council has been approved. You will receive your registration number soon.';
        
        if($mail->send()) {
            return true;
        } else {
            error_log("Failed to send approval email to practitioner ID {$practitioner_id}: " . $mail->ErrorInfo);
            return false;
        }
    } catch (Exception $e) {
        error_log("Exception while sending approval email to practitioner ID {$practitioner_id}: " . $e->getMessage());
        return false;
    }
}

/**
 * Returns the HTML template for an approval notification email
 * 
 * @param string $recipient_name The practitioner's name
 * @return string The HTML email template
 */
function getApprovalEmailTemplate($recipient_name) {
    return '
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
                border-left: 5px solid #1cc88a;
                padding: 20px;
                margin: 20px 0;
                border-radius: 0 5px 5px 0;
            }
            .message-title {
                font-weight: bold;
                color: #1cc88a;
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
                <div class="greeting">Dear ' . htmlspecialchars($recipient_name) . ',</div>
                <p>We are pleased to inform you that your application to the Karnataka State Allied & Healthcare Council has been <strong>Approved</strong>.</p>
                
                <div class="message-box">
                    <div class="message-title">Application Status Update:</div>
                    <p>Your registration status has been updated to <strong>Approved</strong>.</p>
                    <p>Your application is now in the final stage of processing. The next step will be the generation of your registration number. You will receive another notification once that process is complete.</p>
                </div>
                
                <p>Please note that you will receive your official registration number and login credentials in a separate email once the central administration completes the final verification.</p>
                <p>Thank you for your patience during this process.</p>
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
} 