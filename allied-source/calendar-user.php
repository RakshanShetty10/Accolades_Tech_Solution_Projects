<?php
session_start();
require_once 'config/connection.php';
require_once 'config/utils.php';

date_default_timezone_set('Asia/Kolkata');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="robots" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="W3crm:Customer Relationship Management Admin Bootstrap 5 Template">
    <meta property="og:title" content="W3crm:Customer Relationship Management Admin Bootstrap 5 Template">
    <meta property="og:description" content="W3crm:Customer Relationship Management Admin Bootstrap 5 Template">
    <meta property="og:image" content="https://w3crm.dexignzone.com/xhtml/social-image.png">
    <meta name="format-detection" content="telephone=no">
    <title><?php echo $site_full; ?> - Calendar</title>
    <link rel="shortcut icon" type="image/png" href="images/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link href="vendor/fullcalendar/css/main.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <!-- <link href="css/style.css" rel="stylesheet"> -->
    <!-- <link href="assets/css/module-css/sidebar.css" rel="stylesheet"> -->
    <?php require_once 'include/header-link.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            padding: 20px 0;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        
        .page-title {
            text-align: center;
            margin-bottom: 30px;
            color: #192a56;
        }
        
        .page-title h2 {
            font-weight: 600;
        }
        
        .fc-daygrid-day.highlight-day {
            background-color: red !important;
        }

        .fc-h-event,
        .fc-v-event {
            background: red;
            border-radius: .42rem;
        }

        .fc-daygrid-day.highlight-day .fc-daygrid-day-top {
            color: white !important;
        }

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 50px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 0 auto;
            padding: 25px;
            border: 1px solid #888;
            width: 100%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        /* Improve close button styling */
        .close {
            color: #999;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            right: 20px;
            top: 15px;
            transition: all 0.2s ease;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
            border-radius: 50%;
            z-index: 10;
        }

        .close:hover,
        .close:focus {
            color: #000;
            background-color: #eee;
            text-decoration: none;
            cursor: pointer;
            transform: scale(1.1);
        }
        
        /* Button group styling */
        .form-buttons {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 15px;
        }
        
        .btn-cancel {
            background-color: #f5f5f5;
            color: #333;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            flex: 1;
        }
        
        .btn-cancel:hover {
            background-color: #e0e0e0;
        }
        
        .form-buttons .btn-primary {
            flex: 2;
        }

        /* Hide nice-select completely - this is the element causing issues */
        .nice-select {
            display: none !important;
            visibility: hidden !important;
            height: 0 !important;
            width: 0 !important;
            opacity: 0 !important;
            position: absolute !important;
            pointer-events: none !important;
        }

        /* Reset any bootstrap-select styling that might interfere */
        .bootstrap-select {
            display: none !important;
            visibility: hidden !important;
        }

        /* Fixed dropdown styling */
        #slotDropdown {
            width: 100% !important;
            height: auto !important;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            background-color: #fff;
            appearance: menulist !important;
            -webkit-appearance: menulist !important;
            -moz-appearance: menulist !important;
            position: static !important;
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            color: #000;
            z-index: 1 !important;
            transform: none !important;
        }

        #slotDropdown option {
            display: block !important;
            visibility: visible !important;
            color: #000;
            background-color: #fff;
            padding: 5px;
        }

        select[name="category"] {
            width: 100% !important;
            height: auto !important;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            background-color: #fff;
            appearance: menulist !important;
            -webkit-appearance: menulist !important;
            -moz-appearance: menulist !important;
            position: static !important;
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            z-index: 10 !important;
        }

        select[name="category"] option {
            display: block !important;
            visibility: visible !important;
            color: #000;
            background-color: #fff;
            padding: 5px;
        }

        /* Ensure correct dropdown direction */
        .dropdown-menu {
            top: 100% !important;
            bottom: auto !important;
            left: 0 !important;
            right: auto !important;
            transform: none !important;
            position: absolute !important;
            margin-top: 2px !important;
            display: block !important;
        }

        /* Fix for the extra space after dropdowns */
        .form-control {
            margin-bottom: 0 !important;
            box-sizing: border-box;
            line-height: normal;
        }
        
        /* Improve label styling */
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        /* Fix for textarea spacing */
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        
        /* Improve button styling */
        .btn-primary {
            background-color: #192a56;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #273c75;
            transform: translateY(-2px);
        }
        
        .btn-primary:focus {
            outline: none;
            box-shadow: none;
        }

        /* Calendar Responsive Fixes */
        .app-fullcalendar {
            width: 100%;
            overflow-x: auto;
        }

        .fc .fc-toolbar {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25em;
            margin: 0;
        }

        .fc-header-toolbar.fc-toolbar {
            margin-bottom: 1em !important;
        }

        .fc-view-harness {
            min-width: 300px;
        }

        .fc-scrollgrid {
            border-collapse: collapse;
            width: 100%;
        }

        .fc-col-header, .fc-daygrid-body {
            width: 100% !important;
        }

        .fc .fc-daygrid-day-top {
            justify-content: center;
        }

        /* Media Queries for Responsive Design */
        @media (max-width: 1199px) {
            .modal-content {
                max-width: 600px;
                width: 90%;
            }
            
            .fc .fc-toolbar {
                padding: 0 10px;
            }
        }
        
        @media (max-width: 991px) {
            .modal-content {
                max-width: 550px;
                width: 90%;
            }
            
            .modal {
                padding-top: 60px;
            }
            
            .fc-toolbar-chunk {
                margin-bottom: 10px;
            }
            
            .fc .fc-toolbar-title {
                font-size: 1.1em;
            }
        }
        
        @media (max-width: 767px) {
            .modal-content {
                max-width: 500px;
                width: 90%;
                padding: 15px;
            }
            
            .row.mb-3 {
                margin-bottom: 0 !important;
            }
            
            .col-xl-6 {
                margin-bottom: 15px;
            }
            
            .form-control, 
            select[name="category"],
            #slotDropdown {
                font-size: 14px;
                padding: 8px;
            }
            
            .fc .fc-toolbar {
                flex-direction: column;
                align-items: center;
            }
            
            .fc-toolbar-chunk {
                margin-bottom: 10px;
                width: 100%;
                display: flex;
                justify-content: center;
            }
            
            .fc-header-toolbar.fc-toolbar .fc-toolbar-chunk:first-child {
                order: 2;
            }
            
            .fc-header-toolbar.fc-toolbar .fc-toolbar-chunk:nth-child(2) {
                order: 1;
            }
            
            .fc-header-toolbar.fc-toolbar .fc-toolbar-chunk:last-child {
                order: 3;
            }
        }
        
        @media (max-width: 480px) {
            .modal {
                padding-top: 30px;
            }
            
            .modal-content {
                max-width: 95%;
                width: 95%;
                padding: 15px 10px;
            }
            
            h3.col-5 {
                font-size: 18px;
                width: 100%;
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 5px;
            }
            
            h5.col-7 {
                font-size: 14px;
                width: 100%;
                flex: 0 0 100%;
                max-width: 100%;
                text-align: left !important;
            }
            
            .fc .fc-toolbar-title {
                font-size: 1em;
            }
            
            .fc .fc-button {
                padding: 0.2em 0.5em;
                font-size: 0.9em;
            }
            
            .fc-col-header-cell {
                font-size: 0.8em;
            }
            
            .fc-daygrid-day-number {
                font-size: 0.9em;
            }
        }

        /* Fix 2: Force dropdowns to display properly */
        .dropdown-menu {
            top: 100% !important;
            bottom: auto !important;
            left: 0 !important;
            right: auto !important;
            transform: none !important;
            position: absolute !important;
            margin-top: 2px !important;
            display: block !important;
        }
        
        /* Ensure selects show dropdown arrows properly */
        select.form-control {
            appearance: menulist !important;
            -webkit-appearance: menulist !important;
            -moz-appearance: menulist !important;
            background-image: none !important;
        }
        
        /* Override any Bootstrap's dropdown-menu-up class */
        .dropdown-menu-up {
            top: 100% !important;
            bottom: auto !important;
        }
        
        /* Ensure proper dropdown dimensions */
        .bootstrap-select .dropdown-menu {
            min-width: 100% !important;
            box-sizing: border-box !important;
            max-height: 300px !important;
        }
    </style>
