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
require_once 'config/config.php';
if (!defined('SITE_URL')) {
    define('SITE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/Accolades_Tech_Solution_Projects/registration_form_KSAHC/');
}
$site_url = SITE_URL;

date_default_timezone_set('Asia/Kolkata');

$id = $_SESSION['user_id'];
$resDentists = mysqli_query($conn, "SELECT * FROM practitioner WHERE practitioner_id = '$id'");
$rowDentists = mysqli_fetch_assoc($resDentists);
$id_param = base64_encode(urlencode($id));
$qrcode = (new QRCode($options))->render($site_url.'dentist-profile.php?source='.$id_param);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $site_full ?? 'Site'; ?> - Dentist Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <style>
    :root {
        --primary-color: #2A5C9D;
        --secondary-color: #4A90E2;
        --accent-color: #33b679;
        --dark-color: #2C3E50;
        --light-color: #f8f9fc;
        --border-color: #e3e6f0;
        --gradient-start: #2A5C9D;
        --gradient-end: #1E3F75;
        --card-shadow: 0 10px 20px rgba(0,0,0,0.08);
        --hover-shadow: 0 15px 30px rgba(0,0,0,0.12);
    }
    
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #F5F7FA;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        padding-top: 60px;
        color: #444;
    }

    .nav-tabs {
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 1rem;
    }

    .nav-tabs .nav-link {
        border: none;
        padding: 15px 25px;
        color: #666;
        font-weight: 500;
        border-radius: 0;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #f7f7f7;
        margin-right: 2px;
    }

    .nav-tabs .nav-link:hover {
        color: var(--primary-color);
        background: rgba(42, 92, 157, 0.05);
    }

    .nav-tabs .nav-link.active {
        color: white;
        background-color: #192735;
    }

    .tab-content {
        padding: 20px 0;
    }

    .section-title {
        color: var(--primary-color);
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border-color);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 500;
        color: #666;
        margin-bottom: 5px;
    }

    .form-control-static {
        color: #333;
        font-size: 16px;
    }

    @media (max-width: 991.98px) {
        .nav-tabs .nav-link {
            padding: 12px 20px;
            font-size: 14px;
        }
    }

    @media (max-width: 767.98px) {
        .nav-tabs .nav-link {
            padding: 10px 15px;
            font-size: 13px;
        }
        .tab-content {
            padding: 15px;
        }
    }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php
    if (empty($_SESSION['user_id'])) {
        echo "<script>Swal.fire({text: 'Kindly login to proceed!', icon: 'error'}).then(function(){window.location = 'login.php';});</script>";
    } else {
        if (isset($_POST['address_request'])) {
            $description = addslashes(trim($_POST['description']));
            if ($_FILES["image"]["size"] <= 2097152) {
                $imagePath = time() . "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if (move_uploaded_file($_FILES['image']['tmp_name'], "admin/images/address/" . $imagePath)) {
                    $date = date('Y-m-d H:i:s');
                    if (mysqli_query($conn, "INSERT INTO address_change (practitioner_id, change_description, change_file, change_created_on, change_status) VALUES ('$id', '$description', '$imagePath', '$date', 'Initiated')")) {
                        echo "<script>Swal.fire({text: 'Your request has been sent successfully!', icon: 'success'}).then(function(){window.location = 'profile.php';});</script>";
                    } else {
                        echo "<script>Swal.fire({text: 'Unable to process your request!', icon: 'error'});</script>";
                    }
                } else {
                    echo "<script>Swal.fire({text: 'Unable to process your request!', icon: 'error'});</script>";
                }
            } else {
                echo "<script>Swal.fire({text: 'Your file is too large!', icon: 'error'});</script>";
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
                WHERE practitioner_id = ? AND practitioner_address_type = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssssssss", $address_line1, $address_line2, 
                $residential_city, $residential_state, $postal_code, $residential_country, $mobile_number, 
                $updated_by, $date, $secondary_number, $id, $type);
            
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>Swal.fire({text: 'Your address updated successfully!', icon: 'success'}).then(function(){window.location = 'profile.php';});</script>";
            } else {
                echo "<script>Swal.fire({text: 'Unable to process your request!', icon: 'error'});</script>";
            }
        }
    ?>
    <div class="boxed_wrapper ltr">
        <section class="sidebar-page-container pt-5 pb-5 border-top">
            <div class="auto-container">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-12 col-sm-12 sidebar-side">
                        <div class="blog-sidebar ml-3">
                            <div class="sidebar-widget category-widget">
                                <div class="widget-content">
                                    <ul class="category-list clearfix">
                                        <li><a href="welcome.php">Welcome</a></li>
                                        <li><h6><a href="profile.php">My Profile</a></h6></li>
                                        <li><a href="receipts.php">Receipts</a></li>
                                        <li><a href="payments.php">Payments</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-9 col-md-12 col-sm-12 content-side pl-4">
                        <div class="blog-standard-content">
                            <div class="news-block-three">
                                <div class="inner-box">
                                    <div class="lower-content">
                                        <nav>
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <button class="nav-link active" id="nav-one-tab" data-bs-toggle="tab" data-bs-target="#nav-one" type="button" role="tab" aria-controls="nav-one" aria-selected="true">Personal Info</button>
                                                <button class="nav-link" id="nav-two-tab" data-bs-toggle="tab" data-bs-target="#nav-two" type="button" role="tab" aria-controls="nav-two" aria-selected="false">Contact Info</button>
                                                <button class="nav-link" id="nav-three-tab" data-bs-toggle="tab" data-bs-target="#nav-three" type="button" role="tab" aria-controls="nav-three" aria-selected="false">Educational Info</button>
                                                <button class="nav-link" id="nav-four-tab" data-bs-toggle="tab" data-bs-target="#nav-four" type="button" role="tab" aria-controls="nav-four" aria-selected="false">Remarks</button>
                                                <button class="nav-link" id="nav-five-tab" data-bs-toggle="tab" data-bs-target="#nav-five" type="button" role="tab" aria-controls="nav-five" aria-selected="false">CDE Points</button>
                                                <button class="nav-link" id="nav-six-tab" data-bs-toggle="tab" data-bs-target="#nav-six" type="button" role="tab" aria-controls="nav-six" aria-selected="false">Sign/LTI</button>
                                                <button class="nav-link" id="nav-seven-tab" data-bs-toggle="tab" data-bs-target="#nav-seven" type="button" role="tab" aria-controls="nav-seven" aria-selected="false">Change of Address</button>
                                            </div>
                                        </nav>
                                        
                                        <div class="tab-content profile-tab-content" id="profileTabsContent">
                                            <div class="tab-pane fade show active" id="nav-one" role="tabpanel" aria-labelledby="nav-one-tab">
                                                <h5 class="section-title">Personal Information</h5>
                                                <?php
                                                    $resDentists = mysqli_query($conn, "SELECT * FROM practitioner WHERE practitioner_id = '$id'");
                                                    if (mysqli_num_rows($resDentists) > 0) {
                                                        $rowDentists = mysqli_fetch_assoc($resDentists);
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-6"><div class="form-group"><label>Registration Number</label><p class="form-control-static"><?php echo $rowDentists['practitioner_username']; ?></p></div></div>
                                                    <div class="col-md-6"><div class="form-group"><label>Registration Date</label><p class="form-control-static"><?php echo date_format(date_create($rowDentists['registration_date']),'d M Y'); ?></p></div></div>
                                                    <div class="col-md-6"><div class="form-group"><label>Title</label><p class="form-control-static"><?php echo $rowDentists['practitioner_title']; ?></p></div></div>
                                                    <div class="col-md-6"><div class="form-group"><label>Name</label><p class="form-control-static"><?php echo $rowDentists['practitioner_name']; ?></p></div></div>
                                                    <div class="col-md-6"><div class="form-group"><label>Change of Name</label><p class="form-control-static"><?php echo $rowDentists['practitioner_change_of_name']; ?></p></div></div>
                                                    <div class="col-md-6"><div class="form-group"><label>Gender</label><p class="form-control-static"><?php echo $rowDentists['practitioner_gender']; ?></p></div></div>
                                                    <div class="col-md-6"><div class="form-group"><label>Father Name</label><p class="form-control-static"><?php echo $rowDentists['practitioner_spouse_name']; ?></p></div></div>
                                                    <div class="col-md-6"><div class="form-group"><label>Birth Date</label><p class="form-control-static"><?php echo date_format(date_create($rowDentists['practitioner_birth_date']), 'd M Y'); ?></p></div></div>
                                                    <div class="col-md-6"><div class="form-group"><label>Birth Place</label><p class="form-control-static"><?php echo $rowDentists['practitioner_birth_place']; ?></p></div></div>
                                                    <div class="col-md-6"><div class="form-group"><label>Nationality</label><p class="form-control-static"><?php echo $rowDentists['practitioner_nationality']; ?></p></div></div>
                                                    <div class="col-md-6"><div class="form-group"><label>Eligibility to vote</label><p class="form-control-static"><?php echo $rowDentists['vote_status']; ?></p></div></div>
                                                    <div class="col-md-6"><div class="form-group"><label>Email Address</label><p class="form-control-static"><?php echo $rowDentists['practitioner_email_id']; ?></p></div></div>
                                                    <div class="col-md-6"><div class="form-group"><label>Mobile Number</label><p class="form-control-static"><?php echo $rowDentists['practitioner_mobile_number']; ?></p></div></div>
                                                </div>
                                                <?php } ?>
                                            </div>

                                            <div class="tab-pane fade" id="nav-two" role="tabpanel" aria-labelledby="nav-two-tab">
                                                <h5 class="section-title">Contact Information</h5>
                                                <?php
                                                    $resAddress = mysqli_query($conn, "SELECT * FROM practitioner_address WHERE practitioner_address_type = 'Residential' AND practitioner_address_status = 'Active' AND practitioner_id = '$id'");
                                                    if (mysqli_num_rows($resAddress) > 0) {
                                                        $resAddress = mysqli_fetch_assoc($resAddress);
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-6"><div class="form-group"><label>Email Address</label><p class="form-control-static"><?php echo $rowDentists['practitioner_email_id']; ?></p></div></div>
                                                    <div class="col-md-6"><div class="form-group"><label>Mobile Number</label><p class="form-control-static"><?php echo $rowDentists['practitioner_mobile_number']; ?></p></div></div>
                                                    <div class="col-md-12"><div class="form-group"><label>Residential Address</label><p class="form-control-static"><?php echo $resAddress['practitioner_address_line1'] . ' ' . $resAddress['practitioner_address_line2']; ?></p></div></div>
                                                    <div class="col-md-4"><div class="form-group"><label>City/District</label><p class="form-control-static"><?php echo $resAddress['practitioner_address_city']; ?></p></div></div>
                                                    <div class="col-md-4"><div class="form-group"><label>State</label><p class="form-control-static"><?php echo $resAddress['state_name']; ?></p></div></div>
                                                    <div class="col-md-4"><div class="form-group"><label>Postal Code</label><p class="form-control-static"><?php echo $resAddress['practitioner_address_pincode']; ?></p></div></div>
                                                </div>
                                                <?php } ?>

                                                <div class="row mt-4">
                                                    <div class="col-lg-5"><h5 class="section-title">Professional Information</h5></div>
                                                    <div class="col-lg-7">
                                                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProfessional">Add New <i class="fa fa-plus"></i></button>
                                                    </div>
                                                    <?php
                                                        $resAddress2 = mysqli_query($conn, "SELECT * FROM practitioner_address WHERE practitioner_address_type = 'Professional' AND practitioner_address_status = 'Active' AND practitioner_id = '$id'");
                                                        if (mysqli_num_rows($resAddress2) > 0) {
                                                            $resAddress2 = mysqli_fetch_assoc($resAddress2);
                                                    ?>
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="form-group row"><label class="col-sm-5 col-form-label">Professional Address</label><div class="col-sm-7"><span class="form-control-plaintext"><?php echo $resAddress2['practitioner_address_line1'] . ' ' . $resAddress2['practitioner_address_line2']; ?></span></div></div>
                                                        <div class="form-group row"><label class="col-sm-5 col-form-label">Professional City/District</label><div class="col-sm-7"><span class="form-control-plaintext"><?php echo $resAddress2['practitioner_address_city']; ?></span></div></div>
                                                        <div class="form-group row"><label class="col-sm-5 col-form-label">Rural/Urban</label><div class="col-sm-7"><span class="form-control-plaintext"><?php echo $resAddress2['practitioner_address_category']; ?></span></div></div>
                                                        <div class="form-group row"><label class="col-sm-5 col-form-label">Professional State</label><div class="col-sm-7"><span class="form-control-plaintext"><?php echo $resAddress2['state_name']; ?></span></div></div>
                                                        <div class="form-group row"><label class="col-sm-5 col-form-label">Professional Postal Code</label><div class="col-sm-7"><span class="form-control-plaintext"><?php echo $resAddress2['practitioner_address_pincode']; ?></span></div></div>
                                                        <div class="form-group row"><label class="col-sm-5 col-form-label">Professional Country</label><div class="col-sm-7"><span class="form-control-plaintext"><?php echo $resAddress2['country_name']; ?></span></div></div>
                                                        <div class="form-group row"><label class="col-sm-5 col-form-label">Professional Phone</label><div class="col-sm-7"><span class="form-control-plaintext"><?php echo $resAddress2['practitioner_address_phoneno']; ?></span></div></div>
                                                        <div class="form-group row"><div class="col-sm-12 text-end"><button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfessional">Edit <i class="fa fa-pen"></i></button></div></div>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            
                                            <div class="tab-pane fade" id="nav-three" role="tabpanel" aria-labelledby="nav-three-tab">
                                                <h5 class="section-title">Educational Information</h5>
                                                <?php
                                                    $resEducation = mysqli_query($conn, "SELECT * FROM education_information WHERE practitioner_id = '$id' AND education_status = 'Active' ORDER BY education_id DESC");
                                                    if (mysqli_num_rows($resEducation) > 0) {
                                                        while ($rowEducation = mysqli_fetch_assoc($resEducation)) {
                                                ?>
                                                <div class="education-item mb-4">
                                                    <div class="row">
                                                        <div class="col-md-6"><h6><?php echo $rowEducation['education_name']; ?></h6><p class="text-muted"><?php $resCollege = mysqli_query($conn, "SELECT college_name FROM college_master WHERE college_status = 'Active' AND college_id = '$rowEducation[college_id]'"); if (mysqli_num_rows($resCollege) > 0) { $resCollege = mysqli_fetch_assoc($resCollege); echo $resCollege['college_name']; } ?></p></div>
                                                        <div class="col-md-3"><p class="text-muted"><?php echo $rowEducation['education_month_of_passing'] . ' ' . $rowEducation['education_year_of_passing']; ?></p></div>
                                                        <div class="col-md-3"><p class="text-success">Completed</p></div>
                                                    </div>
                                                </div>
                                                <?php } } ?>
                                            </div>
                                            
                                            <div class="tab-pane fade" id="nav-four" role="tabpanel" aria-labelledby="nav-four-tab">
                                                <h5 class="section-title">Remarks</h5>
                                                <?php
                                                    $resRemarks = mysqli_query($conn, "SELECT practitioner_remarks FROM practitioner_remarks WHERE practitioner_id = '$id' AND practitioner_status = 'Active' ORDER BY practitioner_remarks_id DESC");
                                                    if (mysqli_num_rows($resRemarks) > 0) {
                                                        while ($rowRemarks = mysqli_fetch_assoc($resRemarks)) {
                                                ?>
                                                <div class="remarks-item mb-4"><p class="form-control-static"><?php echo $rowRemarks['practitioner_remarks']; ?></p></div>
                                                <?php } } ?>
                                            </div>
                                            
                                            <div class="tab-pane fade" id="nav-five" role="tabpanel" aria-labelledby="nav-five-tab">
                                                <h5 class="section-title">CDE Points</h5>
                                                <div class="row">
                                                    <div class="col-md-12"><div class="form-group"><label>Total Points Obtained</label><p class="form-control-static"><?php $resPoints = mysqli_query($conn, "SELECT COALESCE(SUM(cde_points),0) AS points FROM cde_points WHERE practitioner_id = '$id'"); if(mysqli_num_rows($resPoints)>0){ $resPoints = mysqli_fetch_assoc($resPoints); echo $resPoints['points']; } else { echo "0"; } ?></p></div></div>
                                                    <div class="col-md-12"><div class="form-group"><label>Used Points</label><p class="form-control-static">0</p></div></div>
                                                    <div class="col-md-12"><div class="form-group"><label>Total Available Points</label><p class="form-control-static">0</p></div></div>
                                                </div>
                                            </div>
                                            
                                            <div class="tab-pane fade" id="nav-six" role="tabpanel" aria-labelledby="nav-six-tab">
                                                <h5 class="section-title">Sign/LTI</h5>
                                                <div class="row">
                                                    <div class="col-md-4"><div class="border p-3 text-center"><img style="height:200px;width:100%;object-fit:contain" src="<?php if (!empty($rowDentists['practitioner_signature'])) { echo 'admin/images/dentist/' . $rowDentists['practitioner_signature']; } else { echo 'admin/images/other/dentist.png'; } ?>" alt="Signature"><h6 class="mt-3">Signature</h6></div></div>
                                                    <div class="col-md-4"><div class="border p-3 text-center"><img style="height:200px;width:100%;object-fit:contain" src="<?php if (!empty($rowDentists['practitioner_thumb'])) { echo 'admin/images/dentist/' . $rowDentists['practitioner_thumb']; } else { echo 'admin/images/other/dentist.png'; } ?>" alt="Thumb"><h6 class="mt-3">Thumb</h6></div></div>
                                                    <div class="col-md-4"><div class="border p-3 text-center"><img src="<?= $qrcode ?>" alt="Barcode" style="height:200px;width:100%;object-fit:contain"><h6 class="mt-3">Barcode</h6></div></div>
                                                </div>
                                            </div>
                                            
                                            <div class="tab-pane fade" id="nav-seven" role="tabpanel" aria-labelledby="nav-seven-tab">
                                                <h5 class="section-title">Change of Address</h5>
                                                <form method="post" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="col-md-12"><div class="alert alert-info"><strong>Note:</strong> To update or correct your information such as name, phone number, or address, please submit the detailed description and a valid proof document, such as an Aadhar card.</div></div>
                                                        <div class="col-md-8"><div class="form-group"><label>Description</label><textarea class="form-control" required name="description" rows="4"></textarea></div></div>
                                                        <div class="col-md-8"><div class="form-group"><label>Proof Attachment</label><input type="file" class="form-control" required name="image" accept=".jpg, .jpeg, .png, .pdf"><small class="text-muted">Attach proof such as Aadhaar card or identity card [should not exceed 2 MB in size].</small></div></div>
                                                        <div class="col-md-12"><button type="submit" name="address_request" class="btn btn-primary">Submit Request</button></div>
                                                    </div>
                                                </form>
                                            </div>

                                            <?php if (mysqli_num_rows($resAddress2) > 0) { ?>
                                            <div class="modal fade" id="editProfessional" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <form method="POST">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="editLabel">Edit Professional Information</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Address Line 1<span class="text-danger">*</span></label><input autocomplete="off" type="text" value="<?php echo $resAddress2['practitioner_address_line1']; ?>" name="address_line1" maxlength="1000" title="Please enter a valid address." required class="form-control"></div>
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Address Line 2<span class="text-danger">*</span></label><input autocomplete="off" type="text" value="<?php echo $resAddress2['practitioner_address_line2']; ?>" name="address_line2" maxlength="1000" title="Please enter a valid address." required class="form-control"></div>
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Professional Phone<span class="text-danger">*</span></label><input autocomplete="off" type="text" value="<?php echo $resAddress2['practitioner_address_phoneno']; ?>" required name="mobile_number" maxlength="10" pattern="[0-9]{10}" title="Accept 10 digit number" class="form-control"></div>
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Secondary Number</label><input autocomplete="off" type="text" value="<?php echo $resAddress2['practitioner_address_secondary_phoneno']; ?>" name="secondary_number" maxlength="12" pattern="[0-9]{6,12}" title="Accept 6 to 12 digit number" class="form-control"></div>
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Professional Postal Code<span class="text-danger">*</span></label><input autocomplete="off" type="text" required value="<?php echo $resAddress2['practitioner_address_pincode']; ?>" name="postal_code" maxlength="6" pattern="[0-9]{6}" title="Accept 6 digit number" class="form-control"></div>
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Professional District<span class="text-danger">*</span></label><input autocomplete="off" type="text" value="<?php echo $resAddress2['practitioner_address_city']; ?>" name="residential_city" maxlength="1000" required title="Please enter a valid district." class="form-control"></div>
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Professional State<span class="text-danger">*</span></label><input name="residential_state" required autocomplete="off" value="<?php echo $resAddress2['state_name']; ?>" title="Please enter a valid state." id="stateInputProfessional" class="form-control"></div>
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Professional Country<span class="text-danger">*</span></label><input required autocomplete="off" value="<?php echo $resAddress2['country_name']; ?>" name="residential_country" id="countryInputProfessional" title="Please enter a valid country." class="form-control"></div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" name="update_professional" class="btn btn-primary">Update</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            
                                            <div class="modal fade" id="addProfessional" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <form method="POST">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="addLabel">Add Professional Information</h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Address Line 1<span class="text-danger">*</span></label><input autocomplete="off" type="text" name="address_line1" maxlength="1000" title="Please enter a valid address." required class="form-control"></div>
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Address Line 2<span class="text-danger">*</span></label><input autocomplete="off" type="text" name="address_line2" maxlength="1000" title="Please enter a valid address." required class="form-control"></div>
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Professional Phone<span class="text-danger">*</span></label><input autocomplete="off" type="text" required name="mobile_number" maxlength="10" pattern="[0-9]{10}" title="Accept 10 digit number" class="form-control"></div>
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Secondary Number</label><input autocomplete="off" type="text" name="secondary_number" maxlength="12" pattern="[0-9]{6,12}" title="Accept 6 to 12 digit number" class="form-control"></div>
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Professional Postal Code<span class="text-danger">*</span></label><input autocomplete="off" type="text" required name="postal_code" maxlength="6" pattern="[0-9]{6}" title="Accept 6 digit number" class="form-control"></div>
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Professional District<span class="text-danger">*</span></label><input autocomplete="off" type="text" name="residential_city" maxlength="1000" required title="Please enter a valid district." class="form-control"></div>
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Professional State<span class="text-danger">*</span></label><input name="residential_state" required autocomplete="off" title="Please enter a valid state." id="stateInputProfessional" class="form-control"></div>
                                                                    <div class="col-xl-6 mb-3"><label class="form-label">Professional Country<span class="text-danger">*</span></label><input required autocomplete="off" name="residential_country" id="countryInputProfessional" title="Please enter a valid country." class="form-control"></div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" name="add_professional" class="btn btn-primary">Add</button>
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
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verify Bootstrap is loaded
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap JS is not loaded. Check the script source.');
            return;
        }

        // Initialize tabs
        const tabList = document.querySelectorAll('.nav-tabs .nav-link');
        tabList.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                const bsTab = new bootstrap.Tab(this);
                bsTab.show();
            });
        });

        // Handle direct URL access
        if (window.location.hash) {
            const hash = window.location.hash;
            const targetButton = document.querySelector(`.nav-link[data-bs-target="${hash}"]`);
            if (targetButton) {
                const bsTab = new bootstrap.Tab(targetButton);
                bsTab.show();
            }
        }

        // Update URL hash on tab switch
        document.querySelectorAll('.nav-link[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(event) {
                window.location.hash = event.target.getAttribute('data-bs-target');
            });
        });

        // Initialize modals with error handling
        const editModalElement = document.getElementById('editProfessional');
        const addModalElement = document.getElementById('addProfessional');
        const editProfessionalModal = editModalElement ? new bootstrap.Modal(editModalElement) : null;
        const addProfessionalModal = addModalElement ? new bootstrap.Modal(addModalElement) : null;

        const editButton = document.querySelector('[data-bs-target="#editProfessional"]');
        const addButton = document.querySelector('[data-bs-target="#addProfessional"]');

        if (editButton && editProfessionalModal) {
            editButton.addEventListener('click', function() {
                editProfessionalModal.show();
            });
        } else {
            console.warn('Edit professional button or modal not found.');
        }

        if (addButton && addProfessionalModal) {
            addButton.addEventListener('click', function() {
                addProfessionalModal.show();
            });
        } else {
            console.warn('Add professional button or modal not found.');
        }
    });
    </script>
</body>
</html>
<?php } ?>