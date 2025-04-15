<?php
session_start();
require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $site_full;?> - Home</title>
    <!-- <?php require_once 'include/header-link.php';?> -->
    <link href="assets/css/module-css/banner.css" rel="stylesheet">
    <link href="assets/css/module-css/feature.css" rel="stylesheet">
    <link href="assets/css/module-css/career-details.css" rel="stylesheet">
    <link href="assets/css/module-css/testimonial.css" rel="stylesheet">
    <link href="assets/css/module-css/subscribe.css" rel="stylesheet">
</head>

<body>

    <div class="boxed_wrapper ltr">

        <?php require_once 'include/pre-loader.php';?>
        <?php require_once 'include/header.php';?>

        <section class="banner-section p_relative">
            <div class="banner-carousel owl-theme owl-carousel owl-dots-none">
                <?php
                    $resSlider = mysqli_query($conn, "SELECT slider_image FROM slider_master 
                        WHERE slider_status = 'Active' ORDER BY slider_id DESC");
                    if (mysqli_num_rows($resSlider)) {
                        
                        while ($rowSlider = mysqli_fetch_assoc($resSlider)) {
                    ?>
                <div class="slide-item p_relative">
                    <div class="bg-layer"
                        style="background-image: url(admin/images/slider/<?php echo $rowSlider['slider_image'];?>);">
                    </div>
                    <div class="pattern-layer">
                        <div class="pattern-2" style="background-image: url(assets/images/shape/shape-2.png);"></div>
                    </div>
                    <div class="auto-container">
                        <div class="content-box">
                            <h2>&nbsp;<br>&nbsp;</h2>
                            <p>&nbsp;</p>
                        </div>
                    </div>
                </div>
                <?php
                        }
                    } ?>
            </div>
        </section>

        <section class="feature-section">
            <div class="auto-container">
                <div class="inner-container clearfix wow fadeInLeft animated" data-wow-delay="00ms"
                    data-wow-duration="1500ms">
                    <div class="feature-block-one centred border-bottom">
                        <a href="registration.php">
                            <div class="inner-box">
                                <div class="icon-box"><i class="icon-28"></i></div>
                                <h4>New Registration</h4>
                            </div>
                        </a>
                    </div>
                    <div class="feature-block-one centred border-bottom">
                        <a href="login.php">
                            <div class="inner-box">
                                <div class="icon-box"><i class="icon-5"></i></div>
                                <h4>Login/Renewal</h4>
                            </div>
                        </a>
                    </div>
                    <div class="feature-block-one centred border-bottom">
                        <a href="ksdc-approved-cde-program.php">
                            <div class="inner-box">
                                <div class="icon-box"><i class="icon-36"></i></div>
                                <h4>CDE Program</h4>
                            </div>
                        </a>
                    </div>
                    <div class="feature-block-one centred border-bottom">
                        <a href="consent.php">
                            <div class="inner-box">
                                <div class="icon-box"><i class="icon-31"></i></div>
                                <h4>Consent Form</h4>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="career-details pt_90 pb_40">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="col-lg-8 col-md-12 col-sm-12 content-side"
                        style="background: white;box-shadow: 0px 20px 60px 10px rgba(0, 0, 0, 0.07);">
                        <div class="career-details-content">
                            <div class="content-one mb_40 mt_40 ml_20 mr_20">
                                <h2>Message</h2>
                                <p class="mt_20"><?php echo substr($president_message, 0, 670)?>..</p>
                                <a href="about-president.php" style="border: 1px solid var(--theme-color);"
                                    class="theme-btn btn-three">Read More</a>
                            </div>
                        </div>
                    </div>

                    <style>
                    .news-ticker {
                        height: 366px;
                        overflow: hidden;
                        position: relative;
                    }

                    @keyframes scroll {
                        0% {
                            top: 0;
                        }

                        100% {
                            top: -100%;
                        }
                    }
                    </style>
                    <div class="col-lg-4 col-md-12 col-sm-12 sidebar-side">
                        <div class="career-sidebar ml_40">
                            <h4>Announcement</h4>
                            <div class="news-ticker">
                                <ul class="info-list clearfix">
                                    <?php
                    $resAnnouncement = mysqli_query($conn, "SELECT announcement_title FROM announcement 
                        WHERE announcement_status = 'Active' ORDER BY announcement_id DESC");
                    if (mysqli_num_rows($resAnnouncement)) {
                        
                        while ($rowAnnouncement = mysqli_fetch_assoc($resAnnouncement)) {
                    ?>
                                    <li>
                                        <a href="announcement.php">
                                            <p><?php echo substr($rowAnnouncement['announcement_title'], 0, 110)?>..</p>
                                        </a>
                                    </li>
                                    <?php
                        }
                    } ?>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const ticker = document.querySelector('.news-ticker');
                        const list = ticker.querySelector('.info-list');
                        const items = list.querySelectorAll('li');

                        list.append(...Array.from(items).map(item => item.cloneNode(true)));

                        let scrollHeight = items[0].offsetHeight;
                        let scrollSpeed = 0.3; // Adjust speed here

                        let scrollPosition = 0;

                        function scrollNews() {
                            scrollPosition -= scrollSpeed;
                            if (scrollPosition <= -list.scrollHeight / 2) {
                                scrollPosition = 0;
                            }
                            list.style.transform = `translateY(${scrollPosition}px)`;
                            requestAnimationFrame(scrollNews);
                        }

                        ticker.addEventListener('mouseenter', () => {
                            scrollSpeed = 0;
                        });

                        ticker.addEventListener('mouseleave', () => {
                            scrollSpeed = 0.3;
                        });

                        scrollNews();
                    });
                    </script>

                    <div class="col-lg-12 col-md-12 col-sm-12 content-side pt_70">
                        <div class="career-details-content">
                            <div class="content-one mb_60">
                                <h2>Statutory Body</h2>
                                <p class="mt_20">The Dentist Act - An act to regulate the profession of dentistry was
                                    passed in the parliament on 29th of March 1948, and subsequently Dental Council of
                                    India came into existence. However it became effective in the state of Karnataka
                                    only in 1958.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="testimonial-style-two service-page-one pt_120 pb_120">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-12 col-sm-12 title-column">
                        <div class="sec-title mr_70">
                            <h6>Feedbacks</h6>
                            <h2>Love from Happy Clients</h2>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12 col-sm-12 content-column">
                        <div class="two-item-carousel owl-carousel owl-theme owl-dots-none nav-style-one">
                            <?php
                    $resFeedback = mysqli_query($conn, "SELECT testimonial_client_name, testimonial_client_designation, 
                        testimonial_description FROM testimonials 
                        WHERE testimonial_status = 'Active' ORDER BY testimonial_id DESC");
                    if (mysqli_num_rows($resFeedback)) {
                        
                        while ($rowFeedback = mysqli_fetch_assoc($resFeedback)) {
                    ?>
                            <div class="testimonial-block-two">
                                <div class="inner-box" style="min-height:290px;">
                                    <div>
                                        <h4><?php echo $rowFeedback['testimonial_client_name'];?></h4>
                                    </div>
                                    <p class="mt_10">
                                        <span class="mb_10"
                                            style="color: #000"><?php echo $rowFeedback['testimonial_client_designation'];?></span><br>
                                        “<?php 
                                        if (mb_strlen($rowFeedback['testimonial_description']) > 200) {
                                            echo mb_strimwidth($rowFeedback['testimonial_description'], 0, 200, "...");
                                        } else {
                                            echo $rowFeedback['testimonial_description'];
                                        }?>
                                    </p>
                                </div>
                            </div>
                            <?php
                        }
                    } ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="career-details pt_20 pb_40">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 content-side pt_70">
                        <div class="career-details-content">
                            <div class="content-one mb_60 sec-title">
                                <h6>Patients Grievances</h6>
                                <h2>Guidelines for lodging complaints</h2>
                                <p class="mt_20">Complaints must be brief and contain factful details, verifiable facts
                                    and related matter. They should not be vague or contain absurd allegations and
                                    sweeping statement...</p>
                                <a href="patients-grievances.php" style="border: 1px solid var(--theme-color);"
                                    class="theme-btn btn-three">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="subscribe-section">
            <div class="pattern-layer" style="background-image: url(assets/images/shape/shape-5.png);"></div>
            <div class="auto-container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12 col-sm-12 text-column">
                        <div class="text-box">
                            <h2>Find Dentist</h2>
                            <p class="text-light">Karnataka, India</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 form-column">
                        <div class="form-inner ml_40">
                            <form method="get" action="search-dentist.php">
                                <div class="form-group">
                                    <input type="text" name="input" placeholder="Search by name / area / city / pincode"
                                        required>
                                    <button type="submit" class="theme-btn btn-two">Search</button>
                                </div>
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
    ?>

</body>

</html>