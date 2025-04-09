<?php
// Start session
session_start();

// Database connection
require_once 'config/config.php';

// Check if user is in password reset mode or logged in
if(!isset($_SESSION['reset_user_id']) && !isset($_SESSION['user_id'])) {
    // If not in reset mode and not logged in, redirect to login page
    header("Location: login.php");
    exit;
}

// Initialize variables
$error = '';
$success = '';
$user_id = isset($_SESSION['reset_user_id']) ? $_SESSION['reset_user_id'] : $_SESSION['user_id'];
$username = isset($_SESSION['reset_username']) ? $_SESSION['reset_username'] : $_SESSION['username'];
$name = isset($_SESSION['reset_name']) ? $_SESSION['reset_name'] : $_SESSION['user_name'];
$is_first_login = isset($_SESSION['is_first_login']) ? $_SESSION['is_first_login'] : false;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get passwords
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate input
    if (empty($old_password) && !$is_first_login) {
        $error = "Please enter your current password.";
    } elseif (empty($new_password)) {
        $error = "Please enter a new password.";
    } elseif (strlen($new_password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New passwords do not match.";
    } else {
        // For first login, no need to verify old password
        if ($is_first_login) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update password and set first_login to 'No'
            $update_sql = "UPDATE practitioner SET practitioner_password = ?, is_first_login = 'No' WHERE practitioner_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("si", $hashed_password, $user_id);
            
            if ($update_stmt->execute()) {
                // Set success message
                $success = "Password set successfully!";
                
                // Set regular session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['user_name'] = $name;
                
                // Clear reset session variables
                unset($_SESSION['reset_user_id']);
                unset($_SESSION['reset_username']);
                unset($_SESSION['reset_name']);
                unset($_SESSION['is_first_login']);
                
                // Redirect to profile page after short delay for success message
                header("Refresh: 2; URL=profile.php");
            } else {
                $error = "Error updating password: " . $conn->error;
            }
        } else {
            // For regular password reset, verify old password first
            $check_sql = "SELECT practitioner_password FROM practitioner WHERE practitioner_id = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("i", $user_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                if (password_verify($old_password, $user['practitioner_password'])) {
                    // Hash the new password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    
                    // Update password
                    $update_sql = "UPDATE practitioner SET practitioner_password = ? WHERE practitioner_id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("si", $hashed_password, $user_id);
                    
                    if ($update_stmt->execute()) {
                        // Set success message
                        $success = "Password updated successfully!";
                        
                        // Clear reset session variables if they exist
                        if (isset($_SESSION['reset_user_id'])) {
                            unset($_SESSION['reset_user_id']);
                            unset($_SESSION['reset_username']);
                            unset($_SESSION['reset_name']);
                        }
                        
                        // Redirect to profile page after short delay for success message
                        header("Refresh: 2; URL=profile.php");
                    } else {
                        $error = "Error updating password: " . $conn->error;
                    }
                } else {
                    $error = "Current password is incorrect.";
                }
            } else {
                $error = "User not found.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_first_login ? 'Set Password' : 'Reset Password'; ?> | KSAHC Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --dark-color: #3a3b45;
            --light-color: #f8f9fc;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .reset-container {
            max-width: 550px;
            width: 100%;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(50, 50, 93, 0.15), 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background: var(--primary-color);
            color: white;
            text-align: center;
            padding: 25px 15px;
            border-bottom: none;
        }
        
        .logo-img {
            max-width: 80px;
            margin-bottom: 15px;
        }
        
        .card-body {
            padding: 40px 30px;
            background: white;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 20px;
            height: auto;
            font-size: 16px;
            border: 1px solid #ced4da;
            background-color: #f8f9fc;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
            background-color: white;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #3a5ccc;
            border-color: #3a5ccc;
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
        }
        
        .input-group-text {
            border-radius: 10px 0 0 10px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .form-group label {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 8px;
        }
        
        .alert {
            border-radius: 10px;
            font-size: 14px;
        }
        
        .password-hint {
            font-size: 13px;
            color: #6c757d;
            margin-top: 8px;
        }
        
        .password-criteria {
            margin-top: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fc;
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
        }
        
        .password-criteria h5 {
            font-size: 15px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .password-criteria ul {
            margin-bottom: 0;
            padding-left: 20px;
        }
        
        .password-criteria ul li {
            margin-bottom: 5px;
            font-size: 13px;
            color: #6c757d;
        }
        
        .welcome-message {
            background-color: #e8f4ff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid var(--primary-color);
        }
        
        .welcome-message h5 {
            color: var(--primary-color);
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        /* Strength meter */
        .password-strength-meter {
            height: 5px;
            background-color: #eee;
            border-radius: 3px;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }
        
        .password-strength-meter-fill {
            height: 100%;
            border-radius: 3px;
            width: 0;
            transition: width 0.3s, background-color 0.3s;
        }
        
        .password-strength-text {
            font-size: 12px;
            margin-top: 5px;
            font-weight: 500;
            text-align: right;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #6c757d;
        }
        
        /* Animation for card */
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        
        .card {
            animation: fadeIn 0.8s ease forwards;
        }
        
        /* Password show/hide toggle */
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            cursor: pointer;
            z-index: 10;
            transition: color 0.3s;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
        
        .password-field-container {
            position: relative;
        }
        
        /* Success animation */
        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .success-icon {
            animation: successPulse 1s infinite;
            display: inline-block;
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="card">
            <div class="card-header">
                <img src="ksahc_logo.png" alt="KSAHC Logo" class="logo-img">
                <h3>Karnataka State Allied & Healthcare Council</h3>
                <p class="mb-0"><?php echo $is_first_login ? 'Set Your Password' : 'Reset Your Password'; ?></p>
            </div>
            <div class="card-body">
                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <?php if(!empty($success)): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle mr-2 success-icon"></i><?php echo $success; ?>
                        <p class="mb-0 mt-2">Redirecting to your profile page...</p>
                    </div>
                <?php else: ?>
                    <div class="welcome-message">
                        <h5><i class="fas fa-user-shield mr-2"></i>Welcome, <?php echo htmlspecialchars($name); ?>!</h5>
                        <p class="mb-0"><?php echo $is_first_login ? 'This is your first login. Please create a new password to secure your account.' : 'Please enter your current password and set a new password.'; ?></p>
                    </div>
                    
                    <form method="POST" action="reset_password.php">
                        <?php if(!$is_first_login): ?>
                            <div class="form-group">
                                <label for="old_password"><i class="fas fa-lock mr-2"></i>Current Password</label>
                                <div class="password-field-container">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter your current password" required>
                                    </div>
                                    <span class="password-toggle" onclick="togglePasswordVisibility('old_password', 'toggleIcon0')">
                                        <i class="fas fa-eye" id="toggleIcon0"></i>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="password-criteria">
                            <h5><i class="fas fa-shield-alt mr-2"></i>Password Requirements</h5>
                            <ul>
                                <li>At least 8 characters long</li>
                                <li>Should include uppercase and lowercase letters</li>
                                <li>Include at least one number</li>
                                <li>Include at least one special character</li>
                            </ul>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password"><i class="fas fa-lock mr-2"></i>New Password</label>
                            <div class="password-field-container">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter your new password" required>
                                </div>
                                <span class="password-toggle" onclick="togglePasswordVisibility('new_password', 'toggleIcon1')">
                                    <i class="fas fa-eye" id="toggleIcon1"></i>
                                </span>
                            </div>
                            <div class="password-strength-meter mt-2">
                                <div class="password-strength-meter-fill" id="strengthMeter"></div>
                            </div>
                            <div class="password-strength-text" id="strengthText">Password strength</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password"><i class="fas fa-lock mr-2"></i>Confirm New Password</label>
                            <div class="password-field-container">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                                    </div>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your new password" required>
                                </div>
                                <span class="password-toggle" onclick="togglePasswordVisibility('confirm_password', 'toggleIcon2')">
                                    <i class="fas fa-eye" id="toggleIcon2"></i>
                                </span>
                            </div>
                            <div class="password-hint" id="passwordMatch"></div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" id="submitBtn" class="btn btn-primary btn-block">
                                <i class="fas fa-save mr-2"></i><?php echo $is_first_login ? 'Set Password' : 'Update Password'; ?>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-text mt-3">
            <p>&copy; <?php echo date('Y'); ?> Karnataka State Allied & Healthcare Council. All rights reserved.</p>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        function togglePasswordVisibility(fieldId, iconId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(iconId);
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Password strength checker
        document.getElementById('new_password').addEventListener('input', function() {
            const password = this.value;
            const strengthMeter = document.getElementById('strengthMeter');
            const strengthText = document.getElementById('strengthText');
            
            // Check password strength
            let strength = 0;
            
            // Length check
            if (password.length >= 8) {
                strength += 25;
            }
            
            // Uppercase check
            if (/[A-Z]/.test(password)) {
                strength += 25;
            }
            
            // Lowercase check
            if (/[a-z]/.test(password)) {
                strength += 25;
            }
            
            // Number check
            if (/[0-9]/.test(password)) {
                strength += 12.5;
            }
            
            // Special character check
            if (/[^A-Za-z0-9]/.test(password)) {
                strength += 12.5;
            }
            
            // Update the strength meter
            strengthMeter.style.width = strength + '%';
            
            // Update color and text based on strength
            if (strength < 25) {
                strengthMeter.style.backgroundColor = '#e74a3b';
                strengthText.textContent = 'Very Weak';
                strengthText.style.color = '#e74a3b';
            } else if (strength < 50) {
                strengthMeter.style.backgroundColor = '#f6c23e';
                strengthText.textContent = 'Weak';
                strengthText.style.color = '#f6c23e';
            } else if (strength < 75) {
                strengthMeter.style.backgroundColor = '#4e73df';
                strengthText.textContent = 'Medium';
                strengthText.style.color = '#4e73df';
            } else {
                strengthMeter.style.backgroundColor = '#1cc88a';
                strengthText.textContent = 'Strong';
                strengthText.style.color = '#1cc88a';
            }
        });
        
        // Check if passwords match
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            const passwordMatch = document.getElementById('passwordMatch');
            const submitBtn = document.getElementById('submitBtn');
            
            if (confirmPassword === '') {
                passwordMatch.textContent = '';
            } else if (password === confirmPassword) {
                passwordMatch.textContent = 'Passwords match ✓';
                passwordMatch.style.color = '#1cc88a';
                submitBtn.disabled = false;
            } else {
                passwordMatch.textContent = 'Passwords do not match ✗';
                passwordMatch.style.color = '#e74a3b';
                submitBtn.disabled = true;
            }
        });
    </script>
</body>
</html> 