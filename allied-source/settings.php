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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    
                    if(mysqli_query($conn, "UPDATE practitioner SET practitioner_password = '$updated_password'
                        WHERE practitioner_id = '$id'")){

                        echo "<script>swal.fire({text: 'Your password updated successfully.', icon:'success'}).then(function(){window.location = 'settings.php';});</script>";
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

        <section class="faq-form-section pt_100 pb_100" style="background-color: whitesmoke;">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-12 col-sm-12"></div>
                    <div class="col-lg-6 col-md-12 col-sm-12 border pt_70 pb_50 bg-light"
                        style="box-shadow: 0px 10px 40px 10px rgba(0, 0, 0, 0.07);">
                        <div class="sec-title mb_50 centred">
                            <h2>Change Password</h2>
                        </div>
                        <form method="post" class="form-inner">
                            <div class="form-group">
                                <input autocomplete="off" type="password" name="c_password"
                                    placeholder="Current Password" required>
                            </div>
                            <div class="form-group">
                                <input autocomplete="off" type="password" name="n_password" placeholder="New Password"
                                    required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                                    title="Must contain at least one number and one uppercase and lowercase letter, and at least 6 or more characters"
                                    maxlength="50">
                            </div>
                            <div class="form-group message-btn centred">
                                <button type="submit" class="theme-btn btn-one" name="update_password">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12"></div>
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