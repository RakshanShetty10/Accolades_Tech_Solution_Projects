<?php
// echo password_hash('bigjadmin@1234', PASSWORD_BCRYPT);
// echo md5('ksdcadmin@789%');
session_start();

if (!empty($_SESSION['_id'])) {
    echo "<script>location.href='welcome.php';</script>";
}
require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

$success = '';
// Check if logout message should be shown
if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    $success = 'You have been successfully logged out.';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $site_full;?> - Login</title>
    <?php require_once 'include/header-link.php';?>
    <link href="assets/css/module-css/faq.css" rel="stylesheet">
    <link href="assets/css/profile-design.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
       
        
        .login-section {
            position: relative;
            padding: 80px 0;
            margin-top: -8rem;
            background-color: #f5f6fa;
        }
        
        .login-container {
            display: flex;
            min-height: calc(100vh - 220px);
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 500px;
        }
        
        .login-card:hover {
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }
        
        .login-header {
            background-color: var(--light-color);
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .login-header h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: var(--primary-color);
            font-size: 14px;
            margin-bottom: 0;
        }
        
        .login-body {
            padding: 40px 30px;
        }
        
        .login-form .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .login-form input {
            height: 55px;
            padding: 10px 20px;
            font-size: 16px;
            border: 1px solid #e5e9f2;
            border-radius: var(--border-radius) !important;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .login-form input:focus {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(25, 42, 86, 0.1) !important;
        }
        
        .login-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .login-form small {
            color: #6c757d;
            display: block;
            margin-top: 6px;
        }
        
        .login-form .forgot-link {
            display: block;
            text-align: right;
            margin-bottom: 20px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .login-form .forgot-link:hover {
            color: var(--accent-color);
        }
        
        .login-btn {
            display: block;
            width: 100%;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 15px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(25, 42, 86, 0.2);
        }
        
        .login-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(25, 42, 86, 0.3);
        }
        
        .login-footer {
            text-align: center;
            padding: 20px 30px;
            border-top: 1px solid #e5e9f2;
        }
        
        .login-footer a {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
        }
        
        .login-footer a:hover {
            color: var(--accent-color);
        }
        
        .login-footer a i {
            margin-right: 8px;
        }
        
        .login-logo {
            margin-bottom: 15px;
        }
        
        .login-logo img {
            height: 100px;
            margin-bottom: 5px;
        }
        
        @media (max-width: 768px) {
            .login-section {
                padding: 50px 0;
            }
            
            .login-header {
                padding: 25px 20px;
            }
            
            .login-body {
                padding: 30px 20px;
            }
            
            .login-card {
                margin: 0 15px;
            }
        }
    </style>
</head>

<body>

    <?php
        // Show logout success message if present
        if (!empty($success)) {
            echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: '$success',
                    icon: 'success',
                    confirmButtonText: 'Continue'
                });
            </script>";
        }

        if (isset($_POST['dentist-login'])) {

            $member = addslashes(trim($_POST['registration_number']));
            // $member = str_pad($member, 5, "0", STR_PAD_LEFT);
            $password = $_POST['password'];

            $res = mysqli_query($conn, "SELECT practitioner_id, is_first_login, practitioner_password 
                FROM practitioner WHERE practitioner_username = '$member' AND registration_type_id = 1 
                AND registration_status = 'Active' AND (vote_status = 'Eligible' OR vote_status = 'Non Indian')");

            if (mysqli_num_rows($res) > 0) {

                $res = mysqli_fetch_assoc($res);
                $password_db = $res['practitioner_password'];

                if(password_verify($password, $password_db)){

                    $date = date('Y-m-d H:i:s');

                    $_SESSION['_id'] = $res['practitioner_id'];
                    $_SESSION['_logged'] = $date;
    
                    if($res['is_first_login']=='Yes'){

                        echo "<script>swal.fire({text: 'You have logged in successfully.', icon:'success'}).then(function(){window.location = 'password-manager.php';});</script>";
                    } else{

                        echo "<script>swal.fire({text: 'You have logged in successfully.', icon:'success'}).then(function(){window.location = 'welcome.php';});</script>";
                    }
                } else{

                    echo "<script>swal.fire({text: 'An invalid password you have entered.', icon:'error'});</script>";
                }
                
            } else {

                echo "<script>swal.fire({text: 'Unable to process your request.', icon:'error'});</script>";
            }
        }
        ?>

    <div class="boxed_wrapper ltr">

        <?php #require_once 'include/pre-loader.php';?>
        <?php require_once 'include/header.php';?>

        <section class="login-section">
            <div class="auto-container">
                <div class="login-container">
                    <div class="login-card">
                        <div class="login-header">
                            <div class="login-logo">
                                <img src="assets/images/ksahc_logo.png" alt="Logo">
                            </div>
                            <h2>Practitioner Login</h2>
                            <p>For first-time login, use your date of birth as the password <b>dd/mm/yyyy</b>.</p>
                        </div>
                        <div class="login-body">
                            <form method="post" class="login-form">
                                <div class="form-group">
                                    <label for="registration_number">Registration Number</label>
                                    <input autocomplete="off" type="text" id="registration_number" name="registration_number" 
                                        placeholder="Enter your registration number" required>
                                    <small>Ex: 1234 A (Do not forget the space between 1234 & A)</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input autocomplete="off" type="password" id="password" name="password" 
                                        placeholder="Enter your password" required>
                                </div>
                                
                                <a href="reset-password.php" class="forgot-link">Forgot your password?</a>
                                
                                <button type="submit" class="login-btn" name="dentist-login">
                                    <i class="fas fa-sign-in-alt"></i> Login
                                </button>
                            </form>
                        </div>
                        <div class="login-footer">
                            <a href="assets/login-demo.pdf" target="_blank">
                                Watch Login Demo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php 
            require_once 'include/footer.php';
        ?>

    </div>

    <?php 
        require_once 'include/footer-js.php';
    
    ?>

</body>

</html>