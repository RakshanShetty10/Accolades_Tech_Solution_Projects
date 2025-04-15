<!-- KSAHC Portal Footer -->
<footer class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                    <div class="footer-widget">
                        <div class="footer-logo mb-3">
                            <img src="assets/images/ksahc_logo.png" alt="KSAHC Logo" class="img-fluid" style="max-height: 50px;">
                            <h5 class="mt-2">Karnataka State Allied & Healthcare Professions Council</h5>
                        </div>
                        <p class="footer-description">A statutory body established under the Karnataka State Allied and Healthcare Professions Act, 2021, dedicated to regulating and maintaining standards in allied and healthcare professions.</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <div class="footer-widget">
                        <h5 class="widget-title">Quick Links</h5>
                        <ul class="footer-links">
                            <li><a href="welcome.php">Home</a></li>
                            <li><a href="profile.php">My Profile</a></li>
                            <li><a href="#">Certificates</a></li>
                            <li><a href="#">Renewals</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <div class="footer-widget">
                        <h5 class="widget-title">Important Links</h5>
                        <ul class="footer-links">
                            <li><a href="#">Acts & Rules</a></li>
                            <li><a href="#">Notifications</a></li>
                            <li><a href="#">Circulars</a></li>
                            <li><a href="#">Forms</a></li>
                            <li><a href="#">Grievance Redressal</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <h5 class="widget-title">Contact Us</h5>
                        <ul class="footer-contact">
                            <li>
                                Karnataka State Allied & Healthcare Council<br>
                                No. 123, Health Department Building,<br>
                                Bangalore - 560001, Karnataka, India
                            </li>
                            <li>
                                +91 80 2345 6789
                            </li>
                            <li>
                                info@ksahc.karnataka.gov.in
                            </li>
                            <li>
                                Monday to Friday: 9:00 AM - 5:00 PM
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="copyright">&copy; <?php echo date('Y'); ?> Karnataka State Allied & Healthcare Council. All Rights Reserved.</p>
                </div>
                <div class="col-md-6">
                    <div class="footer-bottom-links">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Use</a>
                        <a href="#">Disclaimer</a>
                        <a href="#">Accessibility</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Footer Styles -->
<style>
    .footer {
        background: #274472;
        color: #fff;
        margin-top: 30px;
    }

    .footer-top {
        padding: 40px 0 20px;
    }

    .footer-widget {
        margin-bottom: 20px;
    }

    .footer-logo img {
        max-height: 50px;
    }

    .footer-logo h5 {
        color: #fff;
        font-size: 16px;
        font-weight: 600;
    }

    .footer-description {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.5;
        font-size: 14px;
    }

    .widget-title {
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        position: relative;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 8px;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 14px;
    }

    .footer-links a:hover {
        color: #fff;
    }

    .footer-contact {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-contact li {
        margin-bottom: 12px;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.5;
        font-size: 14px;
    }

    .footer-bottom {
        background: rgba(0, 0, 0, 0.2);
        padding: 15px 0;
    }

    .copyright {
        color: rgba(255, 255, 255, 0.7);
        margin: 0;
        font-size: 14px;
    }

    .footer-bottom-links {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 15px;
    }

    .footer-bottom-links a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        font-size: 13px;
    }

    .footer-bottom-links a:hover {
        color: #fff;
    }

    @media (max-width: 767px) {
        .footer-bottom-links {
            justify-content: center;
            margin-top: 10px;
        }
        
        .copyright {
            text-align: center;
        }
    }
</style> 