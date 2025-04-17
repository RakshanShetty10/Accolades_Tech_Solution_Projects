<?php
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
require_once('admin/vendor-qr/autoload.php');
$options = new QROptions(
  [
    'eccLevel' => QRCode::ECC_L,
    'outputType' => QRCode::OUTPUT_MARKUP_SVG,
    'version' => 5,
  ]
);

session_start();
require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $site_full;?> - Practitioner Profile</title>
    <?php require_once 'include/header-link.php';?>
    <link href="assets/css/module-css/sidebar.css" rel="stylesheet">
    <link href="assets/css/profile-design.css" rel="stylesheet">
    <link href="assets/css/profile-banner.css" rel="stylesheet">
    <style>
    .nav-tabs .nav-link.active,
    .nav-tabs .nav-item.show .nav-link {
        color: white;
        background-color: #192735;
    }

    .nav-tabs .nav-link {
        color: #192735;
        background: #f7f7f7;
        margin-right: 2px;
    }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <?php
        if (empty($_SESSION['_id'])) {
            
            echo "<script>swal.fire({text: 'Kindly login to proceed!',type: 'error',icon: 'error'}).then(function(){window.location = 'login.php';});</script>";
        } else{

            $id = $_SESSION['_id'];
            $id_param = base64_encode(urlencode($id));

            $qrcode = (new QRCode($options))->render($site_url.'dentist-profile.php?source='.$id_param);

            if (isset($_POST['address_request'])) {
                
                $description = addslashes(trim($_POST['description']));

                if ($_FILES["image"]["size"] <= 2097152) {
                    
                    $imagePath = time() . "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

                    if (move_uploaded_file($_FILES['image']['tmp_name'], "admin/images/address/" . $imagePath)) {

                        $date = date('Y-m-d H:i:s');
                        
                        if (mysqli_query($conn, "INSERT INTO address_change (practitioner_id, change_description, change_file, change_created_on, change_status) VALUES ('$id', '$description', '$imagePath', '$date', 'Initiated')")) {
                            
                            echo "<script>
                                Swal.fire({
                                    text: 'Your request has been sent successfully!',
                                    icon: 'success'
                                }).then(function() {
                                    window.location = 'profile.php';
                                });
                            </script>";
                        } else {
                            echo "<script>
                                Swal.fire({
                                    text: 'Unable to process your request!',
                                    icon: 'error'
                                });
                            </script>";
                        }
                    } else {
                        echo "<script>
                            Swal.fire({
                                text: 'Unable to process your request!',
                                icon: 'error'
                            });
                        </script>";
                    }
                } else {
                    echo "<script>
                        Swal.fire({
                            text: 'Your file is too large!',
                            icon: 'error'
                        });
                    </script>";
                }
            }

            if (isset($_POST['update_professional'])) {

                $date = date('Y-m-d H:i:s');

                $secondary_number = addslashes(trim($_POST['secondary_number']));
                $mobile_number = addslashes(trim($_POST['mobile_number']));
                $residential_country = addslashes(trim($_POST['residential_country']));
                $postal_code = addslashes(trim($_POST['postal_code']));
                $residential_state = addslashes(trim($_POST['residential_state']));
                $residential_city = addslashes(trim($_POST['residential_city']));
                $address_line1 = addslashes(trim($_POST['address_line1']));
                $address_line2 = addslashes(trim($_POST['address_line2']));
                $type = 'Professional';
                $updated_by = 'Self';

                $sql = "UPDATE practitioner_address SET
                    practitioner_address_line1 = ?,
                    practitioner_address_line2 = ?,
                    practitioner_address_city = ?,
                    state_name = ?,
                    practitioner_address_pincode = ?,
                    country_name = ?,
                    practitioner_address_phoneno = ?,
                    practitioner_address_last_updated_by = ?,
                    practitioner_address_last_updated_on = ?,
                    practitioner_address_secondary_phoneno = ?
                    WHERE practitioner_id = ?
                    AND practitioner_address_type = ?";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param($stmt, "ssssssssssss", $address_line1, $address_line2, 
                $residential_city, 
                $residential_state, $postal_code, $residential_country, $mobile_number, 
                $updated_by, $date, 
                $secondary_number, $id, $type);
            
            if (mysqli_stmt_execute($stmt)) {

                echo "<script>
                                Swal.fire({
                                    text: 'Your address updated successfully!',
                                    icon: 'success'
                                }).then(function() {
                                    window.location = 'profile.php';
                                });
                            </script>";
            } else {

                echo "<script>
                            Swal.fire({
                                text: 'Unable to process your request!',
                                icon: 'error'
                            });
                        </script>";
            }

    }
    ?>
    <div class="boxed_wrapper ltr">

        <?php require_once 'include/pre-loader.php';?>
        <?php require_once 'include/header.php';?>
        
        <!-- Profile Banner -->
        <div class="profile-banner">
            <div class="banner-pattern"></div>
            <h2 class="banner-title">Your Profile</h2>
            <div class="banner-subtitle">View and update your professional information</div>
            <div class="banner-wave"></div>
        </div>

        <section class="sidebar-page-container profile-container pt_100 pb_100">
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
                                            <h6><a href="profile.php">My Profile</a></h6>
                                        </li>
                                        <li><a href="receipts.php">Receipts</a></li>
                                        <li><a href="payments.php">Payments</a></li>
                                        <!-- <li><a href="ksdc-journal.php">KSDC Journal</a></li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-9 col-md-12 col-sm-12 content-side pl_40">
                        <div class="blog-standard-content content-area">
                            <div class="news-block-three">
                                <div class="inner-box">
                                    <div class="lower-content">
                                        <nav>
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <button class="nav-link active" id="nav-one-tab" data-bs-toggle="tab"
                                                    data-bs-target="#nav-one" type="button" role="tab"
                                                    aria-controls="nav-one" aria-selected="true">Personal Info</button>
                                                <button class="nav-link" id="nav-two-tab" data-bs-toggle="tab"
                                                    data-bs-target="#nav-two" type="button" role="tab"
                                                    aria-controls="nav-two" aria-selected="false">Contact
                                                    Info</button>
                                                <button class="nav-link" id="nav-three-tab" data-bs-toggle="tab"
                                                    data-bs-target="#nav-three" type="button" role="tab"
                                                    aria-controls="nav-three" aria-selected="false">Educational
                                                    Info</button>
                                                <button class="nav-link" id="nav-four-tab" data-bs-toggle="tab"
                                                    data-bs-target="#nav-four" type="button" role="tab"
                                                    aria-controls="nav-four" aria-selected="false">Remarks</button>
                                                
                                                <button class="nav-link" id="nav-six-tab" data-bs-toggle="tab"
                                                    data-bs-target="#nav-six" type="button" role="tab"
                                                    aria-controls="nav-six" aria-selected="false">Sign/LTI</button>
                                                <button class="nav-link" id="nav-seven-tab" data-bs-toggle="tab"
                                                    data-bs-target="#nav-seven" type="button" role="tab"
                                                    aria-controls="nav-seven" aria-selected="false">Change of
                                                    Address</button>
                                            </div>
                                        </nav>
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="nav-one" role="tabpanel"
                                                aria-labelledby="nav-one-tab" tabindex="0">
                                                <?php
                                                    $resPractitioner = mysqli_query($conn, "SELECT * FROM practitioner WHERE practitioner_id = '$id'");

                                                    if (mysqli_num_rows($resPractitioner) > 0) {

                                                        $rowPractitioner = mysqli_fetch_assoc($resPractitioner);
                                                        ?>
                                                <div class="row mt_40 info-section">
                                                    <h5 class="mb_20">Personal Information</h5>
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Registration
                                                                number</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $rowPractitioner['practitioner_username']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Registration
                                                                Date</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo date_format(date_create($rowPractitioner['registration_date']),'d M Y'); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Title</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $rowPractitioner['practitioner_title']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Name</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $rowPractitioner['practitioner_name']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Change of
                                                                Name</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $rowPractitioner['practitioner_change_of_name']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Gender</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $rowPractitioner['practitioner_gender']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Father
                                                                Name</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $rowPractitioner['practitioner_spouse_name']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Birth Date</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo date_format(date_create($rowPractitioner['practitioner_birth_date']), 'd M Y'); ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Birth Place</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $rowPractitioner['practitioner_birth_place']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Nationality</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $rowPractitioner['practitioner_nationality']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Eligibility to
                                                                vote</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $rowPractitioner['vote_status']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Email Address</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $rowPractitioner['practitioner_email_id']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Mobile Number</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $rowPractitioner['practitioner_mobile_number']; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            </div>

                                            <div class="tab-pane fade" id="nav-two" role="tabpanel"
                                                aria-labelledby="nav-two-tab" tabindex="0">
                                                <?php
                                                    $resAddress = mysqli_query($conn, "SELECT * FROM practitioner_address
                                                        WHERE practitioner_address_type = 'Residential' AND practitioner_address_status = 'Active' AND practitioner_id = '$id'");
                                                    if (mysqli_num_rows($resAddress) > 0) {

                                                        $resAddress = mysqli_fetch_assoc($resAddress);
                                                    ?>
                                                <div class="row mt_40 info-section">
                                                    <h5 class="mb_20">Residential Information</h5>
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Residential
                                                                Address</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $resAddress['practitioner_address_line1'] . ' ' . $resAddress['practitioner_address_line2']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Residential
                                                                City/District</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $resAddress['practitioner_address_city']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Rural/Urban</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $resAddress['practitioner_address_category']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Residential
                                                                State</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $resAddress['state_name']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Residential Postal
                                                                Code</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $resAddress['practitioner_address_pincode']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Residential
                                                                Country</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $resAddress['country_name']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Residential
                                                                Phone</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $resAddress['practitioner_address_phoneno']; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php }?>

                                                <?php
                                                    $resAddress2 = mysqli_query($conn, "SELECT * FROM practitioner_address
                                                        WHERE practitioner_address_type = 'Professional' AND practitioner_address_status = 'Active' 
                                                        AND practitioner_id = '$id'");
                                                    if (mysqli_num_rows($resAddress2) > 0) {

                                                        $resAddress2 = mysqli_fetch_assoc($resAddress2);
                                                    ?>
                                                <div class="row mt_40 info-section">
                                                    <div class="col-lg-5">
                                                        <h5 class="mb_20">Professional Information</h5>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <button class="theme-btn btn-three" data-bs-toggle="modal"
                                                            data-bs-target="#editProfessional"
                                                            style="border: 1px solid var(--theme-color);padding-left:15px;padding-right:15px;padding-top:5px;padding-bottom:5px;font-size:14px">Edit&nbsp;
                                                            <i style="font-size: 12px;" class="fa fa-pen"></i></button>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Professional
                                                                Address</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $resAddress2['practitioner_address_line1'] . ' ' . $resAddress2['practitioner_address_line2']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Professional
                                                                City/District</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $resAddress2['practitioner_address_city']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Rural/Urban</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $resAddress2['practitioner_address_category']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Professional
                                                                State</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $resAddress2['state_name']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Professional Postal
                                                                Code</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $resAddress2['practitioner_address_pincode']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Professional
                                                                Country</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $resAddress2['country_name']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Professional
                                                                Phone</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $resAddress2['practitioner_address_phoneno']; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <style>
                                                input {
                                                    width: 100%;
                                                    height: 50px;
                                                    border: 1px solid rgba(229, 229, 229, 1);
                                                    font-size: 16px;
                                                    padding: 0 10px;
                                                    color: #121212;
                                                    transition: all 500ms ease;
                                                }

                                                input:focus {
                                                    border-color: var(--theme-color);
                                                }

                                                .dropdown-content {
                                                    display: none;
                                                    position: absolute;
                                                    background-color: #ffffff;
                                                    min-width: 160px;
                                                    border: 1px solid #ccc;
                                                    z-index: 1;
                                                    max-height: 200px;
                                                    overflow-y: auto;
                                                }

                                                .dropdown-content div {
                                                    padding: 8px 16px;
                                                    cursor: pointer;
                                                    color: black;
                                                }

                                                .dropdown-content div:hover {
                                                    background-color: #f1f1f1;
                                                }
                                                </style>

                                                <div class="modal fade" id="editProfessional" tabindex="-1"
                                                    aria-labelledby="editLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-center modal-lg">
                                                        <form method="POST">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="editLabel">Edit
                                                                        Professional Information</h1>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Address Line
                                                                                1<span class="text-danger">
                                                                                    *</span></label>
                                                                            <input autocomplete="off" type="text"
                                                                                value="<?php echo $resAddress2['practitioner_address_line1']; ?>"
                                                                                name="address_line1" maxlength="1000"
                                                                                title="Please enter a valid address."
                                                                                required>
                                                                        </div>
                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Address Line
                                                                                2<span class="text-danger">
                                                                                    *</span></label>
                                                                            <input autocomplete="off" type="text"
                                                                                value="<?php echo $resAddress2['practitioner_address_line2']; ?>"
                                                                                name="address_line2" maxlength="1000"
                                                                                title="Please enter a valid address."
                                                                                required>
                                                                        </div>

                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Professional
                                                                                Phone<span class="text-danger">
                                                                                    *</span></label>
                                                                            <input autocomplete="off" type="text"
                                                                                value="<?php echo $resAddress2['practitioner_address_phoneno']; ?>"
                                                                                required name="mobile_number"
                                                                                maxlength="10" pattern="[0-9]{10}"
                                                                                title="Accept 10 digit number">
                                                                        </div>
                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Secondary
                                                                                Number</label>
                                                                            <input autocomplete="off" type="text"
                                                                                value="<?php echo $resAddress2['practitioner_address_secondary_phoneno']; ?>"
                                                                                name="secondary_number" maxlength="12"
                                                                                pattern="[0-9]{6,12}"
                                                                                title="Accept 6 to 12 digit number">
                                                                        </div>
                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Professional
                                                                                Postal Code<span class="text-danger">
                                                                                    *</span></label>
                                                                            <input autocomplete="off" type="text"
                                                                                required
                                                                                value="<?php echo $resAddress2['practitioner_address_pincode']; ?>"
                                                                                name="postal_code" maxlength="6"
                                                                                pattern="[0-9]{6}"
                                                                                title="Accept 6 digit number">
                                                                        </div>
                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Professional
                                                                                District<span class="text-danger">
                                                                                    *</span></label>
                                                                            <input autocomplete="off" type="text"
                                                                                value="<?php echo $resAddress2['practitioner_address_city']; ?>"
                                                                                name="residential_city" maxlength="1000"
                                                                                required
                                                                                title="Please enter a valid dictrict.">
                                                                        </div>
                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Professional
                                                                                State<span class="text-danger">
                                                                                    *</span></label>
                                                                            <input name="residential_state" required
                                                                                autocomplete="off"
                                                                                value="<?php echo $resAddress2['state_name']; ?>"
                                                                                title="Please enter a valid state."
                                                                                id="stateInputProfessional" />
                                                                            <div id="dropdown-stateProfessional"
                                                                                class="dropdown-content"></div>
                                                                        </div>
                                                                        <div class="col-xl-6 mb-3">
                                                                            <label class="form-label">Professional
                                                                                Country<span class="text-danger">
                                                                                    *</span></label>
                                                                            <input required autocomplete="off"
                                                                                value="<?php echo $resAddress2['country_name']; ?>"
                                                                                name="residential_country"
                                                                                id="countryInputProfessional"
                                                                                title="Please enter a valid country." />
                                                                            <div id="dropdown-countryProfessional"
                                                                                class="dropdown-content"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="theme-btn btn-three"
                                                                        style="border: 1px solid var(--theme-color);padding-left:21px;padding-right:21px;padding-top:10px;padding-bottom:10px;font-size:15px"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" name="update_professional"
                                                                        class="theme-btn btn-three"
                                                                        style="background-color: var(--theme-color);padding-left:21px;padding-right:21px;padding-top:10px;padding-bottom:10px;font-size:15px;"><span
                                                                            style="color: #f7f7f7;">Update</span></button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <?php }?>
                                            </div>
                                            <div class="tab-pane fade" id="nav-three" role="tabpanel"
                                                aria-labelledby="nav-three-tab" tabindex="0">

                                                <div class="row mt_40">
                                                    <h5 style="font-weight: 500;" class="mb_20">Education Information
                                                    </h5>
                                                </div>
                                                <?php
                                                    $resEducation = mysqli_query($conn, "SELECT * FROM education_information  
                                                    WHERE practitioner_id = '$id' AND education_status = 'Active' ORDER BY education_id DESC");

                                                    if (mysqli_num_rows($resEducation) > 0) {

                                                        $i = 0;

                                                        while ($rowEducation = mysqli_fetch_assoc($resEducation)) {
                                                            if($i!=0){
                                                                echo "<hr>";
                                                            }
                                                        ?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Degree</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $rowEducation['education_name']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">Year Of
                                                                Passing</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $rowEducation['education_month_of_passing'] . ' ' . $rowEducation['education_year_of_passing']; ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">College Name</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php $resCollege = mysqli_query($conn, "SELECT college_name 
                                                                    FROM college_master WHERE college_status = 'Active' AND college_id = '$rowEducation[college_id]'");
                                                                if (mysqli_num_rows($resCollege) > 0) { $resCollege = mysqli_fetch_assoc($resCollege); echo $resCollege['college_name']; } ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-5 col-form-label">University
                                                                Name</label>
                                                            <div class="col-sm-7">
                                                                <span
                                                                    class="form-control-plaintext"><?php $resUniversity = mysqli_query($conn, "SELECT university_name FROM university_master WHERE university_status = 'Active' AND university_id = '$rowEducation[university_id]'");
                                                                if (mysqli_num_rows($resUniversity) > 0) { $resUniversity = mysqli_fetch_assoc($resUniversity); echo $resUniversity['university_name']; } ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php $i++; }}?>
                                            </div>
                                            <div class="tab-pane fade" id="nav-four" role="tabpanel"
                                                aria-labelledby="nav-four-tab" tabindex="0">

                                                <div class="row mt_40">
                                                    <h5 style="font-weight: 500;" class="mb_20">Remarks
                                                    </h5>
                                                </div>
                                                <?php
                                                    $resRemarks = mysqli_query($conn, "SELECT practitioner_remarks FROM practitioner_remarks  
                                                    WHERE practitioner_id = '$id' AND practitioner_status = 'Active' ORDER BY practitioner_remarks_id  DESC");

                                                    if (mysqli_num_rows($resRemarks) > 0) {

                                                        $i = 0;

                                                        while ($rowRemarks = mysqli_fetch_assoc($resRemarks)) {
                                                            if($i!=0){
                                                                echo "<hr>";
                                                            }
                                                        ?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <span
                                                                    class="form-control-plaintext"><?php echo $rowRemarks['practitioner_remarks']; ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php $i++; }}?>
                                            </div>
                                            
                                            <div class="tab-pane fade" id="nav-six" role="tabpanel"
                                                aria-labelledby="nav-six-tab" tabindex="0">

                                                <div class="row mt_40">
                                                    <h5 style="font-weight: 500;" class="mb_20">Sign/LTI
                                                    </h5>
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="form-group row">
                                                            <div class="col-sm-3 border">
                                                                <img style="height:200px;padding:15px;width:100%"
                                                                    src="<?php if (!empty($rowPractitioner['practitioner_signature'])) {
                                                                                echo 'admin/images/dentist/' . $rowPractitioner['practitioner_signature'];
                                                                            }else{echo 'admin/images/other/dentist.png';} ?>" alt="Signature">
                                                                <h6 class="text-center mb_15">Signature</h6>
                                                            </div>
                                                            <div class="col-sm-3 border">
                                                                <img style="height:200px;padding:15px;width:100%"
                                                                    src="<?php if (!empty($rowPractitioner['practitioner_thumb'])) {
                                                                                echo 'admin/images/dentist/' . $rowPractitioner['practitioner_thumb'];
                                                                            }else{echo 'admin/images/other/dentist.png';} ?>" alt="Thumb">

                                                                <h6 class="text-center mb_15">Thumb</h6>
                                                            </div>
                                                            <div class="col-sm-3 border">
                                                                <img src="<?= $qrcode ?>" alt="Barcode"
                                                                    style="margin:0px;padding:0px">
                                                                <h6 class="text-center mb_15">Barcode</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="nav-seven" role="tabpanel"
                                                aria-labelledby="nav-seven-tab" tabindex="0">

                                                <div class="row mt_40">
                                                    <h5 style="font-weight: 500;" class="mb_20">Change of Address
                                                    </h5>
                                                    <form method="post" class="col-lg-12 col-md-12 col-sm-12"
                                                        enctype="multipart/form-data">
                                                        <div class="form-group row">
                                                            <div class="col-sm-12"><span
                                                                    class="text-danger">Note:</span>
                                                                To update
                                                                or correct your information such as name, phone number,
                                                                or address,
                                                                please submit the detailed description and a valid proof
                                                                document, such
                                                                as an Aadhar card.</div>
                                                            <div class="col-sm-8 mt-4">
                                                                <label class="form-label">Description</label>
                                                                <textarea class="w-100 p-2" required name="description"
                                                                    rows="4"
                                                                    style="border: #e1e1e3 1px solid;"></textarea>
                                                            </div>
                                                            <div class="col-sm-4"></div>
                                                            <div class="col-sm-8 mt-2">
                                                                <label class="form-label">Proof Attachment</label>
                                                                <input autocomplete="off" type="file" required
                                                                    name="image" title="Please select a file"
                                                                    accept=".jpg, .jpeg, .png, .pdf" class="w-100"
                                                                    style="border: #e1e1e3 1px solid;">
                                                                <small>Attach proof such as Aadhaar card or identity
                                                                    card [should not exceed 2 MB in
                                                                    size].</small>
                                                            </div>
                                                            <div class="col-sm-4"></div>
                                                            <div class="col-sm-6 mt-4">
                                                                <button type="submit" class="theme-btn btn-one"
                                                                    style="padding-left: 20px;padding-right: 20px;padding-top: 10px;padding-bottom: 10px;"
                                                                    name="address_request">Submit</button>
                                                            </div>
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
                </div>
            </div>
        </div>
    </div>
    </section>

    <script>
    function setupAutofill(inputId, dropdownId, fetchUrl, itemHandler) {
        const input = document.getElementById(inputId);
        const dropdownContent = document.getElementById(dropdownId);
        let items = [];

        fetch(fetchUrl)
            .then(response => response.json())
            .then(data => items = data)
            .catch(error => console.error('Error fetching items:', error));

        input.addEventListener('input', function() {
            const value = this.value.toLowerCase();
            dropdownContent.innerHTML = '';

            if (value.length > 0) {
                const filteredItems = items.filter(item => item.toLowerCase().startsWith(value)).slice(0, 5);

                filteredItems.forEach(item => {
                    const div = document.createElement('div');
                    div.textContent = item;
                    div.addEventListener('click', function() {
                        input.value = item;
                        dropdownContent.style.display = 'none';
                    });
                    dropdownContent.appendChild(div);
                });

                dropdownContent.style.display = 'block';
            } else {
                dropdownContent.style.display = 'none';
            }
        });

        document.addEventListener('click', function(event) {
            if (!event.target.closest(`#${inputId}`)) {
                dropdownContent.style.display = 'none';
            }
        });
    }

    setupAutofill('stateInputProfessional', 'dropdown-stateProfessional', 'admin/ajax/state.php');
    setupAutofill('countryInputProfessional', 'dropdown-countryProfessional', 'admin/ajax/country.php');
    </script>



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