</head>

<body data-typography="poppins" data-theme-version="light" data-layout="vertical" data-nav-headerbg="black" data-headerbg="color_1">

    <?php
    if (empty($_SESSION['_id'])) {
        echo "<script>swal.fire({text: 'Kindly login to proceed!',type: 'error',icon: 'error'}).then(function(){window.location = 'login.php';});</script>";
    } else {
        $id = $_SESSION['_id'];
    ?>
        <div class="boxed_wrapper ltr">

            <?php require_once 'include/header.php';
            $practitioner_sql = "Select * from practitioner where practitioner_id = '$id'";
            $practitioner_result = mysqli_query($conn, $practitioner_sql);
            $practitioner_row = mysqli_fetch_assoc($practitioner_result);

            if (empty($practitioner_row['practitioner_change_of_name'])) {

                $practitioner_name = $practitioner_row['practitioner_title'] . ' ' . $practitioner_row['practitioner_name'];
            } else {
                $practitioner_name = $practitioner_row['practitioner_title'] . ' ' . $practitioner_row['practitioner_change_of_name'];
            }

            if (isset($_POST['submit'])) {

                $name = addslashes(trim($_POST['name']));
                $phone = addslashes(trim($_POST['phone']));
                $mail = addslashes(trim($_POST['mail']));
                $category = addslashes(trim($_POST['category']));
                $slot = addslashes(trim($_POST['slot']));
                $description = addslashes(trim($_POST['description']));

                $date = addslashes(trim($_POST['date']));

                $date_c = date('Y-m-d H:i:s');
                $status = "Pending";

                $timestamp = strtotime($date);
                $selectedDay = date('l', $timestamp); // Get the day of the week

                $slotResult = mysqli_query($conn, "SELECT slot_timing, no_of_people FROM appointment_master WHERE weekday = '$selectedDay'");
                $slotInfo = mysqli_fetch_assoc($slotResult);
                $no_of_people = $slotInfo['no_of_people'];

                $res2 = mysqli_query($conn, "SELECT COUNT(appoint_id) AS slot_count FROM appointment 
                WHERE appoint_date = '$date' AND appoint_status = '$status' AND 
                appoint_slot_time = '$slot'");

                $res2 = mysqli_fetch_assoc($res2);

                if ($res2['slot_count'] >= $no_of_people) {

                    echo "<script>alert('You have exceeded the maximum limit of slots. You can only allocate up to 4 slots!');</script>";
                } else {

                    if (mysqli_query($conn, "INSERT INTO appointment (appoint_name, appoint_mobile, appoint_email, 
                    appoint_category, appoint_description, appoint_slot_time, appoint_status, appoint_created_by,
                    appoint_created_on, appoint_date) VALUES ('$name', '$phone', '$mail', '$category', 
                    '$description', '$slot', '$status', '$practitioner_name', '$date_c', '$date')")) {

                        echo "<script>Swal.fire({text: 'Slot added successfully!', icon: 'success'}).then(function(){window.location = 'calendar-user.php';});</script>";
                    } else {
                        echo "<script>Swal.fire({text: 'Unable to process your request!', icon: 'error'});</script>";
                    }
                }
            }
            ?>

            <section class="sidebar-page-container pt_10 pb_100 border-top">
                <div class="auto-container">
                    <div class="row clearfix">
                        <div class="col-lg-9 col-md-12 col-sm-12 content-side pl_60">
                            <div class="blog-standard-content">
                                <div class="news-block-three">
                                    <div class="inner-box">
                                        <div class="lower-content">
                                            <!-- <div class="widget-title mb_10">
                                                <li><a href="calendar-booked.php" style="display: inline-block; padding: 10px 20px; background-color: #2c2c2c; color: white; border-radius: 5px; text-decoration: none;">Booked Slot</a></li>
                                            </div> -->
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-xl-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div id="external-events"></div>
                                                                <div id="calendar" class="app-fullcalendar"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="modalOverlay" class="modal" style="display:none;"></div>
                                            <div id="calendarModal" class="modal" style="display:none;">
                                                <div class="modal-content">
                                                    <span class="close">&times;</span>
                                                    <form method="POST">
                                                        <div class="row">
                                                            <h3 class="col-5">Manage Slot</h3>
                                                            <h5 id="selectedDateDisplay" class="text-primary col-7 text-end"></h5>
                                                        </div>
                                                        <div id="slotdata" class=""></div>
                                                        <div class="col-xl-12 mb-3">
                                                            <label class="form-label">Slot Timing<span class="text-danger">*</span></label>
                                                            <select class="form-control" name="slot" id="slotDropdown"></select>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-xl-6">
                                                                <label class="form-label">Name<span class="text-danger">*</span></label>
                                                                <input autocomplete='off' type="text" class="form-control" required name="name" value="<?php echo $practitioner_name ?>" title="Enter valid name">
                                                            </div>
                                                            <div class="col-xl-6">
                                                                <label class="form-label">Phone<span class="text-danger">*</span></label>
                                                                <input autocomplete='off' type="text" class="form-control" pattern="[0-9]{6,13}" title="Accept 6 to 13 digit number" maxlength="13" name="phone" value="<?php echo $practitioner_row['practitioner_mobile_number'] ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-xl-6">
                                                                <label class="form-label">Mail<span class="text-danger">*</span></label>
                                                                <input autocomplete='off' type="email" class="form-control" required name="mail" value="<?php echo $practitioner_row['practitioner_email_id'] ?>" title="Enter valid name">
                                                            </div>
                                                            <div class="col-xl-6">
                                                                <label class="form-label">Category<span class="text-danger">*</span></label>
                                                                <select class="form-control" required name="category">
                                                                    <option value="">Choose</option>
                                                                    <option value="Registration">Registration</option>
                                                                    <option value="Renewal">Renewal</option>
                                                                    <option value="Noc">Noc</option>
                                                                    <option value="Duplicate Registration">Duplicate Registration</option>
                                                                    <option value="Good Standing Certificate">Good Standing Certificate</option>
                                                                    <option value="Change of Name">Change of Name</option>
                                                                    <option value="Miscellaneous">Miscellaneous</option>
                                                                    <option value="Restoration">Restoration</option>
                                                                    <option value="Smart Card">Smart Card</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-12 mb-4">
                                                            <label class="form-label">Description</label>
                                                            <textarea autocomplete='off' rows="6" class="form-control" name="description"></textarea>
                                                        </div>
                                                        <input type="hidden" id="selectedDate" name="date" />
                                                        <div class="col-xl-12">
                                                            <div class="form-buttons">
                                                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                                                <button type="button" class="btn btn-cancel">Cancel</button>
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
            </section>

            <?php require_once 'include/footer.php'; ?>

        </div>

    <?php
    }
    require_once 'include/footer-js.php';
    ?>

    <script>
        function closeModal() {
            document.getElementById('calendarModal').style.display = 'none';
            document.getElementById('modalOverlay').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Close button event listener
            document.querySelector('.close').addEventListener('click', closeModal);
            
            // Cancel button event listener
            document.querySelector('.btn-cancel').addEventListener('click', closeModal);
            
            // Modal overlay click to close
        document.getElementById('modalOverlay').addEventListener('click', closeModal);
        });

        function openModal() {
            document.getElementById('calendarModal').style.display = 'block';
            document.getElementById('modalOverlay').style.display = 'block';
        }
    </script>

    <script src="vendor/global/global.min.js"></script>
    <script src="vendor/moment/moment.min.js"></script>
    <script src="vendor/fullcalendar/js/main.min.js"></script>
    <script src="js/plugins-init/fullcalendar-init2.js"></script>
    <script src="vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/deznav-init.js"></script>

</body>

</html>