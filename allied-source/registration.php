<?php
session_start();
require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $site_full; ?> - Registration</title>
    <?php require_once 'include/header-link.php'; ?>
    <link href="assets/css/module-css/faq.css" rel="stylesheet">
</head>

<body>
    <div class="boxed_wrapper ltr">

        <?php #require_once 'include/pre-loader.php'; 
        ?>
        <?php require_once 'include/header.php'; ?>

        <section class="page-title centred">
            <div class="bg-layer" style="background-image: url(assets/images/banner/ksdc-sub-banner.png);"></div>
            <div class="pattern-layer">
                <div class="pattern-1" style="background-image: url(assets/images/shape/shape-18.png);"></div>
            </div>
            <div class="auto-container">
                <div class="content-box">
                    <h1>Registration</h1>
                    <ul class="bread-crumb clearfix">
                        <li><a href="index.php">Home</a></li>
                        <li>Registration</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="faq-section pt_80 pb_90 border-top">
            <div class="auto-container">
                <div class="sec-title centred mb_70">
                    <h5 style="line-height: 1.6;">(We wish to inform you that EDC/Swiping machine/UPI facility is
                        available for payments at KSDC
                        office from 1st april 2017.
                        Members are requested to avail this facility.)</h5>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 accordion-column">
                        <ul class="accordion-box">
                            <li class="accordion block active-block">
                                <div class="acc-btn active">
                                    <div class="icon-box"></div>
                                    <h4> For Registration under Part-A (Only for Indian Nationals)</h4>
                                </div>
                                <div class="acc-content current">

                                    <div class="text">
                                        <ul class="list-style-one clearfix">
                                            <li>
                                                <?php
                                                $id = 1;
                                                $sql = "SELECT download_file FROM download_master WHERE download_id = '$id' AND download_status = 'Active'";
                                                $result = mysqli_query($conn, $sql);
                                                $downloadLink = '';

                                                if ($result && mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    $downloadLink = 'admin/images/download/' . $row['download_file'];
                                                }
                                                ?>
                                                Application in Form C.<a href="<?php echo $downloadLink ?>"
                                                    target="_blank"> click here to download.</a>
                                            </li>
                                            <li>Copy of Degree Certificate or Provisional Degree Certificate (PDC)
                                                issued by the University with original.</li>
                                            <li>Copy of Compulsory Rotating Internship Completion Certificate(CRICC)
                                                with Photo attested by principal with original.</li>
                                            <li>Copy of Final year part-II & Part I Marks cards with original.</li>

                                            <li>Original and photo copy of the SSLC/10th Marks Sheet or Birth
                                                Certificate or Indian Passport or PAN card to be produced which is
                                                mandatory for proof of Date of Birth & Father Name.</li>

                                            <li>Two recent Passport size Photos with names entered on the back side of
                                                the photo ( Doctors Apron ).</li>
                                            <li>The Total Amount payable at the time of registration by Swiping Card or
                                                UPI or through Demand Draft in the name of KARNATKA STATE DENTAL
                                                COUNCIL, BENGALURU is as follows:<br>
                                                Without N.O.C Rs 2800.00/- (Including Renewal Fee)-<br>With N.O.C Rs
                                                4100.00/-</li>
                                            <li>
                                                <?php
                                                $id = 2;
                                                $sql = "SELECT download_file FROM download_master WHERE download_id = '$id' AND download_status = 'Active'";
                                                $result = mysqli_query($conn, $sql);
                                                $downloadLink = '';

                                                if ($result && mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    $downloadLink = 'admin/images/download/' . $row['download_file'];
                                                }
                                                ?>
                                                The Candidate should register his/her name in the State Dental Council
                                                within 6 months of completing the BDS Degree. If not, they have to
                                                submit the Affidavit in explaining the reason for the delay in
                                                Registration with other required documents for Registration.
                                                <a href="assets/source/Affidavit_format.docx" target="_blank">Affidavit
                                                    Format</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>

                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"></div>
                                    <h4> For Provisional Registration</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <ul class="list-style-one clearfix">
                                            <li>
                                                <?php
                                                $id = 3;
                                                $sql = "SELECT download_file FROM download_master WHERE download_id = '$id' AND download_status = 'Active'";
                                                $result = mysqli_query($conn, $sql);
                                                $downloadLink = '';

                                                if ($result && mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    $downloadLink = 'admin/images/download/' . $row['download_file'];
                                                }
                                                ?>
                                                Application for provisional registration.<a
                                                    href="<?php echo $downloadLink ?>" target="_blank">
                                                    click here to download.</a>
                                            </li>
                                            <li>Copies of final year Part-I & Part-II Marks cards with originals.</li>
                                            <li>Copy of SSLC or other certificate containing the date of birth with
                                                original.</li>
                                            <li>Two passport size photos and names entered on the backside of the
                                                photos.</li>
                                            <li>Swiping Card or
                                                UPI or through Demand Draft for Rs.500/- taken in the name of 'Karnataka
                                                State
                                                Dental
                                                Council, Bangalore', Payable at Bangalore.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"></div>
                                    <h4>For Registration of MDS / Other PG / Diploma</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <ul class="list-style-one clearfix">
                                            <li>An application addressed to The Registrar, KSDC Bangalore</li>
                                            <li>Originals and Xerox Copies of the MDS marks card & provisional degree
                                                certificate or convocation certificate /Xerox Copy of MDS/PG/DIPLOMA
                                                Recognition Letter from Ministry Of Health.</li>
                                            <li>Original registration certificate issued by this council.</li>
                                            <li>Swiping Card or
                                                UPI or through Demand Draft for Rs.1000/- drawn in the name of
                                                'Karnataka State
                                                Dental
                                                Council, Bangalore', Payable at Bangalore. If the BDS Registration
                                                Certificate is laminated or bigger than A4 Size , an additional amount
                                                of Rs
                                                500/- should be paid towards Duplicate Certificate.</li>
                                            <li>
                                                <?php
                                                $id = 4;
                                                $sql = "SELECT download_file FROM download_master WHERE download_id = '$id' AND download_status = 'Active'";
                                                $result = mysqli_query($conn, $sql);
                                                $downloadLink = '';

                                                if ($result && mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    $downloadLink = 'admin/images/download/' . $row['download_file'];
                                                }
                                                ?>The Candidate should register his/her name in the State Dental
                                                Council
                                                within
                                                6 months of completing the MDS Degree. If not, they have to submit the
                                                Affidavit in explaining the reason for the delay in Registration with
                                                other
                                                required documents for Registration. <a
                                                    href="<?php echo $downloadLink ?>" target="_blank">Affidavit
                                                    Format</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"></div>
                                    <h4>For Good Standing Certificate</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <ul class="list-style-one clearfix">
                                            <li>An application to be sent by an email addressed to the asst Registrar’s
                                                email
                                                ID : asst.registrar@ksdc.in, which should contain :
                                                <br>a. Registration number
                                                <br>b. Purpose for which Good standing certificate is needed.
                                                <br>c. Receipt number & date for online payment confirmation for
                                                remittance
                                                of
                                                Rs.1000/- (attach a copy of the receipt).
                                                <br>d. Mention the email ID of the Council/Institution to whom the
                                                certificate
                                                should be sent. KSDC shall send a Good Standing Certificate by email
                                                directly to them. A copy of the same shall be sent to applicant’s
                                                email
                                                ID
                                                for your records.<br>
                                                <br>PLEASE BE ADVISED <span style="background-color: #FFFF00;"> NO
                                                    HARDCOPY</span> OF THE “GOOD STANDING
                                                CERTIFICATE”
                                                SHALL BE
                                                ISSUED HENCEFORTH TO THE OVERSEAS APPLICANTS.
                                                <br>KSDC follows a standard process of sending a <span
                                                    style="background-color: #FFFF00;"> NO
                                                    HARDCOPY</span> of
                                                the
                                                “GOOD STANDING CERTIFCATE” by email only.
                                                <p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"></div>
                                    <h4> For Change of Name</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <ul class="list-style-one clearfix">
                                            <li>The declaration made before the Notary or other competent authority in
                                                changing the name If the Surname Should be Added the Marriage
                                                Certificate
                                                Should be Produced.</li>
                                            <li>The publication to that effect made in the News paper.</li>
                                            <li>Original Registration Certificate to effect the change of name with a
                                                request
                                                letter addressed to the Registrar KSDC Bangalore.</li>
                                            <li>Swiping Card or
                                                UPI or through Demand Draft for Rs.200/- taken in the name of 'Karnataka
                                                State
                                                Dental
                                                Council, Bangalore', Payable at Bangalore.</li>
                                            <li>Duly amended degree certificate and marks sheet</li>
                                        </ul>

                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"></div>
                                    <h4>For Renewal of Registration</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <ul class="list-style-one clearfix">

                                            <p
                                                style="font-size: 16px;line-height: 30px;color: #222;font-weight: 500;margin-bottom: 19px;">
                                                Under the act Renewal of Registration before the end of 31st December
                                                of the year is mandatory.</p>
                                            <h6>Manual Process:</h6>
                                            <p
                                                style="font-size: 16px;line-height: 30px;color: #222;font-weight: 500;margin-bottom: 19px;margin-top: 15px;">
                                                A Request letter addressed to the Registrar, KSDC Bengaluru by
                                                mentioning
                                                the
                                                name, registration number and a Demand Draft (DD) for Rs.200/- taken in
                                                the
                                                name of 'Karnataka State Dental Council, Bangalore', Payable at
                                                Bangalore.<br>

                                                Once the letter is sent to the KSDC office, your registration shall be
                                                renewed manually and renewal certificate will be sent to your registered
                                                address within 15 working days.
                                                <?php
                                                $id = 5;
                                                $sql = "SELECT download_file FROM download_master WHERE download_id = '$id' AND download_status = 'Active'";
                                                $result = mysqli_query($conn, $sql);
                                                $downloadLink = '';

                                                if ($result && mysqli_num_rows($result) > 0) {
                                                    $row = mysqli_fetch_assoc($result);
                                                    $downloadLink = 'admin/images/download/' . $row['download_file'];
                                                }
                                                ?>
                                                <br><a href="<?php echo $downloadLink ?>" target="_blank">
                                                    Application for Renewal - Click
                                                    here to download</a>
                                            </p>

                                            <h6>Online Renewal of Registration:</h6>

                                            <p
                                                style="font-size: 16px;line-height: 30px;color: #222;font-weight: 500;margin-bottom: 19px;margin-top: 15px;">
                                                You can login to Karnataka State Dental Council website with your
                                                practitioner’s login and you can make the payment via credit/debit card
                                                or
                                                other payment options including most of the valet payments.
                                                <br>Once the payment is done, the system allows one day to update your
                                                renewal status, wherein you will be able to download the receipt and
                                                renewal
                                                certificate.
                                            </p>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"></div>
                                    <h4>For Restoration of Registration</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <ul class="list-style-one clearfix">
                                            <li>Renewal of Registration before 31st December of the year for the next
                                                year
                                                and before 31 march for the current year is mandatory under the act. If,
                                                failed to renew the Registration within the limit, a request letter to
                                                the
                                                Registrar, KSDC , Bangalore, Shall be addressed to restore the name by
                                                mentioning the name, registration number,with a Demand Draft (DD) for
                                                restoration fee of Rs. 250/- along with the renewal fee of Rs.200/-per
                                                annum
                                                for the consecutive pending years to be taken in the name of 'Karnataka
                                                State Dental Council', Payable at Bangalore or by Swiping the
                                                Debit/Credit
                                                Card at the office.</li>
                                            <li>A Self Addressed Postal cover to send the renewal certificate by post.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"></div>
                                    <h4>For No Objection Certificate</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <p
                                            style="font-size: 16px;line-height: 30px;color: #222;font-weight: 500;margin-bottom: 19px;">
                                            The following Documents have to be furnished either personally or
                                            through
                                            authorized person:</p>
                                        <ul class="list-style-one clearfix">
                                            <li>Original Registration Certificate issued by K.S.D.C.</li>
                                            <li>Swiping Card or
                                                UPI or through Demand Draft for Rs. 1500/- (if renewed up to date) fvg.
                                                Karnataka State
                                                Dental Council, payable at Bangalore.</li>
                                            <li>Letter mentioning the name of the State to which the NOC required.</li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"></div>
                                    <h4>College Information Template</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <p
                                            style="font-size: 16px;line-height: 30px;color: #222;font-weight: 500;margin-bottom: 19px;">
                                            <?php
                                            $id = 6;
                                            $sql = "SELECT download_file FROM download_master WHERE download_id = '$id' AND download_status = 'Active'";
                                            $result = mysqli_query($conn, $sql);
                                            $downloadLink = '';

                                            if ($result && mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);
                                                $downloadLink = 'admin/images/download/' . $row['download_file'];
                                            }
                                            ?>
                                            <a href="<?php echo $downloadLink ?>" target="_blank">Click here
                                                to download </a>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"></div>
                                    <h4>Application form to conduct cde programs</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <p
                                            style="font-size: 16px;line-height: 30px;color: #222;font-weight: 500;margin-bottom: 19px;">
                                            <?php
                                            $id = 7;
                                            $sql = "SELECT download_file FROM download_master WHERE download_id = '$id' AND download_status = 'Active'";
                                            $result = mysqli_query($conn, $sql);
                                            $downloadLink = '';

                                            if ($result && mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);
                                                $downloadLink = 'admin/images/download/' . $row['download_file'];
                                            }
                                            ?>
                                            <a href="<?php echo $downloadLink ?>" target="_blank">Click here to
                                                download </a>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"></div>
                                    <h4>Format to send Delegates/Participants list</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <p
                                            style="font-size: 16px;line-height: 30px;color: #222;font-weight: 500;margin-bottom: 19px;">
                                            <?php
                                            $id = 8;
                                            $sql = "SELECT download_file FROM download_master WHERE download_id = '$id' AND download_status = 'Active'";
                                            $result = mysqli_query($conn, $sql);
                                            $downloadLink = '';

                                            if ($result && mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);
                                                $downloadLink = 'admin/images/download/' . $row['download_file'];
                                            }
                                            ?>
                                            <a href="<?php echo $downloadLink ?>" target="_blank">Click here to
                                                download </a>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"></div>
                                    <h4>Application for verification </h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <p
                                            style="font-size: 16px;line-height: 30px;color: #222;font-weight: 500;margin-bottom: 19px;">
                                            For
                                            verification of documents issued by KSDC Please arrange to send rs 500
                                            through DD in favor of KARNATKA STATE DENTAL COUNCIL, Bangalore along with
                                            individual documents.</p>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"></div>
                                    <h4>For Registration of DH/DM/DORA/DIPLOMA IN DH/DM</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <p
                                            style="font-size: 16px;line-height: 30px;color: #222;font-weight: 500;margin-bottom: 19px;">
                                            <?php
                                            $id = 9;
                                            $sql = "SELECT download_file FROM download_master WHERE download_id = '$id' AND download_status = 'Active'";
                                            $result = mysqli_query($conn, $sql);
                                            $downloadLink = '';

                                            if ($result && mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);
                                                $downloadLink = 'admin/images/download/' . $row['download_file'];
                                            }
                                            ?>
                                            <a href="<?php echo $downloadLink ?>" target="_blank">Click
                                                here to download</a>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"></div>
                                    <h4>Proforma for Affidavit</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <p
                                            style="font-size: 16px;line-height: 30px;color: #222;font-weight: 500;margin-bottom: 19px;">
                                            <?php
                                            $id = 10;
                                            $sql = "SELECT download_file FROM download_master WHERE download_id = '$id' AND download_status = 'Active'";
                                            $result = mysqli_query($conn, $sql);
                                            $downloadLink = '';

                                            if ($result && mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);
                                                $downloadLink = 'admin/images/download/' . $row['download_file'];
                                            }
                                            ?>
                                            <a href="<?php echo $downloadLink ?>" target="_blank">Click here to
                                                download</a>
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"></div>
                                    <h4>Revised Regulation in Dental Radiology Practice</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <p
                                            style="font-size: 16px;line-height: 30px;color: #222;font-weight: 500;margin-bottom: 19px;">
                                            Prohibition of 328 Fixed Dose Combination (FDC) of drugs with effect from
                                            07.09.2018
                                            <br>
                                            The Registered Dental Practitioners are hereby informed that the
                                            following drugs has been banned and to stop prescribing these Fixed Dose
                                            Combination of
                                            Drugs with
                                            immediate Effect:<br>
                                            <?php
                                            $id = 11;
                                            $sql = "SELECT download_file FROM download_master WHERE download_id = '$id' AND download_status = 'Active'";
                                            $result = mysqli_query($conn, $sql);
                                            $downloadLink = '';

                                            if ($result && mysqli_num_rows($result) > 0) {
                                                $row = mysqli_fetch_assoc($result);
                                                $downloadLink = 'admin/images/download/' . $row['download_file'];
                                            }
                                            ?>
                                            <a href="<?php echo $downloadLink ?>" target="_blank">Click
                                                here to
                                                download the drugs list</a>
                                        </p>
                                    </div>
                                </div>

        </section>
        <?php require_once 'include/footer.php'; ?>
    </div>
    <?php require_once 'include/footer-js.php'; ?>
</body>

</html>