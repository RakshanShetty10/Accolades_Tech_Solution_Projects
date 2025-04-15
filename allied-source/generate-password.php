<?php
session_start();

require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $site_full;?> - Update Password</title>
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
        
        $source = "";
        $pref = "";

        if (!empty($_GET['source'])) {
            $source = base64_decode(urldecode($_GET['source']));
        }

        if (!empty($_GET['pref'])) {
            $pref = base64_decode(urldecode($_GET['pref']));
        }
        $source;

        $date = date('Y-m-d H:i:s');
        $enddate = date('Y-m-d H:i:s', strtotime($source . ' +10 minutes'));

        if ($enddate < $date) {

            echo "<script>swal.fire({text: 'This link has been expired!', icon:'error'}).then(function(){window.location = 'login.php';});</script>";
        }

        if (isset($_POST['_change'])) {

            $n_password = addslashes(trim($_POST['n_password']));
            $c_password = addslashes(trim($_POST['c_password']));

            if ($c_password == $n_password) {

                $n_password = password_hash($n_password, PASSWORD_BCRYPT);

                if (mysqli_query($conn, "UPDATE practitioner SET practitioner_password = '$n_password' WHERE practitioner_username = '$pref'")) {
    
                    echo "<script>swal.fire({text: 'Password updated successfully!', icon:'success'}).then(function(){window.location = 'login.php';});</script>";
                } else {

                    echo "<script>swal.fire({text: 'Unable to process your request!', icon:'error'});</script>";
                }
                
            } else {

                echo "<script>swal.fire({text: 'Password confirmation doesnot match!', icon:'error'});</script>";
            }
        }

        ?>

    <div class="boxed_wrapper ltr">

        <?php #require_once 'include/pre-loader.php';?>
        <?php require_once 'include/header.php';?>

        <!-- <section class="page-title centred">
            <div class="bg-layer" style="background-image: url(assets/images/banner/ksdc-sub-banner.png);"></div>
            <div class="pattern-layer">
                <div class="pattern-1" style="background-image: url(assets/images/shape/shape-18.png);"></div>
            </div>
            <div class="auto-container">
                <div class="content-box">
                    <h1>Password Reset</h1>
                    <ul class="bread-crumb clearfix">
                        <li><a href="index.php">Home</a></li>
                        <li>Password Reset</li>
                    </ul>
                </div>
            </div>
        </section> -->

        <section class="login-section">
            <div class="auto-container">
                <div class="login-container">
                    <div class="login-card">
                        <div class="login-header">
                            <div class="login-logo">
                                <img src="assets/images/ksahc_logo.png" alt="Logo">
                            </div>
                            <h2>Update Your Password</h2>
                        </div>
                        <div class="login-body">
                            <form method="POST" class="login-form">
                                <div class="form-group">
                                    <label for="n_password">New Password</label>
                                    <input autocomplete="off" type="password" id="n_password" name="n_password" 
                                        placeholder="Enter your new password" required
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                                        title="Must contain at least one number and one uppercase and lowercase letter, and at least 6 or more characters">
                                    <small>At least 1 number and 1 uppercase and lowercase letter, and at least 6 or more characters</small>
                                </div>
                                <div class="form-group">
                                    <label for="c_password">Confirm Password</label>
                                    <input autocomplete="off" type="password" id="c_password" name="c_password" 
                                        placeholder="Confirm your new password" required>
                                </div>
                                <button type="submit" class="login-btn" name="_change">
                                    <i class="fas fa-key"></i> Update Password
                                </button>
                            </form>
                        </div>
                        <div class="login-footer">
                            <a href="login.php">
                                <i class="fas fa-arrow-left"></i> Back to Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <?php 
            require_once 'include/footer.php';
        ?>

    </div>

    <?php 
        require_once 'include/footer-js.php';
    ?>

</body>

</html>