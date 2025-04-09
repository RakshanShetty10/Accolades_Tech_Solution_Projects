<?php
// Start session
session_start();

// Database connection
require_once '../config/config.php';

// Initialize error variable
$error = '';

// Check if user is already logged in
if(isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

// Process login form
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Validate input
    if(empty($username) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        // Check user credentials
        $sql = "SELECT * FROM users WHERE username = ? AND user_type = 'admin'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if(password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['admin_id'] = $user['user_id'];
                $_SESSION['admin_name'] = $user['fullname'];
                $_SESSION['admin_username'] = $user['username'];
                
                // Redirect to dashboard
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "Invalid username or password";
        }
    }
}

$pageTitle = "Admin Login | Karnataka State Allied & Healthcare Council";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin-style.css">
</head>
<body class="login-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card login-card animated--grow-in">
                    <div class="card-header">
                        <img src="../../ksahc_logo.png" alt="Logo" style="max-width: 100px; margin-bottom: 15px;">
                        <h4 class="mb-0">Karnataka State Allied & Healthcare Council</h4>
                        <p class="text-muted mb-0">Central Admin Panel</p>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <label for="username"><i class="fas fa-user mr-2"></i>Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password"><i class="fas fa-lock mr-2"></i>Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remember">
                                    <label class="custom-control-label" for="remember">Remember me</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="forgot-password.php">Forgot Password?</a>
                        </div>
                        <div class="text-center mt-3">
                            <a href="../index.php" class="small"><i class="fas fa-arrow-left mr-1"></i> Back to Main Site</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> 