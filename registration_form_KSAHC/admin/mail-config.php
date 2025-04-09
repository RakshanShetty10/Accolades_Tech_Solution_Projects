<?php
/**
 * PHPMailer Configuration 
 * This file contains shared email settings used across the application
 */

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require_once __DIR__ . '/../phpmailer/Exception.php';
require_once __DIR__ . '/../phpmailer/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/SMTP.php';

// PHPMailer settings
define('MAIL_HOST', 'smtp.gmail.com');  // SMTP server
define('MAIL_USERNAME', 'anonymous10unknown10@gmail.com');   // SMTP username
define('MAIL_PASSWORD', 'kdxx fkix stuj nrue');     // SMTP password
define('MAIL_PORT', 587);                           // TCP port to connect to
define('MAIL_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS);   // TLS encryption
define('MAIL_FROM_ADDRESS', 'anonymous10unknown10@gmail.com');    // From email address
define('MAIL_FROM_NAME', 'Karnataka State Allied & Healthcare Council'); // From name

/**
 * Function to get a configured PHPMailer instance
 * 
 * @return PHPMailer\PHPMailer\PHPMailer
 */
function getConfiguredMailer() {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);
    
    // Server settings
    $mail->SMTPDebug = 0;                         // Debug level (0 = no output, 2 = verbose)
    $mail->isSMTP();                              // Send using SMTP
    $mail->Host       = MAIL_HOST;                // SMTP server
    $mail->SMTPAuth   = true;                     // Enable SMTP authentication
    $mail->Username   = MAIL_USERNAME;            // SMTP username
    $mail->Password   = MAIL_PASSWORD;            // SMTP password
    $mail->SMTPSecure = MAIL_ENCRYPTION;          // Enable TLS encryption
    $mail->Port       = MAIL_PORT;                // TCP port to connect to
    
    // Default sender
    $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
    $mail->addReplyTo(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
    
    // Set email format to HTML by default
    $mail->isHTML(true);
    
    return $mail;
} 