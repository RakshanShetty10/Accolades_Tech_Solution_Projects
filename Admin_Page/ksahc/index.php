<?php
session_start();

if (!empty($_SESSION['user_name'])) {
    echo "<script>location.href='home.php';</script>";
}

require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

?>
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <!-- <?php require_once 'include/meta.php'; ?> -->
    <title><?php echo $site; ?> - Login</title>
</head>

<body class="vh-100">
    <?php
    if (isset($_POST['log_in'])) {

        $username = addslashes(trim($_POST['username']));
        $password = md5(addslashes(trim($_POST['password'])));

        $res = mysqli_query($conn, "SELECT user_role FROM users WHERE user_name = '$username' AND user_password = '$password' AND user_status = 'Active'");

        if (mysqli_num_rows($res) > 0) {

            $res = mysqli_fetch_assoc($res);
            $date = date('Y-m-d H:i:s');
            $user_role = $res['user_role'];

            $_SESSION['user_name'] = $username;
            $_SESSION['user_role'] = $user_role;
            $_SESSION['login_date'] = $date;

            echo "<script>location.href='manage-practitioner.php';</script>";
        } else {

            echo "<script>swal('Failed!', 'Invalid credentials!', 'error')</script>";
        }
    }
    ?>

    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <div class="text-center mb-4">
                                        <img src="../assets/images/logo/ksahc_logo.png" alt="<?php echo $site; ?>">
                                    </div>
                                    <h4 class="text-center mb-4">Sign in your account</h4>
                                    <form method="post" class="form-valide-with-icon needs-validation  dz-form pb-3"
                                        novalidate>
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Username</strong></label>
                                            <input autocomplete="off" type="text" name="username" class="form-control"
                                                placeholder="username" required>
                                            <div class="invalid-feedback">
                                                Please enter a username.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Password</strong></label>
                                            <input autocomplete="off" type="password" name="password"
                                                class="form-control" placeholder="Password" required>
                                            <div class="invalid-feedback">
                                                Please enter a password.
                                            </div>
                                        </div>
                                        <div class="text-center mt-4">
                                            <button type="submit" name="log_in" class="btn btn-primary btn-block">Sign
                                                in</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/custom.js"></script>
    <script src="./js/deznav-init.js"></script>

    <script>
    (function() {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
    </script>
</body>

</html>