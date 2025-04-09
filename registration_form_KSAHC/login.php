<?php
// Start session
session_start();

// Database connection
require_once 'config/config.php';

// Initialize variables
$error = '';
$username = '';
$first_login = false;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if it's a login or password reset submission
    if (isset($_POST['login'])) {
        // Login form was submitted
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        if (empty($username)) {
            $error = "Please enter your registration number.";
        } else {
            // Check if user exists and get first_login status
            $check_user_sql = "SELECT practitioner_id, practitioner_username, practitioner_password, is_first_login, practitioner_name 
                               FROM practitioner 
                               WHERE practitioner_username = ? AND registration_status = 'Active'";
            $check_stmt = $conn->prepare($check_user_sql);
            $check_stmt->bind_param("s", $username);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                
                // Check if it's first login
                if ($user['is_first_login'] === 'Yes') {
                    // For first login, password should be the birth date
                    if (password_verify($password, $user['practitioner_password'])) {
                        // Set session variables for password reset
                        $_SESSION['reset_user_id'] = $user['practitioner_id'];
                        $_SESSION['reset_username'] = $user['practitioner_username'];
                        $_SESSION['reset_name'] = $user['practitioner_name'];
                        $_SESSION['is_first_login'] = true;
                        
                        // Redirect to password reset page
                        header("Location: reset_password.php");
                        exit;
                    } else {
                        $error = "Incorrect date of birth. Please enter in YYYY-MM-DD format.";
                        $first_login = true;
                    }
                } else {
                    // Regular login with password
                    if (password_verify($password, $user['practitioner_password'])) {
                        // Set session variables
                        $_SESSION['user_id'] = $user['practitioner_id'];
                        $_SESSION['username'] = $user['practitioner_username'];
                        $_SESSION['user_name'] = $user['practitioner_name'];
                        
                        // Redirect to profile page
                        header("Location: welcome.php");
                        exit;
                    } else {
                        $error = "Incorrect password. Please try again.";
                    }
                }
            } else {
                $error = "Invalid registration number. Please check and try again.";
            }
        }
    }
}

// If username is provided but form not submitted yet, check if it's first login
if (!empty($_GET['username']) && empty($error)) {
    $username = trim($_GET['username']);
    
    // Check if user exists and if it's their first login
    $check_sql = "SELECT is_first_login FROM practitioner WHERE practitioner_username = ? AND registration_status = 'Active'";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $user_data = $check_result->fetch_assoc();
        if ($user_data['is_first_login'] === 'Yes') {
            $first_login = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | KSAHC Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        
        .login-container {
            max-width: 500px;
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
        
        .btn-primary:active {
            transform: translateY(1px);
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
        
        .info-text {
            font-size: 14px;
            color: #6c757d;
            margin-top: 25px;
            text-align: center;
        }
        
        .info-box {
            background-color: #e8f4ff;
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .info-box i {
            color: var(--primary-color);
        }
        
        .date-picker {
            background-color: white;
        }
        
        .forgotten-pwd {
            display: block;
            text-align: right;
            margin-top: 10px;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .forgotten-pwd:hover {
            color: #3a5ccc;
            text-decoration: none;
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
        
        /* Cool toggle switch for password show/hide */
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
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card">
            <div class="card-header">
                <img src="ksahc_logo.png" alt="KSAHC Logo" class="logo-img">
                <h3>Karnataka State Allied & Healthcare Council</h3>
                <p class="mb-0">Practitioner Login Portal</p>
            </div>
            <div class="card-body">
                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label for="username"><i class="fas fa-id-card mr-2"></i>Registration Number</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter your registration number" value="<?php echo htmlspecialchars($username); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock mr-2"></i>
                            <?php echo $first_login ? 'Password' : 'Password'; ?>
                        </label>
                        <div class="password-field-container">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" class="form-control" id="password" name="password" placeholder="<?php echo $first_login ? 'Enter your date of birth (YYYY-MM-DD)' : 'Enter your password'; ?>" required>
                            </div>
                            <span class="password-toggle" onclick="togglePasswordVisibility()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </span>
                        </div>
                        <?php if($first_login): ?>
                            <div class="info-box mt-3">
                                <i class="fas fa-info-circle mr-2"></i>
                                This is your first login. Please enter your date of birth in YYYY-MM-DD format as your initial password.
                            </div>
                        <?php else: ?>
                            <a href="forgot_password.php" class="forgotten-pwd">Forgot password?</a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" name="login" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </button>
                    </div>
                </form>
                
                <div class="info-text">
                    <p>If you're logging in for the first time, use your date of birth as your password.</p>
                    <p>Having trouble? Contact support at <a href="mailto:support@ksahc.in">support@ksahc.in</a></p>
                </div>
            </div>
        </div>
        <div class="footer-text mt-3">
            <p>&copy; <?php echo date('Y'); ?> Karnataka State Allied & Healthcare Council. All rights reserved.</p>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize date picker for date of birth field
        flatpickr(".date-picker", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            disableMobile: "true"
        });
        
        // Toggle password visibility
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
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
        
        // Automatically check username for first login status when entered
        $(document).ready(function() {
            $('#username').on('blur', function() {
                const username = $(this).val().trim();
                if (username !== '') {
                    window.location.href = 'login.php?username=' + encodeURIComponent(username);
                }
            });
        });
    </script>
</body>
</html>
