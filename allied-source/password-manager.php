<?php
session_start();

require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $site_full;?> - Change Password</title>
    <?php require_once 'include/header-link.php';?>
    <link href="assets/css/module-css/faq.css" rel="stylesheet">
    <link href="assets/css/profile-design.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
        }
        
        .password-section {
            position: relative;
            padding: 80px 0;
            background-color: #f5f6fa;
            margin-top: -8rem;

        }
        
        .password-container {
            display: flex;
            min-height: calc(100vh - 220px);
            align-items: center;
            justify-content: center;
        }
        
        .password-card {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 500px;
        }
        
        .password-card:hover {
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }
        
        .password-header {
            background-color: var(--light-color);
            padding: 30px;
            text-align: center;
            position: relative;
        }
        
        .password-header h2 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .password-header p {
            color: var(--primary-color);
            font-size: 14px;
            margin-bottom: 0;
        }
        
        .password-body {
            padding: 40px 30px;
        }
        
        .password-form .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .password-form input {
            height: 55px;
            padding: 10px 20px;
            font-size: 16px;
            border: 1px solid #e5e9f2;
            border-radius: var(--border-radius) !important;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .password-form input:focus {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 3px rgba(25, 42, 86, 0.1) !important;
        }
        
        .password-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .password-form small {
            color: #6c757d;
            display: block;
            margin-top: 6px;
        }
        .login-logo img {
            height: 100px;
            margin-bottom: 15px;
        }
        
        .password-btn {
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
            box-shadow: 0 4px 6px rgba(68, 189, 50, 0.2);
        }
        
        .password-btn:hover {
            background-color: #3aa528;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(68, 189, 50, 0.3);
        }
        
        .password-requirements {
            background-color: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary-color);
        }
        
        .password-requirements h5 {
            color: var(--primary-color);
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .password-requirements ul {
            padding-left: 20px;
            margin-bottom: 0;
        }
        
        .password-requirements li {
            color: #555;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        @media (max-width: 768px) {
            .password-section {
                padding: 50px 0;
            }
            
            .password-header {
                padding: 25px 20px;
            }
            
            .password-body {
                padding: 30px 20px;
            }
            
            .password-card {
                margin: 0 15px;
            }
        }
    </style>
</head>

<body>

    <?php

    if (empty($_SESSION['_id'])) {
            
            echo "<script>swal.fire({text: 'Kindly login to proceed!',type: 'error',icon: 'error'}).then(function(){window.location = 'login.php';});</script>";
        } else{

            $id = $_SESSION['_id'];

        if (isset($_POST['update_password'])) {

            $n_password = addslashes(trim($_POST['n_password']));
            $password = addslashes(trim($_POST['c_password']));

            $res = mysqli_query($conn, "SELECT practitioner_password FROM practitioner 
                WHERE practitioner_id = '$id' AND registration_status = 'Active'");

            if (mysqli_num_rows($res) > 0) {

                $res = mysqli_fetch_assoc($res);

                $password_db = $res['practitioner_password'];

                if(password_verify($password, $password_db)){

                    $updated_password = password_hash($n_password, PASSWORD_BCRYPT);
    
                    if(mysqli_query($conn, "UPDATE practitioner SET practitioner_password = '$updated_password', 
                        is_first_login = 'No' WHERE practitioner_id = '$id'")){

                        echo "<script>swal.fire({text: 'Your password updated successfully.', icon:'success'}).then(function(){window.location = 'welcome.php';});</script>";
                    } else{

                        echo "<script>swal.fire({text: 'Unable to process your request.', icon:'error'});</script>";
                    }
                } else{

                    echo "<script>swal.fire({text: 'An invalid current password you have entered.', icon:'error'});</script>";
                }
                
            } else {

                echo "<script>swal.fire({text: 'Unable to process your request.', icon:'error'});</script>";
            }
        }
        ?>

    <div class="boxed_wrapper ltr">

        <?php #require_once 'include/pre-loader.php';?>
        <?php require_once 'include/header.php';?>

        <section class="password-section">
            <div class="auto-container">
                <div class="password-container">
                    <div class="password-card">
                        <div class="password-header">
                            <div class="login-logo">
                                <img src="assets/images/ksahc_logo.png" alt="Logo">
                            </div>
                            <h2>Change Password</h2>
                            <p>Update your password to continue</p>
                        </div>
                        <div class="password-body">
                            <!-- <div class="password-requirements">
                                <h5><i class="fas fa-shield-alt"></i> Password Requirements</h5>
                                <ul>
                                    <li>At least one number</li>
                                    <li>At least one uppercase letter</li>
                                    <li>At least one lowercase letter</li>
                                    <li>Minimum 6 characters length</li>
                                </ul>
                            </div> -->
                            <form method="post" class="password-form">
                                <div class="form-group">
                                    <label for="c_password">Current Password</label>
                                    <input type="password" id="c_password" name="c_password" 
                                        placeholder="Enter your current password" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="n_password">New Password</label>
                                    <input type="password" id="n_password" name="n_password" 
                                        placeholder="Enter your new password" required
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                                        title="Must contain at least one number and one uppercase and lowercase letter, and at least 6 or more characters"
                                        maxlength="50">
                                </div>
                                
                                <button type="submit" class="password-btn" name="update_password">
                                    <i class="fas fa-key"></i> Update Password
                                </button>
                            </form>
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
    }
    ?>

</body>

</html>