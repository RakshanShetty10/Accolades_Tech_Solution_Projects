<?php
session_start();
require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $site_full;?> - Privacy Policy</title>
    <?php require_once 'include/header-link.php';?>
    <link href="assets/css/module-css/team.css" rel="stylesheet">
    <style>
    /* Enhanced styles for privacy policy */
    /* :root {
        --primary-color: #e36e00;
        --primary-light: rgba(227, 110, 0, 0.1);
        --primary-gradient: linear-gradient(135deg, #e36e00, #ff8c2f);
        --text-color: #333;
        --section-spacing: 3rem;
        --card-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        --border-radius: 12px;
    } */
    
    body {
        color: var(--text-color);
        line-height: 1.7;
    }
    
    .privacy-policy-section {
        background-color: #f8f9fa;
        padding: 4rem 0;
    }
    
    .privacy-section {
        margin-bottom: var(--section-spacing);
        transition: all 0.3s ease;
        border-radius: var(--border-radius);
    }
    
    .privacy-card {
        border: none;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--card-shadow);
        background-color: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .privacy-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    }
    
    .privacy-card .card-header {
        background: var(--primary-gradient);
        color: white;
        border-bottom: none;
        padding: 1.5rem 2rem;
        display: flex;
        align-items: center;
    }
    
    .privacy-card .card-body {
        padding: 2rem;
    }
    
    .privacy-card h3 {
        font-weight: 600;
        margin-bottom: 0;
        font-size: 1.5rem;
    }
    
    .privacy-card h5 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 1rem;
        position: relative;
        padding-bottom: 0.75rem;
    }
    
    .privacy-card h5::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background: var(--primary-gradient);
        border-radius: 3px;
    }
    
    .card-header i {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 15px;
        border-radius: 50%;
        margin-right: 15px;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .privacy-intro {
        background: #fff;
        border-radius: var(--border-radius);
        padding: 2.5rem;
        margin-bottom: 3rem;
        border-left: 5px solid var(--primary-color);
        box-shadow: var(--card-shadow);
    }
    
    .privacy-intro .lead {
        font-size: 1.2rem;
        line-height: 1.8;
        color: #555;
        margin-bottom: 0;
    }
    
    .sec-title h2 {
        color: var(--primary-color);
        font-weight: 700;
    }
    
    .sec-title .text {
        font-size: 1.2rem;
        color: #666;
        margin-top: 0.75rem;
    }
    
    /* Table of contents for better navigation */
    .policy-toc {
        background: #fff;
        border-radius: var(--border-radius);
        padding: 2rem;
        margin-bottom: 3rem;
        box-shadow: var(--card-shadow);
        position: sticky;
        top: 20px;
    }
    
    .policy-toc h4 {
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        font-weight: 600;
    }
    
    .policy-toc ul {
        list-style: none;
        padding-left: 0;
    }
    
    .policy-toc li {
        padding: 0.5rem 0;
        border-bottom: 1px dashed #eee;
    }
    
    .policy-toc a {
        color: #555;
        text-decoration: none;
        display: block;
        padding: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .policy-toc a:hover {
        color: var(--primary-color);
        background-color: var(--primary-light);
        border-radius: 4px;
        padding-left: 1rem;
    }
    
    .policy-toc .toc-icon {
        margin-right: 10px;
        color: var(--primary-color);
    }
    
    /* Back to top button */
    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: var(--primary-gradient);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0.8;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(227, 110, 0, 0.3);
        z-index: 999;
    }
    
    .back-to-top:hover {
        opacity: 1;
        transform: translateY(-5px);
    }
    
    /* Last updated info */
    .last-updated {
        text-align: center;
        margin-top: 2rem;
        font-style: italic;
        color: #777;
    }
    
    @media (max-width: 768px) {
        .privacy-card .card-header {
            padding: 1.25rem;
        }
        
        .privacy-card .card-body {
            padding: 1.5rem;
        }
        
        .privacy-intro {
            padding: 1.5rem;
        }
    }
</style>

</head>

<body>

    <div class="boxed_wrapper ltr">

        <?php require_once 'include/header.php';?>

        <section class="page-title centred">
            <div class="bg-layer" style="background-image: url(assets/images/banner/ksdc-sub-banner.png);"></div>
            <div class="pattern-layer">
                <div class="pattern-1" style="background-image: url(assets/images/shape/shape-18.png);"></div>
            </div>
            <div class="auto-container">
                <div class="content-box">
                    <h1>Privacy Policy</h1>
                    <ul class="bread-crumb clearfix">
                        <li><a href="index.php">Home</a></li>
                        <li>Privacy Policy</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="privacy-policy-section pt_80 pb_60">
            <div class="auto-container">
                <div class="sec-title mb_50 centred">
                    <h2>Our Privacy Commitment</h2>
                    <div class="text">We are committed to protecting your personal information</div>
                </div>
                
                <div class="content-box">
                    <div class="row">
                        <!-- Table of Contents Sidebar -->
                        <div class="col-lg-3 d-none d-lg-block">
                            <div class="policy-toc">
                                <h4>Quick Navigation</h4>
                                <ul>
                                    <li><a href="#introduction"><i class="fas fa-file-alt toc-icon"></i> Introduction</a></li>
                                    <li><a href="#info-collection"><i class="fas fa-user-shield toc-icon"></i> Information Collection</a></li>
                                    <li><a href="#cookies"><i class="fas fa-cookie-bite toc-icon"></i> Cookies & Tracking</a></li>
                                    <li><a href="#security"><i class="fas fa-lock toc-icon"></i> Data Security</a></li>
                                    <li><a href="#updates"><i class="fas fa-exclamation-circle toc-icon"></i> Policy Updates</a></li>
                                    <li><a href="#sharing"><i class="fas fa-share-alt toc-icon"></i> Information Sharing</a></li>
                                    <li><a href="#responsibilities"><i class="fas fa-user-check toc-icon"></i> User Responsibilities</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Main Content -->
                        <div class="col-lg-9">
                            <div id="introduction" class="privacy-intro">
                                <p class="lead">The Karnataka State Dental Council Privacy Policy describes how we treat personal information when you use our website, including information provided when you use our services. This policy outlines our practices regarding data collection, usage, and protection to ensure transparency and build trust with our users.</p>
                            </div>
                            
                            <div id="info-collection" class="privacy-section">
                                <div class="privacy-card">
                                    <div class="card-header">
                                        <i class="fas fa-user-shield"></i>
                                        <h3>Information Collection</h3>
                                    </div>
                                    <div class="card-body">
                                        <p>Karnataka State Dental Council collects necessary personal information to enable membership, including your email address and a password, which protects your account from unauthorized access. Your Karnataka State Dental Council Account allows you to access your member area to manage your profile and view relevant details.</p>
                                        <p>When you sign up or use our services, you voluntarily provide us with required information. We may also collect user details, information, and location data to deliver requested services. We never share your personal data with third parties for advertising or marketing purposes.</p>
                                        <p>For SMS communications with our website, we collect and maintain associated information including phone numbers, carriers, message content, and timestamps. When you interact with specific features, we may collect and maintain related activity information.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="cookies" class="privacy-section">
                                <div class="privacy-card">
                                    <div class="card-header">
                                        <i class="fas fa-cookie-bite"></i>
                                        <h3>Cookies & Tracking</h3>
                                    </div>
                                    <div class="card-body">
                                        <p>After logging into Karnataka State Dental Council Services, we use cookies to identify you during your session. A temporary cookie is stored on your computer and automatically deleted when your session ends.</p>
                                        <p>You may enable automatic login for subsequent sessions by selecting "Login automatically on this computer." This stores part of your login data in encrypted form. Automatic login is only possible on the computer where the cookie was originally saved.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="security" class="privacy-section">
                                <div class="privacy-card">
                                    <div class="card-header">
                                        <i class="fas fa-lock"></i>
                                        <h3>Data Security</h3>
                                    </div>
                                    <div class="card-body">
                                        <p>Karnataka State Dental Council prioritizes the confidentiality of your personal information. We implement comprehensive administrative, physical, and electronic security measures to protect against unauthorized access.</p>
                                        <p>We commit to legally-required disclosures regarding any security breaches affecting your unencrypted personal data. Notifications will be made promptly via email or conspicuous website posting, balancing law enforcement needs with our obligation to inform you and restore system integrity.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="updates" class="privacy-section">
                                <div class="privacy-card">
                                    <div class="card-header">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <h3>Policy Updates</h3>
                                    </div>
                                    <div class="card-body">
                                        <p>Karnataka State Dental Council reserves the right to update this privacy policy at any time. For significant changes affecting your personal information treatment, we will notify you through our website or email, as described above.</p>
                                        <p>Unless specified otherwise, the current Privacy Policy applies to all information we maintain about you and your account. Continued use of our services after policy changes constitutes your acceptance of the updated terms.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="sharing" class="privacy-section">
                                <div class="privacy-card">
                                    <div class="card-header">
                                        <i class="fas fa-share-alt"></i>
                                        <h3>Information Sharing</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <h5>Service Providers</h5>
                                            <p>We engage trusted third parties to perform essential functions including hosting, maintenance, customer relations, and database management. We share only necessary personal information with these providers under strict contractual obligations requiring them to maintain your data's privacy and security.</p>
                                        </div>
                                        <div>
                                            <h5>Legal Compliance</h5>
                                            <p>Karnataka State Dental Council cooperates with government and law enforcement when legally required. We may disclose information when we believe in good faith it's necessary to comply with laws, legal processes, or to protect the rights and safety of our organization, users, or the public.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="responsibilities" class="privacy-section">
                                <div class="privacy-card">
                                    <div class="card-header">
                                        <i class="fas fa-user-check"></i>
                                        <h3>User Responsibilities</h3>
                                    </div>
                                    <div class="card-body">
                                        <p>Users must comply with our current Privacy Policy and User Agreement terms, including respecting all intellectual property rights belonging to third parties.</p>
                                        <p>You agree not to disseminate information that is injurious, violent, offensive, racist, xenophobic, or otherwise violates our community standards. Provide only information you're comfortable sharing regarding your personal, professional, or social status.</p>
                                        <p>Violations of these guidelines may result in account restriction, suspension, or termination, as we uphold these principles to maintain a trustworthy environment for all users of Karnataka State Dental Council services.</p>
                                    </div>
                                </div>
                            </div>
                            
                           
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Back to top button -->
            <!-- <div class="back-to-top">
                <i class="fas fa-arrow-up"></i>
            </div> -->
        </section>

        <?php 
            require_once 'include/footer.php';
        ?>

    </div>

    <?php 
        require_once 'include/footer-js.php';
    ?>
    
    <script>
    // Back to top button functionality
    $(document).ready(function() {
        var backToTop = $('.back-to-top');
        
        // Initially hide the button
        backToTop.hide();
        
        // Show/hide based on scroll position
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                backToTop.fadeIn();
            } else {
                backToTop.fadeOut();
            }
        });
        
        // Smooth scroll to top
        backToTop.click(function() {
            $('html, body').animate({scrollTop: 0}, 800);
            return false;
        });
        
        // Smooth scroll for table of contents links
        $('.policy-toc a').click(function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            $('html, body').animate({
                scrollTop: $(target).offset().top - 80
            }, 800);
        });
        
        // Add subtle animation to cards on hover
        $('.privacy-card').hover(
            function() {
                $(this).find('.card-header i').css('transform', 'rotate(10deg)');
            },
            function() {
                $(this).find('.card-header i').css('transform', 'rotate(0deg)');
            }
        );
    });
    </script>

</body>

</html>