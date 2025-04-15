<?php


require_once 'config/connection.php';
require_once 'config/utils.php';
// Mail helper functions for password reset

// Use PHPMailer for reliable email delivery
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer autoloader (make sure you have installed PHPMailer via composer)
require 'vendor-email/autoload.php';

/**
 * Sends a password reset email with the recovery link
 * 
 * @param string $name The recipient's name
 * @param string $email The recipient's email address
 * @param string $reset_link The password reset link
 * @return bool Whether the email was sent successfully
 */
function sendResetEmail($name, $email, $reset_link) {
    global $site_full, $site_url;
    
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    try {
        // Server settings - MODIFY THESE WITH YOUR ACTUAL EMAIL SERVER DETAILS
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';        // Gmail SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'anonymous10unknown10@gmail.com';  // SMTP username
        $mail->Password   = 'kdxx fkix stuj nrue';     // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587;                      // TCP port to connect to
        
        // Recipients
        $mail->setFrom('noreply@yourdomain.com', $site_full);
        $mail->addAddress($email, $name);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request - ' . $site_full;
        
        // HTML email body with professional styling
        $htmlBody = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e5e9f2; border-radius: 8px; background-color: #fff;">
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="' . $site_url . 'assets/images/ksahc_logo.png" alt="Logo" style="max-height: 80px;">
            </div>
            
            <div style="padding: 20px; background-color: #f5f6fa; border-radius: 8px; margin-bottom: 20px;">
                <h2 style="color: #192a56; text-align: center; margin-top: 0;">Password Reset Request</h2>
                <p style="color: #333; font-size: 16px;">Dear ' . $name . ',</p>
                <p style="color: #333; font-size: 16px;">We received a request to reset your password. Please click the button below to create a new password:</p>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . $reset_link . '" style="display: inline-block; padding: 12px 24px; background-color: #192a56; color: #fff; text-decoration: none; font-weight: bold; border-radius: 4px; font-size: 16px;">Reset Your Password</a>
            </div>
            
            <div style="margin-top: 20px; color: #555; font-size: 14px;">
                <p>If the button above doesn\'t work, copy and paste the following link into your browser:</p>
                <p style="word-break: break-all; background-color: #f5f6fa; padding: 10px; border-radius: 4px; font-size: 13px;">' . $reset_link . '</p>
            </div>
            
            <div style="margin-top: 30px; color: #555; font-size: 14px;">
                <p><strong>Important:</strong> This link will expire in 10 minutes for security reasons.</p>
                <p>If you did not request a password reset, please ignore this email or contact support if you have concerns.</p>
            </div>
            
            <hr style="border: none; border-top: 1px solid #e5e9f2; margin: 30px 0;">
            
            <div style="color: #777; font-size: 12px; text-align: center;">
                <p>This is an automated email. Please do not reply to this message.</p>
                <p>&copy; ' . date('Y') . ' ' . $site_full . '. All rights reserved.</p>
            </div>
        </div>';
        
        // Plain text version for email clients that don't support HTML
        $textBody = "Dear $name,\n\n"
            . "We received a request to reset your password.\n\n"
            . "Please visit the link below to create a new password:\n"
            . "$reset_link\n\n"
            . "This link will expire in 10 minutes for security reasons.\n\n"
            . "If you did not request a password reset, please ignore this email or contact support if you have concerns.\n\n"
            . "Regards,\n"
            . "$site_full Team";
        
        $mail->Body    = $htmlBody;
        $mail->AltBody = $textBody;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log the error for debugging
        error_log("Mail error: " . $mail->ErrorInfo);
        return false;
    }
}