<?php
session_start();
require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $site_full;?> - Dentist Profile</title>
    <?php require_once 'include/header-link.php';?>
    <link href="assets/css/module-css/sidebar.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <?php
        if (empty($_SESSION['_id'])) {
            
            echo "<script>swal.fire({text: 'Kindly login to proceed!',type: 'error',icon: 'error'}).then(function(){window.location = 'login.php';});</script>";
        } else{

            $id = $_SESSION['_id'];

            $resWelcome = mysqli_query($conn, "SELECT practitioner_title, practitioner_name, 
                practitioner_change_of_name, practitioner_profile_image FROM practitioner 
                WHERE practitioner_id = '$id'");
            if(mysqli_num_rows($resWelcome)>0){

                $resWelcome = mysqli_fetch_assoc($resWelcome);

                $practitioneer_name = "";

                if(!empty($resWelcome['practitioner_change_of_name'])){
                    $practitioneer_name = $resWelcome['practitioner_change_of_name'] . ' ' . $resWelcome['practitioner_name'];
                } else{
                    $practitioneer_name = $resWelcome['practitioner_title'] . ' ' . $resWelcome['practitioner_name'];
                }
    ?>
    <div class="boxed_wrapper ltr">

        <?php #require_once 'include/pre-loader.php';?>
        <?php require_once 'include/header.php';?>

        <section class="sidebar-page-container pt_100 pb_100 border-top">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-12 col-sm-12 sidebar-side">
                        <div class="blog-sidebar ml_20">
                            <div class="sidebar-widget category-widget">
                                <div class="widget-content">
                                    <ul class="category-list clearfix">
                                        <li>
                                            <a href="welcome.php">Welcome</a>
                                        </li>
                                        <li>
                                            <a href="profile.php">My Profile</a>
                                        </li>
                                        <li>
                                            <a href="receipts.php">Receipts</a>
                                        </li>
                                        <li>
                                            <a href="payments.php">Payments</a>
                                        </li>
                                        <li>
                                            <h6><a href="ksdc-journal.php">KSDC Journal</a></h6>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-12 col-sm-12 content-side pl_60">
                        <div class="blog-standard-content">
                            <div class="news-block-three">
                                <div class="inner-box">
                                    <div class="lower-content">
                                    </div>
                                </div>
                            </div>
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
        
        } else {
    
            echo "<script>swal.fire({text: 'Unable to process your request.',type: 'error',icon: 'error'}).then(function(){window.location = 'login.php';});</script>";
        }
    }

    ?>

</body>

</html>