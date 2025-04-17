<?php
session_start();
require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

?>
<!DOCTYPE html> 
<html lang="en">

<head>
    <title><?php echo $site_full;?> - Welcome</title>
    <?php require_once 'include/header-link.php';?>
    <link href="assets/css/module-css/sidebar.css" rel="stylesheet">
    <link href="assets/css/profile-design.css" rel="stylesheet">
    <link href="assets/css/profile-banner.css" rel="stylesheet">
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

        <?php require_once 'include/pre-loader.php';?>
        <?php require_once 'include/header.php';?>
        
        <!-- Profile Banner -->
        <div class="profile-banner">
            <div class="banner-pattern"></div>
            <h2 class="banner-title">Welcome to KSAHC Portal</h2>
            <div class="banner-subtitle">Karnataka State Allied Council</div>
            <div class="banner-wave"></div>
        </div>

        <section class="sidebar-page-container welcome-container pt_100 pb_100">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-12 col-sm-12 sidebar-side">
                        <div class="blog-sidebar ml_20">
                            <div class="sidebar-widget category-widget">
                                <div class="widget-content">
                                    <ul class="category-list clearfix">
                                        <li>
                                            <h6><a href="welcome.php">Welcome</a></h6>
                                        </li>
                                        <li><a href="profile.php">My Profile</a></li>
                                        <li><a href="receipts.php">Receipts</a></li>
                                        <li><a href="payments.php">Payments</a></li>
                                        <!-- <li><a href="ksdc-journal.php">KSDC Journal</a></li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-12 col-sm-12 content-side pl_60">
                        <div class="blog-standard-content content-area">
                            <div class="news-block-three">
                                <div class="inner-box">
                                    <div class="lower-content welcome-message">
                                        <h4 class="welcome-header">Welcome <?php echo $practitioneer_name;?></h4>
                                        <div class="welcome-content">
                                            <div class="welcome-image">
                                            <img src="assets/chairman/chairman.jpeg" alt="chaiman_img" width="100px" height="100px">

                                                <!-- <img src="<?php if (!empty($resWelcome['practitioner_profile_image'])) {
                                                    echo 'admin/images/dentist/' . $resWelcome['practitioner_profile_image'];
                                                } else {
                                                    echo 'admin/images/other/dentist.png';
                                                } ?>" alt="<?php echo $practitioneer_name;?>"> -->
                                            </div>
                                            <div class="welcome-text">
                                                <p class="greetings">My dear Fellow Practitioner,</p>
                                                <p>&nbsp;&nbsp;&nbsp;&nbsp;Greetings from me and my team.
                                                The establishment of the State Council for Allied and Health Care Professionals marks a significant advancement for the healthcare industry. As Chairman, I commend the hard work of allied and health care professionals who advocated for this council. Its goals include regulating and standardizing the profession, ensuring quality education, promoting professional development, and advocating for professionals' rights. The council will enhance public awareness, facilitate networking, and uphold accountability and ethics. Additionally, it aims to provide opportunities for professional growth and influence healthcare policy. As the first appointed Chairman, I embrace the responsibility of creating guidelines that will elevate the allied and health care profession..</p>
                                                
                                                <div class="signature-block">
                                                    <h6 class="signature-name"><?php echo $president_name?></h6>
                                                    <span class="signature-title">Chairman KSAHC, <?php echo $president_qualification?></span>
                                                </div>
                                            </div>
                                        </div>
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