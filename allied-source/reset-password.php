<?php
session_start();

if (!empty($_SESSION['_id'])) {
    echo "<script>location.href='welcome.php';</script>";
}

require_once 'config/connection.php';
require_once 'config/utils.php';
require_once 'config/mail-helper.php';

date_default_timezone_set('Asia/Kolkata');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $site_full;?> - Password Reset</title>
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

        if (isset($_POST['dentist-reset'])) {

            $member = addslashes(trim($_POST['registration_number']));

            $res = mysqli_query($conn, "SELECT practitioner_change_of_name, practitioner_name, practitioner_title, 
                practitioner_email_id FROM practitioner WHERE practitioner_username = '$member' AND 
                registration_status = 'Active' AND registration_type_id = 1 AND (vote_status = 'Eligible' 
                OR vote_status = 'Non Indian')");
                
            if(mysqli_num_rows($res)>0){

                $res = mysqli_fetch_assoc($res);

                if(!empty($res['practitioner_change_of_name'])){
                    $name = $res['title']." ".$res['practitioner_change_of_name'];
                } else{
                    $name = $res['practitioner_title']." ".$res['practitioner_name'];
                }

                $email = $res['practitioner_email_id'];

                $date = date('Y-m-d H:i:s');

                $encodes = base64_encode($member);
                $param = urlencode($encodes);

                $encodes_date = base64_encode($date);
                $param_date = urlencode($encodes_date);

                $reset_link = $site_url . "generate-password.php?source=$param_date&pref=$param";

                if(sendResetEmail($name, $email, $reset_link)){
                    
                    echo "<script>swal.fire({text: 'We have sent the password recovery link to your registered email address.', icon:'success'}).then(function(){window.location = 'reset-password.php';});</script>";
                } else{

                    echo "<script>swal.fire({text: 'Unable to process your request.', icon:'error'});</script>";
                }

            } else{

                echo "<script>swal.fire({text: 'Unable to process your request.', icon:'error'});</script>";
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
                            <h2>Reset Your Password</h2>
                            <p>Enter your registration number to receive a password reset link</p>
                        </div>
                        <div class="login-body">
                            <form method="POST" class="login-form">
                                <div class="form-group">
                                    <label for="registration_number">Registration Number</label>
                                    <input autocomplete="off" type="text" id="registration_number" name="registration_number"
                                        placeholder="Enter your registration number" required>
                                    <small>Ex: 1234 A (Do not forget the space between 1234 & A)</small>
                                </div>
                                <button type="submit" class="login-btn" name="dentist-reset">
                                    <i class="fas fa-key"></i> Reset Password
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